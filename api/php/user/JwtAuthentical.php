<?php

define("SECRETKEY", "webcuoiky_secret_key");

function createJWT($userId, $email, $provider) {
    $header = json_encode(array(
        "alg" => "hs256",
        "type" => "jwt"
    ));

    $payload = json_encode(array(
        "userId" => $userId,
        "email" => $email,
        "provider" => $provider,
        "iss" => "serverhost"
//        "iat" => date("Y-m-d")
//        "exp" => date("Y-m-d", strtotime("+ 1 week"))
    ));

    // Encode Header to Base64Url String
    $base64UrlHeader = base64_encode($header);

    // Encode Payload to Base64Url String
    $base64UrlPayload = base64_encode($payload);

    $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, SECRETKEY, true);
    // Encode Signature to Base64Url String
    $base64UrlSignature = base64_encode($signature);

    $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

    return $jwt;
}

?>