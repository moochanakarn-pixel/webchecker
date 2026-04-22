<?php
require_once __DIR__ . '/config.php';

session_start();

// ถ้า login แล้ว ไปหน้าหลักเลย
if (!empty($_SESSION['kds_staff_id'])) {
    header('Location: checker.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim((string)($_POST['staff_code'] ?? ''));
    $pass = trim((string)($_POST['password'] ?? ''));

    if ($code === '' || $pass === '') {
        $error = 'กรุณากรอกรหัสพนักงานและรหัสผ่าน';
    } else {
        try {
            $conn = getDbConnection();

            $stmt = $conn->prepare("
                SELECT StaffID, StaffFirstName, StaffLastName, StaffCode
                FROM staffs
                WHERE StaffCode = ?
                  AND StaffPassword = ?
                  AND Activated = 1
                  AND Deleted = 0
                LIMIT 1
            ");
            // SHA1 ตัวพิมพ์ใหญ่ ตรงกับที่ DB เก็บ
            $sha1pass = strtoupper(sha1($pass));
            $stmt->bind_param('ss', $code, $sha1pass);
            $stmt->execute();
            $result = $stmt->get_result();
            $staff = $result->fetch_assoc();
            $stmt->close();
            $conn->close();

            if ($staff) {
                session_regenerate_id(true);
                $_SESSION['kds_staff_id']   = $staff['StaffID'];
                $_SESSION['kds_staff_code'] = $staff['StaffCode'];
                $_SESSION['kds_staff_name'] = trim($staff['StaffFirstName'] . ' ' . $staff['StaffLastName']);
                header('Location: checker.php');
                exit;
            } else {
                $error = 'รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง';
            }
        } catch (Exception $e) {
            $error = 'เชื่อมต่อฐานข้อมูลไม่ได้ กรุณาลองใหม่';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ — <?php echo h(APP_TITLE); ?></title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background: #edf5ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 12px 32px rgba(15,23,42,.10);
            padding: 40px 36px 36px;
            width: 100%;
            max-width: 360px;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            color: #122033;
            text-align: center;
            margin-bottom: 6px;
        }
        .subtitle {
            font-size: 13px;
            color: #6b7a90;
            text-align: center;
            margin-bottom: 28px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #3a4a5c;
            margin-bottom: 6px;
        }
        input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #dbe8f7;
            border-radius: 10px;
            font-size: 15px;
            color: #122033;
            background: #f8fbff;
            outline: none;
            transition: border-color .2s;
            margin-bottom: 18px;
        }
        input:focus { border-color: #1683ff; background: #fff; }
        .btn {
            width: 100%;
            padding: 12px;
            background: #1683ff;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
        }
        .btn:hover { background: #0f69cf; }
        .error {
            background: #ffe8e4;
            color: #c0392b;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 18px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="title"><?php echo h(APP_TITLE); ?></div>
    <div class="subtitle">กรุณาเข้าสู่ระบบด้วยรหัสพนักงาน</div>

    <?php if ($error !== ''): ?>
        <div class="error"><?php echo h($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="staff_code">รหัสพนักงาน</label>
        <input type="text" id="staff_code" name="staff_code"
               placeholder="เช่น 101"
               value="<?php echo h($_POST['staff_code'] ?? ''); ?>"
               autocomplete="username" required>

        <label for="password">รหัสผ่าน</label>
        <input type="password" id="password" name="password"
               placeholder="รหัสผ่าน"
               autocomplete="current-password" required>

        <button type="submit" class="btn">เข้าสู่ระบบ</button>
    </form>
</div>
</body>
</html>
