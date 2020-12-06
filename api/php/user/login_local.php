<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

include_once "../config/database.php";
include_once "../../../vendor/autoload.php";
include_once "../../../api/php/user/JwtAuthentical.php";

//$SECRET_KEY = "webcuoiky";

function dataToSever($success, $error)
{
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

//$email = str_replace("'","''", $email);
//$password = str_replace("'","''", $password);


if(empty($email) || empty($password)) {
    $errors = array (
        array (
            "name" => "email",
            "error" => "email chua duoc nhap"
        ),
        array (
            "name" => "password",
            "error" => "mat khau chua duoc nhap"
        )
    );
    echo dataToSever(false, $errors);
    return;
}

if (strpos($email, '@gmail.com') === false) {
    $error = array(
        array(
            "name" => "email",
            "error" => "email khong dung dinh dang"
        )
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

if (strlen($password) < 3) {
    $error = array(
        array(
            "name" => "password",
            "error" => "mat khau qua ngan"
        )
    );
    echo dataToSever(false, $error);
    return;
}

$user = $collection->findOne(array(
    "email" => $email
));

if (empty($user)) {
    $error = array(
        array(
            "name" => "email",
            "error" => "email khong ton tai"
        )
    );
    echo dataToSever(false, $error);
    return;
}

// checking password is correct (hashed)
$password = hash("md5", $password);

if ($user->password !== $password) {
    $error = array(
        array(
            "name" => "password",
            "error" => "sai mat khau"
        )
    );
    echo dataToSever(false, $error);
    return;
}

$jwt = createJWT($user->_id, $user->email, $user->provider);

//$jwt = \Firebase\JWT\JWT::encode($payload, $SECRET_KEY);

$updateResult = $collection->findOneAndUpdate(
    ['_id' => $user->_id],
    ['$set' => ["jwt" => $jwt]]
);

echo json_encode(
    array(
        "success" => true,
        "data" => array(
            "email" => $user->email,
            "provider" => $user->provider,
            "jwt" => $jwt
        )
    )
);
