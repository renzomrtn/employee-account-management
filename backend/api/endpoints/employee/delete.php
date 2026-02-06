<?php
// api/endpoints/employee/delete.php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json;');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/database.php';
include_once '../../models/employee.php';

// instantiate database and employee object
$database = new Database();
$db = $database->connect();

// instantiate employee object
$employee = new Employee($db);

// get the posted data
$data = json_decode(file_get_contents("php://input"));

// set res_id to delete
$employee->res_id = $data->res_id;

// delete the employee
if($employee->delete()) {
    // convert to JSON
    echo json_encode(
        array('message' => 'Employee Deleted')
    );
} else {
    // convert to JSON
    echo json_encode(
        array('message' => 'Employee not Deleted')
    );
}

?>