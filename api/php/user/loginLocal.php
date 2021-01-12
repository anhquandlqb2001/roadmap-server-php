<?php
// header("Access-Control-Allow-Origin: *");
$allowedOrigins = [
    "http://localhost:3000",
    "http://localhost:3001",
    "http://192.168.43.45:3000"
  ];
  
  if (in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
  }
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

//$SECRET_KEY = "webcuoiky";
$db = new Database();
$userModel = new UserModel($db);

$data = json_decode(file_get_contents("php://input"));

function dataToSever($success, $error) {
    return json_encode(
        array(
            "success" => $success,
            "errors" => [$error]
        )
    );
}

// sanitize
$email = htmlspecialchars(strip_tags($data->email));
$password = htmlspecialchars(strip_tags($data->password));

if(strpos($email, '@gmail.com') === false) {
    $error = array(
        "name" => "email",
        "error" => "email khong dung dinh dang"
    );
    echo dataToSever(false, $error);
    return;
}


if(strlen($password) < 3) {
    $error = array(
        "name" => "password",
        "error" => "mat khau qua ngan"
    );
    echo dataToSever(false, $error);
    return;
}

$user = $userModel->findUserByEmail($email);

if(empty($user)) {
    $error = array (
            "name" => "email",
            "error" => "email khong ton tai"
    );
    echo dataToSever(false, $error);
    return;
}

// checking password is correct (hashed)
$password = hash("md5", $password);

if($user->password !== $password) {
    $error = array (
        array(
            "name" => "password",
            "error" => "sai mat khau"
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