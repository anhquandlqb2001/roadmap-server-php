<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$headers = apache_request_headers();
$jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;

if(!$jwt)
{
    echo json_encode(array(
        "success" => false
    ));
    return;
}

$mapId = filter_input(INPUT_GET, "mapId");
$data = json_decode(file_get_contents("php://input"));
$jwtExtract = verifyUser($jwt);


$connect = new Database();
$userModel = new UserModel($connect);

// var_dump($jwtExtract["id"]->{'$oid'});

$response = $userModel->startMap($mapId, $jwtExtract["id"]->{'$oid'});

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