<?php
$allowedOrigins = [
  "http://localhost:3000",
  "http://localhost:3001"
];

if (in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
  header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");


$mapId = filter_input(INPUT_GET, "mapId");
// $userId = "5fba750ab64d2e9d61f14846";
// echo $mapId;

$mapInfo = $mapModel->getMapDocumentation($mapId);
// var_dump($mapInfo->documentation);
// var_dump($mapInfo);
echo json_encode(array(
    "success" => true,
    "data" => array(
        "documentation" => $mapInfo->documentation
    )
));
