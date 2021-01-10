<?php

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