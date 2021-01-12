<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
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

$jwtExtract = verifyUser($jwt);

//$SECRET_KEY = "webcuoiky";
$db = new Database();
$userModel = new UserModel($db);

$data = json_decode(file_get_contents("php://input"));

$response = $userModel->logout($jwtExtract["id"]->{'$oid'});

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