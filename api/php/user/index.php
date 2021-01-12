<?php
$allowedOrigins = [
    "http://localhost:3000",
    "http://localhost:3001",
    "http://192.168.43.45:3000"
];

if (in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

// include_once("index.php");
require_once(dirname(__FILE__) . '/../../../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../../config/Database.php');
require_once(dirname(__FILE__) . '/../../models/UserModel.php');
require_once "../../libs/verifyUser.php";
// required headers

$action = filter_input(INPUT_GET, "action");

switch ($action) {
    case "loginLocal": {
            include("./loginLocal.php");
            break;
        }
    case "register": {
            include("register.php");
            break;
        }
    case "logout": {
            include("logout.php");
            break;
        }
    case "getMapList": {
            include("getMapList.php");
            break;
        }
    case "getUserMap": {
            include("getUserMap.php");
            break;
        }
    case "updateUserProgress": {
            include("updateUserProgress.php");
            break;
        }
    case "startMap": {
            include("startMap.php");
            break;
        }
    case "current": {
            include("current.php");
            break;
        }
}
