<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");


$heading = $data->heading;
$detail = trim($data->detail);

$commonModel = new CommontModel(new Database());
$response = $commonModel->updateContent($heading, $detail);

if($response)
{
    echo json_encode(array(
        "success" => true
    ));
    return;
}

echo json_encode(array(
    "success" => false
));

?>