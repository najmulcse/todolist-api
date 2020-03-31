<?php
/**
 * Created by PhpStorm.
 * User: Najmul
 * Date: 3/30/2020
 * Time: 8:20 AM
 */

include "../Config/header.php";
require_once __DIR__ . "../../../vendor/autoload.php";
use Wedevs\TodoList\APIs\Config\Database;
use Wedevs\TodoList\Object\Todo;


try{

    $database = new Database();
    $db = $database->getConnection();

    $todo = new Todo($db);

// get posted data
    $data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
    if(
    !empty($data->title)

    ){

        // set product property values
        $todo->title = $data->title;

        // create the product
        if($todo->create()){

            // set response code - 201 created
            http_response_code(201);
            $message = "Product created successfully.";
            $response['message'] = $message;
            $response['status'] = 'success';

            echo json_encode($response);
        }

        // if unable to create the product, tell the user
        else{

            // set response code - 503 service unavailable
            http_response_code(503);
            $message = "Unable to create todo.";
            $response['message'] = $message;
            $response['status'] = 'error';

            echo json_encode($response);
        }
    }else{

        // set response code - 400 bad request
        http_response_code(400);
        $message = "Unable to create todo. Need more required fields";
        $response['message'] = $message;
        $response['status'] = 'error';
        echo json_encode($response);
    }
}catch (\Exception $exception){
    var_dump($exception);
}
