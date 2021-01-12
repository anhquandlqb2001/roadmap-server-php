<?php
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$roadName = $data->name;
$description = $data->description;
$title = $description->title;
$detail = $description->detail;
$introduction = $data->introduction;
$path = $data->documentation->path;
$map = $data->map;

$adminModel = new MapModel(new Database());
$response = $adminModel->addMap($jwt, $mapId, $roadName, $title, $detail, $path, $introduction, $map);

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