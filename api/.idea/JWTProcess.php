<?php
use Firebase\JWT\JWT;

define("SECRET_KEY", base64_encode("webcuoiky"));

function sendJWT($typ, $alg, $payload) {
    $header = array(
        "typ" => $typ,
        "alg" => $alg
    );

    $pl = array(
        "email" => $payload["email"],
        "password" => $payload["password"]
    );

    $data = base64_encode($header) + "." + base64_encode($pl) + "." + SECRET_KEY;


    return $data;
}

?>