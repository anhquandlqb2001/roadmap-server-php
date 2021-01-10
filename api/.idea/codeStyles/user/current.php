<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

include_once "../config/database.php";
include_once "../../../vendor/autoload.php";

$SECRET_KEY = "webcuoiky";

$data = json_decode(file_get_contents("php://input"));


?>