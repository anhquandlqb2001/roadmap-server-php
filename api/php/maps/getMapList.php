<?php

$mapList = $mapModel->findListMap();
// echo gettype($mapList);
echo json_encode(array(
    "success" => true,
    "maps" => $mapList
));

?>