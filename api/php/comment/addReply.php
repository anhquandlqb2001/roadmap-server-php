<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

// $jwt = \Firebase\JWT\JWT::decode($jwt, "WebCuoiKy", array("HS256"));
// var_dump($jwt);

$headers = apache_request_headers();
$jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;
if (!$jwt) {
    echo json_encode([
        'success' => false
    ]);
    return;
}

$user = verifyUser($jwt);

$commentId = filter_input(INPUT_GET, "commentId");
$userId = $user["id"]->{'$oid'};
$userEmail = $user["email"];
$mapId = filter_input(INPUT_GET, "mapId");
$text = $data->text;

// var_dump($userId);

$commentModel = new CommentModel(new Database());
$response = $commentModel->addReply($mapId, $commentId, $userId, $userEmail, $text);

if($response)
{
    echo json_encode(array(
        "success" => true,
        "data" => [
            "replyId" => $response['replyId'],
            "createdAt" => $response["createdAt"]
        ]
    ));
    return;
}

echo json_encode(array(
    "success" => false
));
