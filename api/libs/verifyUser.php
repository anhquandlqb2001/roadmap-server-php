<?php

// include_once("./Database.php");
require_once(dirname(__FILE__) . '/../config/Database.php');
require_once(dirname(__FILE__) . '/../models/UserModel.php');
require_once(dirname(__FILE__) . '/../../vendor/autoload.php');


function verifyUser($jwt)
{

    $db = new Database();
    $userModel = new UserModel($db);

    $jwtExtract = \Firebase\JWT\JWT::decode($jwt, "WebCuoiKy", array("HS256"));
    // var_dump($jwtExtract);

    $userEmail = $jwtExtract->email;

    // find user
    $userData = json_decode(json_encode($userModel->findUserByEmail($userEmail)));

    if ($userData->jwt == $jwt && $userData->jwt != "") {
        return array(
                "id" => $jwtExtract->id,
                "email" => $jwtExtract->email,
                "provider" => $jwtExtract->provider
        );
    }

    return false;
}
