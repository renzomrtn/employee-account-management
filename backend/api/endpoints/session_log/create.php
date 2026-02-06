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
    $sql = "INSERT INTO session_log
            (a_id, timestamp, status)
            VALUES (:a_id, :timestamp, :status)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "SQL prepare failed"
        ]);
        exit;
    }

    $stmt->bindParam(':a_id', $data["a_id"]);
    $stmt->bindParam(':timestamp', $data["timestamp"]);
    $stmt->bindParam(':status', $data["status"]);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Session Log added successfully"
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
        "message" => "Database error: " . $e->getMessage()
    ]);
}
exit;