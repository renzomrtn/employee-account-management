<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json;');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/database.php';
include_once '../../models/account.php';

// instantiate database and account object
$database = new Database();
$db = $database->connect();

// instantiate account object
$account = new Account($db);

// get the posted data
$data = json_decode(file_get_contents("php://input"));

// set res_id to delete
$account->res_id = $data->res_id;

// delete the account
if($account->delete()) {
    // convert to JSON
    echo json_encode(
        array('message' => 'Account Deleted')
    );
} else {
    // convert to JSON
    echo json_encode(
        array('message' => 'Account not Deleted')
    );
}

?>