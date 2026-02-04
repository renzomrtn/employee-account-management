<?php
session_set_cookie_params([
    'path' => '/Activity1',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include_once '../../config/database.php';
include_once '../../models/account.php';

$database = new Database();
$db = $database->connect();

// Read JSON body
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Employee ID and password are required'
    ]);
    exit;
}

// Sanitize
$username   = trim($data->username);
$password = $data->password;

try {
    $account = new Account($db);

    $loginResult = $account->login($username, $password);

    if ($loginResult['success']) {

        $_SESSION['account_id']  = $loginResult['account_id'];
        $_SESSION['username']      = $username;
        $_SESSION['role']          = $loginResult['role']; 
        $_SESSION['login_time']  = time();
        $_SESSION['session_token'] = bin2hex(random_bytes(32));

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'account_id' => $loginResult['account_id'],
            'username' => $username,
            'role' => $loginResult['role'],
            'session_token' => $_SESSION['session_token']
        ]);

    } else {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Username or password'
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error occurred'
    ]);
}
