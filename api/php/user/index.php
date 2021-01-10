<?php

// include_once("index.php");
require_once(dirname(__FILE__) . '\..\..\..\vendor\autoload.php');
require_once(dirname(__FILE__) . '\..\..\config\Database.php');
require_once(dirname(__FILE__) . '\..\..\models\UserModel.php');
require_once(dirname(__FILE__) . '\..\..\libs\dataToServer.php');
// required headers

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

switch($action){
    case "loginLocal":
        {
            include("./loginLocal.php");
        break;
        }
    case "register":
        {
            include("register.php");
        break;
        }
    case "logout":
        {
            include("logout.php");
            header("Location:");
        break;
        }
    case "getMapList":
        {
            include("getMapList.php");
        break; 
        }
    case "getUserMap":
        {
            include("getUserMap.php");
        break;
        }
    case "updateUserProgress":
        {
            include("updateUserProgress.php");
        }
    case "startMap":
        {
            include("startMap.php");
        }
}

?>