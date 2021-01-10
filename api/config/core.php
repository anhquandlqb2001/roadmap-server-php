<?php

// show error reporting
error_reporting(E_ALL);

// set default time-zone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// variable used for jwt
$key = "token massage";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // valid for 1 hour
//$issuer = "http://localhost/CodeOfaNinja/RestApiAuthLevel1/";
$issuer = "";
?>