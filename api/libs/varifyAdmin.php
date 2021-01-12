<?php

// include_once("./Database.php");
require_once(dirname(__FILE__) . '/../config/Database.php');
require_once(dirname(__FILE__) . '/../models/AdminModel.php');
require_once(dirname(__FILE__) . '/../../vendor/autoload.php');


function verifyAdmin($jwt)
{

  $db = new Database();
  $adminModel = new AdminModel($db);

  $jwtExtract = \Firebase\JWT\JWT::decode($jwt, "WebCuoiKy", array("HS256"));
  // var_dump($jwtExtract);

  $userEmail = $jwtExtract->email;

  // find user
  $userData = json_decode(json_encode($adminModel->findUserByEmail($userEmail)));

  if ($userData->jwt == $jwt && $userData->jwt != "") {
    return array(
      "id" => $jwtExtract->id,
      "email" => $jwtExtract->email,
      "provider" => $jwtExtract->provider
    );
  }

  return false;
}
