<?php 
/**
 * APLIKASI PPDB UNTUK
 * @author dadan hidayat <dadanhidyt@gmail.com>
 * VersiAPP: v 1.0
 * Jika ingin membantu mengembangkan aplikasi ini
 * Hubungi kontak saya 088223837165 atau email dadanhidyt@gmail.com
 */
define("ROOT_PATH", __DIR__);
#cek apakah file init.php ada
$init_file = sprintf("%s/%s",ROOT_PATH,"sys/init.php");
if (false === file_exists($init_file)) {
	die("Server Down");
}
#mendefinisikan route
$route = "main/MainPage";
if (isset($_GET['r']) && !empty($_GET['r'])) {
	$route = $_GET['r'];
}

#route secure
$route = trim($route);
$route = htmlspecialchars($route,ENT_QUOTES);
/**
 * Mendefinisikan file handler
 */
$app_handler = ROOT_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR.$route.'.php';
if (is_file($app_handler)) {
	include $app_handler;
} else {
	include ROOT_PATH.DIRECTORY_SEPARATOR."app/404/404.php";
}
$app->sidebar = Template::render("partial/sidebar");
$app->navbar = Template::render("partial/navbar");
Template::render('container', $app,false);
//close connection after load the site
$konek->close();
?>