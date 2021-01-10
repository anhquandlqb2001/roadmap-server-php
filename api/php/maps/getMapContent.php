<?php
$mapId = filter_input(INPUT_GET, "mapId");
$userId = "5fba750ab64d2e9d61f14846";
// echo $mapId;

$mapInfo = $mapModel->getMapContent($mapId);

// var_dump($mapInfo);
// echo json_encode(array(
//     "success" => true,
//     "data" => array(
//         "map" => json_decode($mapInfo, true)
//     )
// ));

?>