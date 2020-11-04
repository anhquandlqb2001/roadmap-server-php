<?php
$action = filter_input(INPUT_GET, "action");

switch($action) {
    case "login_local" :
        include_once "./login_local.php";
        break;

    case 'register':
        include_once "./register.php";
        break;
}
?>