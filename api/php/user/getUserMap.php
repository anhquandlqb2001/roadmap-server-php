<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");


//$SECRET_KEY = "webcuoiky";
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

// get jwt
$jsonData = json_decode(file_get_contents("php://input"));


// foreach ($headers as $header => $value) {
//     echo "$header: $value <br />\n";
// }

$mapId = filter_input(INPUT_GET, "mapId");
$jwtExist = $userModel->getUserList();
$isExistJwt = false;

$jwtExtract = \Firebase\JWT\JWT::decode($jwt, "WebCuoiKy", array("HS256"));

// var_dump($jwtExtract);
// get userId after extracted jwt
$userEmail = (string)$jwtExtract->email;

$userData = json_decode(json_encode($userModel->findUserByEmail($userEmail)));

if($userData->jwt == $jwt && $userData->jwt != "")
{
    $userMaps = $userData->maps;

    foreach($userData->maps as $map => $mapInfo)
    {
        // var_dump($mapInfo->mapId->{'$oid'});
        if($mapInfo->mapId->{'$oid'} == $mapId)
        {
            // echo gettype($mapInfo->map);
            echo json_encode(array(
                "success" => true,
                "data" => array(
                    "map" => json_decode($mapInfo->map, true),
                    "ownerMapId" => $mapInfo->_id->{'$oid'}
                )
            ));
            return;
        }
    }
}

echo json_encode(array(
    "success" => false
));

?>