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

$mapList = $mapModel->findListMap();
// echo gettype($mapList);
echo json_encode(array(
    "success" => true,
    "maps" => $mapList
));

?>