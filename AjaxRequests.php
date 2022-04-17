<?php
header("Content-Type:application/json");
define("ROOT_PATH", __DIR__);
include ROOT_PATH."/sys/init.php";
/**
 * xhr/register/handler.php
 */
$xhrHandler = "";
if (isset($_GET['xhrh'])) {
    $xhrHandler = $_GET['xhrh'];
} else {
    die(json_encode(array(
        "success"=>false,
        "code"=>403,
        "message" => "Akses tidak di ijinkan!" 
    ),JSON_PRETTY_PRINT));
}
//cek csrf
$csrf = (isset($_GET['token_csrf']) OR isset($_POST['token_csrf'])) ? (empty($_POST['token_csrf']) ? $_GET['token_csrf'] : $_POST['token_csrf']) : null;
if (empty($csrf)) {
    die(json_encode(array(
        "success"=>false,
        "code"=>400,
        "message" => "Bad Request! Your request without csrf token,Please clear your cookie browser and try again or" 
    ),JSON_PRETTY_PRINT)); 
}
elseif (false == verifikasi_token($csrf)) {
    die(json_encode(array(
        "success"=>false,
        "code"=>400,
        "message" => "Bad Request! csrf not valid please try again" 
    ),JSON_PRETTY_PRINT)); 
}

$handlerAjaxFile = sprintf("%s/%s/%s/ajax.php",ROOT_PATH,"xhr",$xhrHandler);
if (false == file_exists($handlerAjaxFile)) {
    die(json_encode(array(
        "success"=>false,
        "code"=>500,
        "message" => "Internal server error" 
    ),JSON_PRETTY_PRINT)); 
}
require $handlerAjaxFile;
$konek->close();