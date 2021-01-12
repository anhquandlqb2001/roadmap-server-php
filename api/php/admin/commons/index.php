<?php
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

include("../../../models/CommonModel.php");
include("../../../config/Database.php");

$action = filter_input(INPUT_GET, "action");
$data = json_decode(file_get_contents("php://input"));
// echo md5("admin");
$headers = apache_request_headers();
$jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;

if(!$jwt)
{
    echo json_encode(array(
        "success" => false
    ));
    return;
}

if(!$data)
{
    echo json_encode(array(
        "success" => false
    ));
    return;
}

switch($action)
{
    case "updateHomePageContent":
        {
            include("updateHomePageContent.php");
        break;
        }
}
?>