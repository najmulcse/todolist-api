<?php

require 'vendor/autoload.php';
use Dotenv\Dotenv;

use NAJMULCSE\TODOLIST\Config\DBConnection;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

$dbConnection = (new DBConnection())->getConnection();

