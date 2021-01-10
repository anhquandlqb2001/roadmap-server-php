<?php


include("../../models/CommonModel.php");
include("../../config/Database.php");
$action = filter_input(INPUT_GET, "action");
// $data = json_decode(file_get_contents("php://input"));

switch($action)
{
    case "getHomePageContent":
    {
        $commonModel =  new CommontModel(new Database());
        // var_dump(json_encode($commonModel->getHomePageContent()));

        echo json_encode(array(
           "success" => true,
           "data" => json_decode(json_encode($commonModel->getHomePageContent()), true)
        ));
    }

}


?>