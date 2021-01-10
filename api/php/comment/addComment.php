<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$jwt = \Firebase\JWT\JWT::decode($jwt, "WebCuoiKy", array("HS256"));
// var_dump($jwt);

$userId = $jwt->id->{'$oid'};
$userEmail = $jwt->email;
$mapId = filter_input(INPUT_GET, "mapId");
$text = $data->text;

$commentModel = new CommentModel(new Database());
$response = $commentModel->addComment($userId, $userEmail, $mapId, $text);

if($response)
{
    echo json_encode(array(
        "success" => true,
        "data" => array(
            "commentId" => $response["commentId"],
            "createdAt" => $response["createdAt"]
        )
    ));
}

?>