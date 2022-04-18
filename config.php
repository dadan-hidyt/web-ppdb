<?php 
define("APP_VERSION", "1.0");
//setingan untuk database
$conf = array();
$conf['host'] = "localhost";
$conf['user'] = "root";
$conf['pass'] = "";
$conf['dbname'] = "web_ppdb";
//site title
$conf['site_title'] = "SMK 2 HIDYT";
//site url
$conf['site_url'] = "";//biarkan kosong;
//configurasi utnuk koneksi ke smtp
$conf['smtp_config'] = array(
	"host"=>"mail.ardevs-group.my.id",
	"port"=>"465",
	"username"=>"suport@ardevs-group.my.id",
	"password"=> "dadanhidayat2003",
	"smtp_secure"=> "ssl"
);

?>