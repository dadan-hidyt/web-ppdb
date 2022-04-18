<?php defined("ROOT_PATH") or exit('Forbidden');
//memulai session
session_name("PPDB_SESS");
session_start();
define("BASEPATH", dirname(__DIR__));
define("DS", DIRECTORY_SEPARATOR);
//mengaktifkan dan menonaktifkan error
error_reporting(E_ALL);
//menyeting timezone
date_default_timezone_set("asia/jakarta");
ini_set("date.timezone", "asia/jakarta");
//menginclude file config
include BASEPATH.DS."config.php";
//menginclude file functions
include BASEPATH.DS."sys/functions.php";
//menginclude file template
include BASEPATH.DS."sys/includes/template.php";
//inlcude file definisi yang berisi konstanta
include BASEPATH.DS."sys/define/definisi.php";
//membuat class kosong
$app = new StdClass();
$global = new StdClass();

//membuat koneksi ke database
$host 	= isset($conf['host'])   ? $conf['host']	: null;
$user 	= isset($conf['user'])   ? $conf['user']	: null;
$pass	= isset($conf['pass'])   ? $conf['pass']	: null;
$dbname = isset($conf['dbname']) ? $conf['dbname']	: null;
try{
	$konek = @new mysqli($host,$user,$pass,$dbname);
	if ($konek->connect_errno) {
		throw new Exception('Koneksi gagal: '.$konek->connect_error);
	}
}catch (Exception $e) {
	die($e->getMessage());
}
#untuk menyimpan nilai config
$config = array2obj($conf);
/**
 * Untuk memntukan base url
 */
if (empty($config->site_url)){
	$config->base_url = base_url();
} else {
	$config->base_url = $config->site_url;
	unset($config->site_url);
}
//membuat properti csrf untuk di parse ke halaman
$global->csrf_token = csrf_token();
//panggil file autoload jika
//ada dependency yang di install dari composer
if(is_file(BASEPATH.DS."vendor/autoload.php")) {
	include BASEPATH.DS."vendor/autoload.php";
}
//autoloader
spl_autoload_register(function($class){
	$dir = array(
		"includes",
		"lib"
	);
	foreach ($dir as $directory) {
		$fullPath =  BASEPATH.DS."sys".DS.$directory.DS.$class.".php";
		if (file_exists($fullPath)) {
			require $fullPath;
		}
	}
});
//jika ada library yang di install dari composer
if (file_exists(BASEPATH.DS."vendor/autoload.php")) {
	require BASEPATH.DS."vendor/autoload.php";
}
$userinstance = User::getInstance();
$isLogin = false;
if (false !== $userinstance->isLogin()){
	$isLogin = true;
	//mendapatkan user info jika sudah login
	$me = $userinstance->getUserInfo();
	//membuat instance Pendaftaran
	$pendaftaran = Pendaftaran::instance();
	$pendaftaran->setKodePendaftaran($me['kode_pendaftaran']);
	$photo = null;
	if ($pendaftaran->cekStatusPendaftaran()) {
		$photo = $pendaftaran->getFormulirPendaftaran()['photo_calon_siswa'];
	}
	$me['avatar'] = (!empty($photo) && file_exists(BASEPATH.DS.$photo)) ? $photo : "media/avatar/default.png";
	$global->me = $me;
}
?>