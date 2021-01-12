<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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

$mapid = filter_input(INPUT_GET, "mapId");
$data = json_decode(file_get_contents("php://input"));


$connect = new Database();
$map = new UserModel($connect);


$jwtExtract = verifyUser($jwt);

// $value = $_GET["url"];

$valueChange = ($data->currentValue != true ? true : false);
// echo json_encode(
//     array(
//         "field" => $
//     )
// );

$response = $map->updateUserProgress($mapid, $jwtExtract["id"]->{'$oid'}, $data->fieldChange, $valueChange);

if($response)
{
    echo json_encode(array(
        "success" => true
    ));
}
else
{
    echo json_encode(array(
        "success" => false
    ));
}

?>