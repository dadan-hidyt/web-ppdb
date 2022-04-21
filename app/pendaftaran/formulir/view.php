<?php 
//cek dulu sudah login apa belum jika belum arahkan ke halaman login
//dan arahkan lagi kesini
if (false === $isLogin) {
	header("location:".base_url('login?backTo='.base_url('pendaftaran/dashboard/folmulir')));
	die;
}
if (!$pendaftaran->cekStatusPendaftaran()) {
	header("location:".base_url("pendaftaran/dashboard/formulir"));
	exit;
}
$app->page = "dashboard";
$app->title = "formulir";
$app->content = Template::render("pendaftaran/formulir/template/view");
?>
