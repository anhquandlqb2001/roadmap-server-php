<?php

include("../../models/CommentModel.php");
include("../../models/MapModel.php");
include("../../config/Database.php");

$headers = apache_request_headers();
$jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;

if(!$jwt)
{
    echo json_encode(array(
        "success" => false
    ));
    return;
}

$action = filter_input(INPUT_GET, "action");
$data = json_decode(file_get_contents("php://input"));

switch($action)
{
    case "addComment":
        {
            include("addComment.php");
        break;
        }
}


?>