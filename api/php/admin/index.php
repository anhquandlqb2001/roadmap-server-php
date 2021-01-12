<?php
include("../../config/Database.php");
include("../../models/AdminModel.php");
include("../../models/MapModel.php");
header("Access-Control-Allow-Origin: http://localhost:3001");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

// $headers = apache_request_headers();
// $jwt = isset($headers["Authorization"]) ? $headers["Authorization"] : NULL ;

// if(!$jwt)
// {
//     echo json_encode(array(
//         "success" => false
//     ));
//     return;
// }

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

        case "addMap":
            {
                include("addMap.php");
            break;
            }
}
