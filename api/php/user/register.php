<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");

// database connection will be here
// files needed to connect to database
include_once '../config/database.php';

$db = new Database();

$collection = $db->getConnection()->users;

$data = json_decode(file_get_contents("php://input"));

$errors = "";

function dataToSever($success, $error) {
    return json_encode(
        array(
            "success" => $success,
            "errors" => $error
        )
    );
}

//
$email = $data->email;
$password = $data->password;

if(empty($email) && empty($password)) {
    $errors = array (
        array (
            "error" => "email",
            "message" => "email chua duoc nhap"
        ),
        array (
            "error" => "password",
            "message" => "mat khau chua duoc nhap"
        )
    );
    echo dataToSever(false, $errors);
    return;
}

if (strpos($data->email, "@") === false) {
    $errors = array(
        array(
            "error" => "email",
            "message" => "Email phai dung dinh dang"
        )
    );

    echo dataToSever(false, $errors);
    return;

}

$email_prefix = substr($email, 0, strpos($email, "@"));

if(strlen($email_prefix) < 6) {
    $error = array(
        "error" => "email prefix",
        "message" => "email khong hop le",
        "error-description" => "ten email it hon 6 ki tu"
    );
    echo dataToSever(false, $error);
    return;
}


// check email is exist
$user = $collection->findOne(array(
    "email" => $data->email
));

if (!isset($user)) {
    $errors = array(
        array(
            "error" => "email",
            "message" => "Email da ton tai"
        )
    );
    echo dataToSever(false, $errors);
    return;
}


// after checking -> sanitize input
$email = str_replace("'", "''", [$email]);
$password = str_replace("'", "''", [$password]);

// encode password
$password = hash("md5", $password);

try {
    $insertOneResult = $collection->insertOne([
        'email' => $email,
        'password' => $password,
        'provider' => 'local',
    ]);
} catch (Exception $e) {
    $errors = $e;
}

// if insert has problem
if ($errors != null) {
    http_response_code(500);
    echo json_encode(
        array(
            "success" => false,
            "errors" => $errors
        )
    );
    return;
}

// insert successfully
http_response_code(200);
echo json_encode(
    array(
        "success" => true,
        "email" => $data->email
    )
);

?>