<?php
/**
 * Created by PhpStorm.
 * User: Najmul
 * Date: 4/1/2020
 * Time: 6:20 AM
 */

namespace NAJMULCSE\TODOLIST\APIs;


class TodoListController {

    private $db;
    private $requestMethod;
    private $apiName;

    private $todoQueryHandler;

    public function __construct($db, $requestMethod, $apiName)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->apiName = $apiName;

        $this->todoQueryHandler = new TodoQueryHandler($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->apiName == 'fetch') {
                    $response = $this->getAllTodoList();
                } else {
                    $response = $this->notFoundResponse();
                };
                break;
            case 'POST':
                if($this->apiName == 'create'){
                    $response = $this->createTodo();
                }else {
                    $response = $this->notFoundResponse();
                };
                break;
            case 'PUT':
                if($this->apiName == 'update'){
                    $response = $this->update();
                }else if($this->apiName == 'toggleStatus'){
                    $response = $this->toggleStatus();
                }else{
                    $response = $this->notFoundResponse();
                }

                break;
            case 'DELETE':
                if($this->apiName == 'delete'){
                    $response = $this->delete();
                }else{
                    $response = $this->notFoundResponse();
                }

                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        echo json_encode($response);
    }

    private function getAllTodoList()
    {
        try{
            $data = $this->todoQueryHandler->findAll();
        
            $result['todos'] = $data;
            $response['status_code'] = 200;
            $response['message'] = "Data retrieved successfully.";
            $response['data'] = $result;
            return $response;
        }catch( \Exception $ex){
            return $this->unprocessableEntityResponse();
        }
    }



    private function createTodo()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $data = @$input['data'];
        
        if (! $this->validateTodo($data)) {
            return $this->unprocessableEntityResponse();
        }
        $insertedData['id'] = $this->todoQueryHandler->insert($data);
        $response['status_code'] = 200;
        $response['data'] = $insertedData;
        $response['message'] = "Todo created successfully.";
        return $response;
    }

    private function update()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $data = @$input['data'];
        $id = $data['id'];
        $result = $this->todoQueryHandler->find($id);
        
        if (! $result) {
            return $this->notFoundResponse();
        }
        if (! $this->validateTodo($data)) {
            return $this->unprocessableEntityResponse();
        }
        //print_r($data);die();
        $status = $this->todoQueryHandler->update($id, $data);
        if(!$status){
            return $this->sendErrorResponse(422, "Updating failed.");
        }
        $response['status_code'] = 200;
        $response['data'] = null;
        $response['message'] = "Updated successfully.";
        return $response;
    }
    private function toggleStatus()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $data = @$input['data'];
        $id = $data['id'];
        $isCompleted = $data['is_completed'];
        $result = $this->todoQueryHandler->find($id);
        
        if (! $result) {
            return $this->notFoundResponse();
        }
        if (! $this->validateToggleData($data)) {
            return $this->unprocessableEntityResponse();
        }
        $status = $this->todoQueryHandler->toggleStatus($id, $isCompleted);
        if(!$status){
            return $this->sendErrorResponse(422, "Updating failed.");
        }
        $response['status_code'] = 200;
        $response['data'] = null;
        $response['message'] = "Status updated successfully.";
        return $response;
    }

    private function delete()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $id = @$input['id'];
        
        $result = $this->todoQueryHandler->find($id);
    
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->todoQueryHandler->delete($id);
        $response['status_code'] = 200;
        $response['message'] = "Deleted successfully.";
        $response['data'] = null;
        return $response;
    }

    private function validateTodo($input)
    {
        if (! isset($input['title'])) {
            return false;
        }

        return true;
    }

    private function validateToggleData($input){
        if(!isset($input['is_completed'])){
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code'] = 422;
        $response['data'] = [
            'error' => 'Invalid input'
        ];
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code'] = 404;
        $response['message'] = "Not Found";
        $response['data'] = null;
        return $response;
    }
    private function sendErrorResponse($message, $code)
    {
        $response['status_code'] = $code;
        $response['data'] = null;
        $response['message'] = $message;
        return $response;
    }
}