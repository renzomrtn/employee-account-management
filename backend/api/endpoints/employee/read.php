<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, X-API-Key, Access-Control-Allow-Methods, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../models/employee.php";

$database = new Database();
$db = $database->connect();

$employee = new Employee($db);

$result = $employee->read();

$num = $result->rowCount();

if ($num > 0) {
    $employee_arr = array();
    $employee_arr['success'] = true;
    $employee_arr['data'] = array();

    while($row = $result -> fetch (PDO::FETCH_ASSOC)) {
        extract($row);

        $employee_item = array(
            'e_id' => $e_id,
            'name' => $name,
            'address' => $address,
            'birthday' => $birthday,
            'position' => $position,
            'department' => $department
        );        

        array_push($employee_arr['data'], $employee_item);
    }

    echo json_encode($employee_arr);

} else {
    echo json_encode([
        'data' => []
    ]);
}

?>