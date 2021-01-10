<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");


//$SECRET_KEY = "webcuoiky";
$db = new Database();
$userModel = new UserModel($db);

$data = json_decode(file_get_contents("php://input"));


// sanitize
$email = htmlspecialchars(strip_tags($data->email));
$password = htmlspecialchars(strip_tags($data->password));

if(strpos($email, '@gmail.com') === false) {
    $error = array(
        "error" => "email",
        "message" => "email khong dung dinh dang"
    );
    echo dataToSever(false, $error);
    return;
}


if(strlen($password) < 3) {
    $error = array(
        "error" => "password",
        "message" => "mat khau qua ngan"
    );
    echo dataToSever(false, $error);
    return;
}

$user = $userModel->findUserByEmail($email);

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

// checking password is correct (hashed)
$password = hash("md5", $password);

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

// var_dump($user->_id[]);

$header = ["alg" => "HS256", "typ" => "JWT"];
$payload = array("id" => $user->_id, "email" => $user->email, "password" => $user->provider);

// $data = base64_encode($header) + "." + base64_decode($payload);
// echo $data;
$jwt = \Firebase\JWT\JWT::encode($payload, "WebCuoiKy", "HS256", NULL, $header);
$updateResult = $userModel->login($email, $jwt);

echo json_encode(
    array(
        "success" => true,
        "jwt" => $jwt 
    )
);

?>