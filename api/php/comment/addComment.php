<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$headers = apache_request_headers();
$jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;
if (!$jwt) {
    echo json_encode([
        'success' => false
    ]);
    return;
}
$user = verifyUser($jwt);
// var_dump($jwt);

$userId = $user["id"]->{'$oid'};
$userEmail = $user["email"];
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