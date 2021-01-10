<?php

function dataToSever($success, $error) {
    return json_encode(
        array(
            "success" => $success,
            "errors" => [$error]
        )
    );
}

?>