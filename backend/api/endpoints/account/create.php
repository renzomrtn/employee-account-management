<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
require_once __DIR__ . "/../../config/database.php";

$database = new Database();
$conn = $database->connect();

if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection not available"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid input"
    ]);
    exit;
}

try {
    $sql = "INSERT INTO account
            (e_id, username, password, role)
            VALUES (:e_id, :username, :password, :role)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "SQL prepare failed"
        ]);
        exit;
    }

    $stmt->bindParam(':e_id', $data["e_id"]);
    $stmt->bindParam(':username', $data["username"]);
    $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role', $data["role"]);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Account added successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Execute failed"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage(),
        "a_id" => $conn->lastInsertId()
    ]);
}
exit;
