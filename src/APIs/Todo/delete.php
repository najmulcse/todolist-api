<?php

include "../Config/header.php";
use Wedevs\TodoList\Config\Database;
use Wedevs\TodoList\Object\Todo;

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$todo = new Todo($db);

// get product id
$data = json_decode(file_get_contents("php://input"));

// set product id to be deleted
$todo->id = $data->id;

// delete the product
if($todo->delete()){

    // set response code - 200 ok
    http_response_code(200);

    $message = "Todo deleted successfully.";
    $response['message'] = $message;
    $response['status'] = 'success';

    echo json_encode($response);
}

// if unable to delete the product
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    $message = "Unable to delete. Please try again";
    $response['message'] = $message;
    $response['status'] = 'error';

    echo json_encode($response);
}