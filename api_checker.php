<?php
// ── Bootstrap ────────────────────────────────────────
ob_start();
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// session ก่อน require เสมอ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// กัน HTML หลุดเป็น response — ต้องทำก่อน require
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // log เงียบๆ
    error_log("[$errno] $errstr in $errfile:$errline");
    return true;
});

set_exception_handler(function($e) {
    while (ob_get_level()) ob_end_clean();
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE | (defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_INVALID_UTF8_SUBSTITUTE : 0));
    exit;
});

// เช็คไฟล์ก่อน require
if (!file_exists(__DIR__ . '/config.php')) {
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'config.php missing']);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_check.php';
session_write_close(); // release session file lock — ไม่มีการเขียน session หลังจุดนี้

// timeout
@set_time_limit(30);