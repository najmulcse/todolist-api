<?php
/**
 * Created by PhpStorm.
 * User: Najmul
 * Date: 4/1/2020
 * Time: 6:17 AM
 */


require "../bootstrap.php";
use NAJMULCSE\TODOLIST\APIs\TodoListController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /person
// everything else results in a 404 Not Found

if ($uri[1] !== 'api') {
    header("HTTP/1.1 404 Not Found");
    exit();
}
if ($uri[2] !== 'v1') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if ($uri[3] !== 'todo') {
    header("HTTP/1.1 404 Not Found");
    exit();
}
// the user id is, of course, optional and must be a number:

    $apiName = "";
if (isset($uri[4]) && $uri[4] != '') {
    $apiName = $uri[4];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:

$controller = new TodoListController($dbConnection, $requestMethod, $apiName);

$controller->processRequest();