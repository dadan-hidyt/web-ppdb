<?php 
define("APP_VERSION", "1.0");
/**
 * Configuration database
 * */
$conf = array();
$conf['host'] = "localhost";
$conf['user'] = "root";
$conf['pass'] = "";
$conf['dbname'] = "web_ppdb";
/**
 * informasi website
 * */
$conf['site_title'] = "SMP 2 HIDYT";
$conf['site_url'] = "";
/**
 * configuration stmp
 * Untuk mengirim email
 * */
$conf['smtp_config'] = array(
	"host"=>"mail.ardevs-group.my.id",
	"port"=>"465",
	"username"=>"suport@ardevs-group.my.id",
	"name"=>"Suport System Ardevs",
	"password"=> "dadanhidayat2003",
	"smtp_secure"=> "ssl"
);

?>