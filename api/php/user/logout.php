<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");


//$SECRET_KEY = "webcuoiky";
$db = new Database();
$userModel = new UserModel($db);

$data = json_decode(file_get_contents("php://input"));


$user = \Firebase\JWT\JWT::decode($data->jwt, "WebCuoiKy", array("HS256"));
// var_dump($user);
// get value inside an object
// var_dump((string)$user->id->{'$oid'});

$response = $userModel->logout($user->id->{'$oid'});

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