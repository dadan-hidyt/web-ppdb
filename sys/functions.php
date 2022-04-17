<?php
function csrf_token () {
    //cek dulu ada tidak session csrf
    if (!isset($_SESSION['csrf'])) {
        //jika tidak buat session csrf
        $random = md5(uniqid().bin2hex(random_bytes(32)).microtime());
        $_SESSION['csrf'] = $random;
    }
    return $_SESSION['csrf'];
}
function verifikasi_token ($token = null) {
    if (empty($token)) {
        return false;
    }
    if (isset($_SESSION['csrf']) && ($_SESSION['csrf'] === $token)) {
        return true;
    } else {
        return false;
    }
}
/**
 * Fungsi untuk membuat url otomatis
 */
function base_url($pathToFile = false) {
    $http = true;
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']==="on")) {
        $http = false;
    }
    $scheme = "http://";
    if (!$http){
        $scheme = "https://";
    }
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "[::1]/";
    $path = str_replace(basename($_SERVER['SCRIPT_NAME']),'',$_SERVER['SCRIPT_NAME']);
    $fullUrl = $scheme.$host.$path;
    if ($pathToFile) {
        return $fullUrl.$pathToFile;
    } else {
        return $fullUrl;
    }
}
//fungsi untuk menkonvert bytes ke 
//K KB MB GB TB ...
function bytes2SatuanPenyimpanan($bytes) {
    $satuan = array("B","KB","MB","GB","TB","PB");
    $i = 0;
    while ($bytes > 1024) {
        $bytes = round($bytes/1024,2);
        $i++;
    }
    return sprintf('%d %s', $bytes,$satuan[$i]);
}
//fungsi untuk mendapat media file dari 
//folder media
function getMedia($pathToFile = null) {
    global $config;
    if(empty($pathToFile)) return false;
    return base_url("media/".$pathToFile);

}

function assets_css($pathToCssFile = null) {
    if (empty($pathToCssFile)) return false;
    return '<link rel="stylesheet" href="'.base_url()."assets/".$pathToCssFile.'" />'.PHP_EOL;
}

function assets_js($pathToJsFile) {
    if (!empty($pathToJsFile)) {
        return '<script src="'.base_url("assets/{$pathToJsFile}").'"></script>'.PHP_EOL;
    }
}
function tanggal_indo () {

}
function field_kosong($data = "", $replace = "-") {
    if (empty($data)) {
        return $replace;
    } else {
        return $data;
    }
}
function is_ajax() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"){
            return true;
        }
    }
    return false;
}
function input_get($key = null, $secure = false) {
    if (!empty($key)) {
        if (isset($_GET[$key])) {
            if ($secure) {
                $return = $GLOBALS['konek']->escape_string($_GET[$key]);
                $return = htmlspecialchars($_GET[$key],ENT_QUOTES);
                $return = trim($_GET[$key]);
                return $return;
            } else {
                return $_GET[$key];
            }
        } else {
            return false;
        }
    } else {
        return $_GET;
    }
}
/**
 * Fungsi untuk mendapatkan data dari method post
 * @param string $Key
 * @param boolean $secure
 */
function input_post($key = null, $secure = false) {
    if (!empty($key)) {
        if (isset($_POST[$key])) {
           if ($secure) {
            $return = $GLOBALS['konek']->escape_string($_POST[$key]);
            $return = htmlspecialchars($_POST[$key],ENT_QUOTES);
            $return = trim($_POST[$key]);
            return $return;
        } else {
            return $_POST[$key];
        }
    } else {
        return false;
    }
} else {
    return $_POST;
}
}
//mengkonversi array ke object
function array2obj($array = array()) {
    //$array = array('nama'=>'dadan');
    $obj = new StdClass();
    if (!empty($array)) {
        foreach ($array as $key => $value) {
            $obj->$key = $value;
        }
    }
    return $obj;
}




?>
