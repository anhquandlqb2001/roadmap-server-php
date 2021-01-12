<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");


$commentModel = new CommentModel(new Database());
$mapModel = new MapModel(new Database());

$mapId = filter_input(INPUT_GET, "mapId");
$page = filter_input(INPUT_GET, "page");

$response = $commentModel->getListComment($mapId, $page);

if($response)
{
    echo json_encode(array(
        "success" => true,
        "comments" => $response["comments"],
        "hasMore" => $response["hasMore"]
    ));
}
else
{
    
}
?>