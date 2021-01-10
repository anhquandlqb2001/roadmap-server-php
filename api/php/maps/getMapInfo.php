<?php
$mapId = filter_input(INPUT_GET, "mapId");
// echo $mapId;
$mapInfo = $mapModel->getMapInfo($mapId);

echo json_encode(array(
    "success" => true,
    "data" => $mapInfo
));

?>