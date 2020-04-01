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
        $result = $this->todoQueryHandler->findAll();
        $response['status_code'] = 200;
        $response['message'] = "Data retrieved successfully.";
        $response['body'] = $result;
        return $response;
    }



    private function createTodo()
    {
        $input = $_POST;

        if (! $this->validateTodo($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->todoQueryHandler->insert($input);
        $response['status_code'] = 200;
        $response['body'] = null;
        $response['message'] = "Todo created successfully.";
        return $response;
    }

    private function update()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $id = @$input['id'];

        $result = $this->todoQueryHandler->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        if (! $this->validateTodo($input)) {
            return $this->unprocessableEntityResponse();
        }
        $status = $this->todoQueryHandler->update($id, $input);
        if(!$status){
            return $this->sendErrorResponse(422, "Updating failed.");
        }
        $response['status_code'] = 200;
        $response['body'] = null;
        $response['message'] = "Updated successfully.";
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
        $response['body'] = null;
        return $response;
    }

    private function validateTodo($input)
    {
        if (! isset($input['title'])) {
            return false;
        }

        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code'] = 422;
        $response['body'] = [
            'error' => 'Invalid input'
        ];
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code'] = 404;
        $response['body'] = null;
        $response['message'] = "Not Found";
        return $response;
    }
    private function sendErrorResponse($message, $code)
    {
        $response['status_code'] = $code;
        $response['body'] = null;
        $response['message'] = $message;
        return $response;
    }
}