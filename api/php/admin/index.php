<?php
include("../../config/Database.php");
include("../../models/AdminModel.php");
include("../../models/MapModel.php");

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
    case "login":
        {
            $email = $data->email;
            $password = $data->password;

            $adminModel = new AdminModel(new Database());
            $response = $adminModel->login($email, $password);

            if($response)
            {
                echo json_encode(array(
                    "success" => true,
                    "jwt" => $response
                ));
                return;
            }

            echo json_encode(array(
                "success" => false
            ));
        break;
        }

    case "updateMap":
        {
            include("updateMap.php");
        break;
        }
}
?>