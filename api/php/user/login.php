<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Controll-Allow-Origin, Authorization, X-Requested-With");



//  vi du push code moi len github

// database connection will be here
// files needed to connect to database
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "config/create_user.php");
include_once 'C:\xampp\htdocs\rest-api-authentication\api\objects\user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// check email is existed
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product properties values
$user->email = $data->email;
$email_exists = $user->emailExist();

// generate json web token (JWT)
include_once 'api/config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// password_verify() function
// check if email is exists and if password is correct
if($email_exists && password_verify($data->password, $user->password)){

    $token = array(
        "iat" => $issued_at,
        "exp" => $expiration_time,
        "iss" => $issuer,
        "data" => array (
            "firstName" => $user->firstName,
            "lastName" => $user->lastName,
            "email" => $user->email
        )
    );

    // set response code
    http_response_code(200);

    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "message" => "successfully login.",
            "jwt" => $jwt
        )
    );
}
// login failed
else{
    // set response code
    http_response_code(401);

    // tell the user login failed
    echo json_encode(array("message" => "login failed."));
}

?>