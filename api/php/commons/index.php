<?php
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

include("../../models/CommonModel.php");
include("../../config/Database.php");
$action = filter_input(INPUT_GET, "action");
// $data = json_decode(file_get_contents("php://input"));

switch($action)
{
    case "getHomePageContent":
    {
        $commonModel =  new CommontModel(new Database());
        echo json_encode(array(
           "success" => true,
           "data" => json_decode(json_encode($commonModel->getHomePageContent()), true)
        ));
    }

}


?>