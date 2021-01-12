<?php

include("../../models/CommentModel.php");
include("../../models/MapModel.php");
include("../../config/Database.php");
include "../../libs/verifyUser.php";

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: HEAD,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$action = filter_input(INPUT_GET, "action");
$data = json_decode(file_get_contents("php://input"));

switch($action)
{
    case "addComment":
        {
            include("addComment.php");
        break;
        }

        case "getListComment":
            {
                include "getListComment.php";
                break;
            }
        case "addReply":
            {
                include "addReply.php";
                break;
            }
            case "getListReply":
                {
                    include "getListReply.php";
                    break;
                }
}


?>