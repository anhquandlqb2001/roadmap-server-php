<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$mapid = filter_input(INPUT_GET, "mapId");
$data = json_decode(file_get_contents("php://input"));


$connect = new Database();
$map = new UserModel($connect);

// $value = $_GET["url"];

$valueChange = ($data->currentValue != true ? true : false);
// echo json_encode(
//     array(
//         "field" => $
//     )
// );

$response = $map->updateUserProgress($mapid, "5fba750ab64d2e9d61f14846", $data->fieldChange, $valueChange);

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