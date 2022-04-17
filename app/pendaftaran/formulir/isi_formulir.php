<?php 
//cek dulu sudah login apa tidak
if (false === $isLogin) {
	header("location:".base_url('login?backTo='.base_url('pendaftaran/dashboard/folmulir')));
	die;
}
//cek dulu apkah form sudah di isi
if ($pendaftaran->cekStatusPendaftaran()) {
	header("location:".base_url("pendaftaran/dashboard/formulir/view"));
	exit;
}
$app->page = "dashboard";
$app->title = "Formulir";
$app->content = Template::render("pendaftaran/formulir/template/isi_formulir");
?>