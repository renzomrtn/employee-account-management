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
    $sql = "INSERT INTO employee
            (name, address, birthday, position, department)
            VALUES (:name, :address, :birthday, :position, :department)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            "success" => false,
            "message" => "SQL prepare failed"
        ]);
        exit;
    }

    $stmt->bindParam(':name', $data["name"]);
    $stmt->bindParam(':address', $data["address"]);
    $stmt->bindParam(':birthday', $data["birthday"]);
    $stmt->bindParam(':position', $data["position"]);
    $stmt->bindParam(':department', $data["department"]);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Employee added successfully",
            "e_id" => $conn->lastInsertId()
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
