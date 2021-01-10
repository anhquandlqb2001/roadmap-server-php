<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$mapId = filter_input(INPUT_GET, "mapId");
$roadName = $data->name;
$description = $data->description;
$title = $description->title;
$detail = $description->detail;
$introduction = $data->introduction;
$path = $data->documentation->path;

$adminModel = new MapModel(new Database());
$response = $adminModel->updateMap($jwt, $mapId, $roadName, $title, $detail, $path, $introduction);

// // var_dump($response);
if($response)
{
    echo json_encode(array(
        "success" => true
    ));
    return;
}


echo json_encode(array(
    "success" => false
));


?>