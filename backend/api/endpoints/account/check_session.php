<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if (isset($_SESSION['account_id']) && isset($_SESSION['role'])) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'authenticated' => true,
        'role' => $_SESSION['role'],
        'username' => $_SESSION['username']
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'authenticated' => false,
        'message' => 'Not authenticated'
    ]);
}
?>