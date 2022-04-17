<?php 
if ($isLogin == true) {
    //jika sudah login arahkan ke halaman dashboard
    header("location:".base_url('pendaftaran/dashboard'));
    die;
}
/** cek apakah request nya dari xhr/ajax atau bukan */
if (is_ajax()) {
    header("content-type:application/json");
    //cek csrf token
    $csrf = isset($_POST['protek']) ? $_POST['protek'] : null;
    if (!verifikasi_token($csrf)) {
        $response = array(
            "status"=>403,
            "message"=>"Token csrf tidak valid"
        );
        die(json_encode($response));
    }
    //validasi login
    $nisnOrEmail = $konek->escape_string($_POST['nisn-email']);
    $password    = $konek->escape_string($_POST['password']);
    $remember = isset($_POST['remember']) && !empty($_POST['remember']) ? 'on' : 'off';
    if($nisnOrEmail == "" && $password == ""){
        $response['mssg'] = "Email/nisn dan password tidak boleh kosong";
        $response['login'] = false;
    } elseif ($nisnOrEmail == "") {
        $response['mssg'] = "Nisn atau emal tidak boleh kosong";
        $response['login'] = false;
    } elseif($password == "") {
        $response['mssg'] = "Password tidak boleh kosong";
        $response['login'] = false;
    } else {
        //create instance for sigin
        $sigIn = new User();
        $sigIn->setNisn($nisnOrEmail);
        $sigIn->setPassword($password);
        if ($returned = $sigIn->login()) {
            if (false !== $returned['successCek']){
                //create session jika sudah login
                $sigIn->buatSessionLogin($returned['returnData']->user_id, $remember);
                $response['mssg'] = "Login berhasil! Please wait";
                $response['login'] = true;
                $backUrl = isset($_GET['backTo']) ? $_GET['backTo'] : false;
                $response['backurl'] = $backUrl;
            } else {
                $response['mssg'] = $returned['error'];
                $response['login'] = false;
            }
        }
    }
    $sendResponse = array(
        "status"=>200,
        "responData"=>$response
    );
    die(json_encode($sendResponse));
    exit;
}
$app->title = "login";
$app->content = Template::render("auth/template/login");
$app->css_page = assets_css('vendor/css/pages/page-auth.css');
?>