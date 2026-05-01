<?php
// screen_lock.php v10 — server-side screen counter (ใช้ file แทน DB)
// 1. auth_check.php: require_once __DIR__ . '/screen_lock.php';
// 2. checker.php in <head>: screenLockJavascript();

if (!defined('SCREEN_LOCK_ENABLED'))       { define('SCREEN_LOCK_ENABLED',       true); }
if (!defined('SCREEN_LOCK_HEARTBEAT_SEC')) { define('SCREEN_LOCK_HEARTBEAT_SEC', 8);    }
if (!defined('SCREEN_LOCK_MAX_SCREENS'))   { define('SCREEN_LOCK_MAX_SCREENS',   3);    }
// timeout: ถ้า instance ไม่ ping นานกว่านี้ถือว่าปิดไปแล้ว
if (!defined('SCREEN_LOCK_TIMEOUT_SEC'))   { define('SCREEN_LOCK_TIMEOUT_SEC',   20);   }

if (!SCREEN_LOCK_ENABLED) {
    if (!function_exists('screenLockJavascript')) {
        function screenLockJavascript() { return; }
    }
    return;
}

// ── File storage ────────────────────────────────────
function slStorePath() {
    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'sl_data';
    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0755, true) && !is_dir($dir)) {
            return __DIR__ . DIRECTORY_SEPARATOR . 'screens.json'; // fallback
        }
        // กัน web access
        file_put_contents($dir . DIRECTORY_SEPARATOR . '.htaccess', 'Deny from all');
        file_put_contents($dir . DIRECTORY_SEPARATOR . 'web.config',
            '<?xml version="1.0"?><configuration><system.webServer><security><requestFiltering><denyUrlSequences><add sequence="/sl_data"/></denyUrlSequences></requestFiltering></security></system.webServer></configuration>');
    }
    return $dir . DIRECTORY_SEPARATOR . 'screens.json';
}

function slReadScreens() {
    $path = slStorePath();
    if (!file_exists($path)) return array();
    $raw  = @file_get_contents($path);
    if ($raw === false || trim($raw) === '') return array();
    $data = @json_decode($raw, true);
    if (!is_array($data)) {
        // JSON พัง — reset
        @unlink($path);
        return array();
    }
    return $data;
}

function slWriteScreens($data) {
    $path = slStorePath();
    $tmp  = $path . '.tmp';
    $json = json_encode($data);
    if (@file_put_contents($tmp, $json, LOCK_EX) !== false) {
        @rename($tmp, $path); // atomic swap
    }
}

function slCleanExpired($screens) {
    $now     = time();
    $timeout = (int)SCREEN_LOCK_TIMEOUT_SEC;
    $cleaned = array();
    foreach ($screens as $id => $info) {
        if (($now - (int)$info['last']) < $timeout) {
            $cleaned[$id] = $info;
        }
    }
    return $cleaned;
}

function slMakeToken() {
    return md5(uniqid('', true) . microtime(true) . mt_rand(100000, 999999));
}

