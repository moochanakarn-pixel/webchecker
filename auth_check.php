<?php
// ใส่บรรทัดแรกของทุกหน้าที่ต้องการป้องกัน:
// require_once __DIR__ . '/auth_check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['kds_staff_id'])) {
    // AJAX request → ส่ง 401 แทน redirect
    if (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error' => 'session_expired']);
        exit;
    }

    header('Location: login.php');
    exit;
}
