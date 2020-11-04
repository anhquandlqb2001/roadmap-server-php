<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

include_once "../config/database.php";
include_once "../../../vendor/autoload.php";

$SECRET_KEY = "asdkjasnd";

function dataToSever($success, $error) {
    return json_encode(
        array(
            "success" => $success,
            "errors" => $error
        )
    );
}

$db = new Database();
$collection = $db->getConnection()->users;

$data = json_decode(file_get_contents("php://input"));
//echo json_encode($return_value);


// sanitize
$email = htmlspecialchars(strip_tags($data->email));
$password = htmlspecialchars(strip_tags($data->password));

$email = str_replace("'","''", $email);
$password = str_replace("'","''", $password);


if(strpos($email, '@gmail.com') === false) {
    $error = array(
        "error" => "email",
        "message" => "email khong dung dinh dang"
    );
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

if(strlen($password) < 8) {
    $error = array(
        "error" => "password",
        "message" => "mat khau khong du 8 ki tu"
    );
    echo dataToSever(false, $error);
    return;
}

$user = $collection->findOne(array(
    "email" => $email
));

if(empty($user)) {
    $error = array (
        array(
            "error" => "email",
            "message" => "email khong ton tai"
        )
    );
    echo dataToSever(false, $error);
    return;
}

if($user->password !== $password) {
    $error = array (
        array(
            "error" => "password",
            "message" => "sai mat khau"
        )
    );
    echo dataToSever(false, $error);
    return;
}

$header = array(
    "alg" => "HS256",
    "typ" => "JWT"
);

$payload = array(
    "email" => $user->email,
    "password" => $user->password
);

$jwt = \Firebase\JWT\JWT::encode($payload, $SECRET_KEY);

echo json_encode(
    array(
        "success" => true,
        "email" => $user->email,
        "jwt" => $jwt
    )
);

?>