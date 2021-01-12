<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$db = new Database();
$userModel = new UserModel($db);

$headers = apache_request_headers();
$jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;

if(!$jwt)
{
    echo json_encode(array(
        "success" => false
    ));
    return;
}

$jwtExtract = verifyUser($jwt);
// var_dump($jwtExtract);

$userEmail = $jwtExtract["email"];

// find user
$userData = json_decode(json_encode($userModel->findUserByEmail($userEmail)));

$arrId = [];
foreach($userData->maps as $user => $data)
{
    $arrMapId = array(
        "mapHasStarted" => $data->mapId->{'$oid'},
        "ownerMapId" => $data->_id->{'$oid'} 
    );
    $arrId[] = $arrMapId;
}

if ($userData->jwt == $jwt && $userData->jwt != "")
{
    echo json_encode(array(
        "success" => true,
        "user" => array(
            "id" => $jwtExtract["id"],
            "email" => $jwtExtract["email"],
            "provider" => $jwtExtract["provider"]
        ),
        "map" => $arrId
    ));
    return;
}

echo json_decode(array(
    "success" => false
));
