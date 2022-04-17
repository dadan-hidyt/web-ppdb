<?php 
if ($isLogin == true) {
    //jika sudah login arahkan ke halaman dashboard
    header("location:".base_url('pendaftaran/dashboard'));
    die;
}
$app->title = "register";
$app->css_page = assets_css('vendor/css/pages/page-auth.css');
$app->content = Template::render("auth/template/register");

?>