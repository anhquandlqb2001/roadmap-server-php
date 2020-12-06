<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
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

// echo json_encode(
//     array(
//         "data" => $collection
//     )
// );
// return;

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

if (strpos($data->email, "@") === false) {
    $errors = array(
        array(
            "name" => "email",
            "error" => "Email phai dung dinh dang"
        )
    );

    echo dataToSever(false, $errors);
    return;

}

$email_prefix = substr($email, 0, strpos($email, "@"));

if(strlen($email_prefix) < 6) {
    $error = array(
        array(
            "name" => "email",
            "error" => "email khong hop le",
        // "error-description" => "ten email it hon 6 ki tu"
        )
    );
    echo dataToSever(false, $error);
    return;
}


// check email is exist
$user = $collection->findOne(array(
    "email" => $email
));

if (isset($user)) {
    $errors = array(
        array(
            "name" => "email",
            "error" => "Email da ton tai"
        )
    );
    echo dataToSever(false, $errors);
    return;
}


// after checking -> sanitize input
//$email = str_replace("'", "''", [$email]);
//$password = str_replace("'", "''", [$password]);

// encode password
$password = hash("md5", $password);

try {
    $insertOneResult = $collection->insertOne(array(
        'email' => $email,
        'password' => $password,
        'provider' => 'LOCAL',
    ));
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
        "message" => "Dang ky tai khoan thanh cong"
    )
);