// ── AJAX handlers ────────────────────────────────────
if (isset($_GET['screen_lock_action'])) {
    @set_time_limit(3);
    $act = (string)$_GET['screen_lock_action'];

    // ping: instance บอกว่ายังอยู่
    if ($act === 'ping') {
        $iid = isset($_GET['iid']) ? preg_replace('/[^a-z0-9]/', '', (string)$_GET['iid']) : '';
        if ($iid === '') { header('Content-Type: application/json'); echo '{"ok":false}'; exit; }

        $screens = slCleanExpired(slReadScreens());
        $max     = (int)SCREEN_LOCK_MAX_SCREENS;
        $inList  = isset($screens[$iid]);

        if (!$inList) {
            // instance นี้ถูกเอาออก (โดน kick) หรือ timeout แล้ว
            header('Content-Type: application/json');
            echo '{"ok":false,"reason":"kicked"}';
            exit;
        }

        // อัพเดต last active
        $screens[$iid]['last'] = time();
        slWriteScreens($screens);
        header('Content-Type: application/json');
        echo '{"ok":true,"count":' . count($screens) . ',"max":' . $max . '}';
        exit;
    }

    // register: instance ใหม่ขอเข้า
    if ($act === 'register') {
        $iid = isset($_GET['iid']) ? preg_replace('/[^a-z0-9]/', '', (string)$_GET['iid']) : '';
        if ($iid === '') { header('Content-Type: application/json'); echo '{"ok":false}'; exit; }

        $screens = slCleanExpired(slReadScreens());
        $max     = (int)SCREEN_LOCK_MAX_SCREENS;

        if (isset($screens[$iid])) {
            // มีอยู่แล้ว — update และ allow
            $screens[$iid]['last'] = time();
            slWriteScreens($screens);
            header('Content-Type: application/json');
            echo '{"ok":true,"count":' . count($screens) . ',"max":' . $max . '}';
            exit;
        }

        if (count($screens) >= $max) {
            // เกิน limit
            header('Content-Type: application/json');
            echo '{"ok":false,"reason":"limit","count":' . count($screens) . ',"max":' . $max . '}';
            exit;
        }

        $screens[$iid] = array('last' => time());
        slWriteScreens($screens);
        header('Content-Type: application/json');
        echo '{"ok":true,"count":' . count($screens) . ',"max":' . $max . '}';
        exit;
    }

    // unregister: instance ปิดตัว
    if ($act === 'unregister') {
        $iid = isset($_GET['iid']) ? preg_replace('/[^a-z0-9]/', '', (string)$_GET['iid']) : '';
        $screens = slCleanExpired(slReadScreens());
        unset($screens[$iid]);
        slWriteScreens($screens);
        header('Content-Type: application/json');
        echo '{"ok":true}';
        exit;
    }

    // force_kick: kick oldest แล้วเพิ่มตัวเอง
    if ($act === 'force_kick') {
        $iid = isset($_GET['iid']) ? preg_replace('/[^a-z0-9]/', '', (string)$_GET['iid']) : '';
        $screens = slCleanExpired(slReadScreens());
        $max     = (int)SCREEN_LOCK_MAX_SCREENS;

        // kick oldest
        if (count($screens) >= $max) {
            $oldest = null; $oldestTime = PHP_INT_MAX;
            foreach ($screens as $id => $info) {
                if ((int)$info['last'] < $oldestTime) {
                    $oldest = $id; $oldestTime = (int)$info['last'];
                }
            }
            if ($oldest) unset($screens[$oldest]);
        }

        $screens[$iid] = array('last' => time());
        slWriteScreens($screens);
        header('Content-Type: application/json');
        echo '{"ok":true,"count":' . count($screens) . ',"max":' . $max . '}';
        exit;
    }
}

$GLOBALS['_slMaxScreens'] = (int)SCREEN_LOCK_MAX_SCREENS;
$GLOBALS['_slHbMs']       = (int)(SCREEN_LOCK_HEARTBEAT_SEC * 1000);

if (!function_exists('screenLockJavascript')) {
    function screenLockJavascript() {
        $cfg = array(
            'hb'         => $GLOBALS['_slHbMs'],
            'maxScreens' => $GLOBALS['_slMaxScreens'],
            't1'         => 'เกินจำนวนหน้าจอที่อนุญาต',
            'd1'         => 'ระบบอนุญาตสูงสุด ' . $GLOBALS['_slMaxScreens'] . ' หน้าจอ กรุณาปิดหน้าจออื่นก่อน',
            't2'         => 'ถูก Kick ออก',
            'd2'         => 'มีการบังคับเข้าใช้งานจากหน้าจออื่น',
            'btn'        => 'บังคับเข้าใช้งาน (kick หน้าจอเก่าสุด)',
            'note'       => 'หน้าจอที่เปิดนานที่สุดจะถูกล็อคออกอัตโนมัติ',
        );
        $json = json_encode($cfg, JSON_UNESCAPED_UNICODE);
        // dynamic path — ใช้ได้ทุก subfolder
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $jsUrl = rtrim($scriptDir, '/') . '/screen_lock.js';
        echo '<script src="' . htmlspecialchars($jsUrl, ENT_QUOTES) . '"></script>' . "\n";
    }
}
