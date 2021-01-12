<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

//$SECRET_KEY = "webcuoiky";
function dataToSever($success, $error) {
    return json_encode(
        array(
            "success" => $success,
            "errors" => [$error]
        )
    );
}

// var_dump("abc");

$db = new Database();
$userModel = new UserModel($db); 

$data = json_decode(file_get_contents("php://input"));
//echo json_encode($return_value);


// sanitize
$email = htmlspecialchars(strip_tags($data->email));
$password = htmlspecialchars(strip_tags($data->password));

//$email = str_replace("'","''", $email);
//$password = str_replace("'","''", $password);


if(strpos($email, '@gmail.com') === false) {
    $error = [
        "name" => "email",
        "error" => "email khong dung dinh dang"
    ];
    echo dataToSever(false, $error);
    return;
}
//$email_prefix = substr($email, 0, strpos($email, "@"));
//
//if(strlen($email_prefix) < 6) {
//    $error = array(
//        "error" => "email prefix",
//        "message" => "email khong hop le",
//        "error-description" => "ten email it hon 6 ki tu"
//    );
//    echo dataToSever(false, $error);
//    return;
//}

if(strlen($password) < 3) {
    $error = array(
        "name" => "password",
        "error" => "mat khau qua ngan"
    );
    echo dataToSever(false, $error);
    return;
}

$user = $userModel->findUserByEmail($email);

if(!empty($user)) {
    $error = array (
        "name" => "email",
        "error" => "email da ton tai"
    );
    echo dataToSever(false, $error);
    return;
}

// checking password is correct (hashed)
$password = hash("md5", $password);

$response = $userModel->register($email, $password);

if($response)
{
    echo json_encode(array(
        "success" => true
    ));
}
else
{
    echo json_encode(array(
        "success" => false
    ));
}

?>