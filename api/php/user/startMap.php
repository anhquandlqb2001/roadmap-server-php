<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$mapid = filter_input(INPUT_GET, "mapId");
$data = json_decode(file_get_contents("php://input"));

$connect = new Database();
$userModel = new UserModel($connect);

$response = $userModel->startMap("5fb12e6e581d3b79b1362e13", "5ff98aaf3c0a00009d004198");

if($response)
{
    echo json_encode(
        array(
        "success" => true
    ));
}
else
{
    echo json_encode(
        array(
        "success" => false
    ));
}

?>