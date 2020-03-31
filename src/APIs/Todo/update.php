<?php

include "../Config/header.php";
use Wedevs\TodoList\Config\Database;
use Wedevs\TodoList\Object\Todo;

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$todo = new Todo($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of product to be edited
$todo->id = $data->id;

// set product property values
$todo->title = $data->title;

// update the product
$response = array();
if($todo->update()){

    // set response code - 200 ok
    http_response_code(200);

    $message = "Todo updated successfully.";
    $response['message'] = $message;
    $response['status'] = 'success';

    echo json_encode($response);
}

else{

    // set response code - 503 service unavailable
    http_response_code(503);
    $message = "Unable to update todo.";
    $response['message'] = $message;
    $response['status'] = 'error';

    echo json_encode($response);
}