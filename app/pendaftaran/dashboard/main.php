<?php
if ($isLogin == false){
    header("location:".base_url('login?backTo='.base_url('pendaftaran/dashboard')));
}
$app->page = "Dashboard";
$app->title = "Dashboard";
$app->content = Template::render("pendaftaran/dashboard/template/dashboard");