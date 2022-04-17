<?php
require __DIR__.DS."ajaxFunction.php";
$response = array();
$auth = input_get("auth");
if (isset($auth)) {
    //auth type
    switch ($auth) {
        case "register":
        ajax_register();
        break;
        case "login":
        ajax_login();
        break;
    }
} else {  
    //send response code
    http_response_code(404);
    $response = array(
        "status"=>false,
        "code"=>403,
        "message"=>"Accsess forbidden"
    );
}
//if $response not empty print contain in $response as json
if (!empty($response)) {
    die(json_encode($response));
}

?>