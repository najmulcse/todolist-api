<?php

include "../Config/header.php";
use Wedevs\TodoList\Config\Database;
use Wedevs\TodoList\Object\Todo;

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$todo = new Todo($db);

// query products
$stmt = $todo->read();
$num = $stmt->rowCount();

// check if more than 0 record found

$response = array();
if($num>0){

    // products array
    $todo_arr = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        $todo_item = array(
            "id" => $row['id'],
            "title" => $row['title'],
            "is_completed" => $row['is_completed'],
        );

        array_push($todo_arr, $todo_item);
    }

    http_response_code(200);
    $response['data'] = $todo_arr;
    $response['status'] = 'success';
    echo json_encode($response);
}else{

    http_response_code(200);
    $response['data'] = [];
    $response['status'] = 'success';
    echo json_encode($response);
}