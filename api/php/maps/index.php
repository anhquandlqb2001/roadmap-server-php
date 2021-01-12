<?php

$allowedOrigins = [
    "http://localhost:3000",
    "http://localhost:3001"
];

if (in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
// include_once("index.php");
// require_once(dirname(__FILE__) . '\..\..\..\vendor\autoload.php');
require_once "../../../vendor/autoload.php";
// require_once(dirname(__FILE__) . '\..\..\config\Database.php');
require_once "../../config/Database.php";
// require_once(dirname(__FILE__) . '\..\..\models\MapModel.php');
require_once "../../models/MapModel.php";
// required headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$action = filter_input(INPUT_GET, "action");
$db = new Database();
$mapModel = new MapModel($db);

switch($action)
{
    case "updateProgress":
    {
        include("updateProgress.php");
    break;
    }
    case "getMapList":
        {
            include("getMapList.php");
        break;
        }
    case "getMapInfo":
        {
            include("getMapInfo.php");
        break;
        }
    case "getMapContent":
        {
            include("getMapContent.php");
        break;
        }
    case "getDocumentPath":
        {
            include "getDocumentPath.php";
        break;
        }

}

?>