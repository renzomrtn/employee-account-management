<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, X-API-Key, Access-Control-Allow-Methods, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../models/session_log.php";

$database = new Database();
$db = $database->connect();

$session_log = new Session_Log($db);

$result = $session_log->read();

$num = $result->rowCount();

if ($num > 0) {
    $employee_arr = array();
    $employee_arr['success'] = true;
    $employee_arr['data'] = array();

    while($row = $result -> fetch (PDO::FETCH_ASSOC)) {
        extract($row);

        $employee_item = array(
            'sl_id' => $sl_id,
            'a_id' => $a_id,
            'login_time' => $login_time,
            'status' => $status
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