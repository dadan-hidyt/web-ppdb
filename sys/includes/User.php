<?php
/**
 * class user
 * @author dadanhidayat
 * @package Includes
 */
class User {
    private $db;
    public $userinfo;
    public $nisn;
    public $email;
    public $nama_lengkap;
    public static $instance;
    public function __construct() {
        if (null == $this->db) {
            $this->db = $GLOBALS['konek'];
        }
    }
    public static function getInstance(){
        if (self::$instance == null) {
            self::$instance = new User();
        }
        return self::$instance;
    }
    /**
     * method utuk mengecek apakah nisn sudah ada di database apa belum
     * @return boolean
     * */
    public function nisnExists() {
        if (!empty($this->nisn)) {
            if ($this->db->query("SELECT nisn FROM tbl_users WHERE nisn='".$this->nisn."'")->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * Method seter untuk mengeset mengecek apakah email ada di database atau belum
     * @param string $email
     * @return void
     * */
    public function emailExists(){
        if (!empty($this->email)) {
            if ($this->db->query("SELECT email FROM tbl_users WHERE email='".$this->email."'")->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * Method seter untuk mengeset nisn
     * @param string $nisn
     * @return void
     * */
    public function setNisn($nisn = null) {
        $this->nisn = $nisn;
    }
    /**
     * Method seter untuk mengeset nama_lengkap
     * @param string $nama_lengkap
     * @return void
     * */
    public function setNamaLengkap($nama_lengkap = null){
        $this->nama_lengkap = $nama_lengkap;
    }
    /**
     * Method seter untuk mengeset email
     * @param string $email
     * @return void
     * */
    public function setEmail($email = null) {
        $this->email = $email;
    }
    /**
     * untuk menset password user
     * @return bool
     */
    public function setPassword($password = null) {
        $this->password = $password;
    }
    /**
     * METHOD untuk register akun
     * @return boolean
     */
    public function register() {
        $full_name = $this->nama_lengkap;
        $nisn = $this->nisn;
        $email = $this->email;
        $password = password_hash($this->password,PASSWORD_DEFAULT);
        $avatar = "media/avatar/default.png";
        $kode_pendaftaran = strtoupper(Pendaftaran::instance()->generateKodePendaftaran(7));
        $insertSQL = "INSERT INTO tbl_users (full_name,nisn,email,password,kode_pendaftaran,avatar)
        VALUES ('$full_name','$nisn','$email','$password','$kode_pendaftaran','$avatar')";
        if ($this->db->query($insertSQL)){
            return true;
        } else {
            return false;
        }
    }
    /**
     * Untuk melakukan login
     * @return mixed
     */
    public function login() {
        $returnData = array();
        $returnData['returnData'] = false;
        $emailOrNisn = $this->nisn;
        $cek1 = $this->db->query("SELECT `user_id`,`nisn`,`email`,`password` 
          FROM `tbl_users`
          WHERE `email`='$emailOrNisn'
          OR `nisn`='$emailOrNisn'");
        if ($cek1) {
         if ($cek1->num_rows > 0) {
               //cek password
          $row = $cek1->fetch_object();
          if (password_verify($this->password, $row->password)) {
             $returnData['successCek'] = true;
             $returnData['returnData'] = $row;
         } else {
            $returnData['successCek'] = false;
            $returnData['error'] = "Password yang anda ketikan salah";
        }
    } else {
        $returnData['successCek'] = false;
        $returnData['error'] = "Email atau nisn belum terdaftar";
    }
} else {
   $returnData['successCek'] = false;
   $returnData['error'] = $this->db->error;
}
return $returnData;
    //    else {
    //        echo $this->db->error;
    //    }
}
    /**
     * Untuk membuat session login
     * @param int $userid
     * @param string $remember
     * @return bool
     */
    public function buatSessionLogin($userId = null, $remember = "off") {
        if (empty($userId)) {
            return false;
        }
        $sessHash = bin2hex(random_bytes(32));
        $timeNow = time();
        $expire  = time()+(60*60*24*30*12);//1 tahun
        $createSession = $this->db->query("INSERT INTO tbl_sessions (user_id,waktu_login,expires,token,ip) VALUES ('$userId','$timeNow','$expire','$sessHash','1.1.1.1')");
        if ($createSession) {
            $_SESSION['___myalogin'] = $sessHash;
            if (strtolower($remember) == 'on') {
                setcookie('___myalogin',$sessHash,$expire,'/','',false,true);
            }
            return true;
        }
    }
    public function getSessionToken() {
        $tokenAuth = null;
        if (isset($_COOKIE['___myalogin'])) {
            $tokenAuth = $_COOKIE['___myalogin'];
        } elseif (isset($_SESSION['___myalogin'])) {
            $tokenAuth = $_SESSION['___myalogin'];
        }
        if(!empty($tokenAuth)){
            return $tokenAuth;
        } else {
            return null;
        }
    }
    /**
     * untuk mengecek apakah user sudah login apa belum
     * @return bool 
     */
    public function isLogin() {
        $tokenAuth = null;
        if (!empty($this->getSessionToken())) {
            $tokenAuth = $this->getSessionToken();
        }
        /**JIkaa ada cookie cek dulu */
        $timeNow = time();
        $cekToken = $this->db->query("SELECT user_id,token,expires FROM tbl_sessions WHERE token='$tokenAuth'");
        if ($cekToken->num_rows == 1) {
            return true;
        }
        return false;

    }
    /**
     * fungsi untuk mendapatkan userId berdasarkan token
     * @param string $token
     * @return obeject
     */
    public function getUserIdByToken($token = "") {
        $qid = $this->db->query("SELECT user_id FROM tbl_sessions WHERE token='$token'");
        if ($qid->num_rows == 1) {
            return $qid->fetch_object()->user_id;
        } 
    }
    /**
     * method untuk logout
     * */
    public function logout() {
        $sessionToken = $this->getSessionToken();
        unset($_COOKIE['___myalogin']);
        $this->db->query("DELETE FROM tbl_sessions WHERE token='$sessionToken'");
        setcookie('___myalogin',null,-1,'/');
        @session_destroy();

    }
    /**
     * Untuk mendapatkan data user yang sudah login
     * @return array
     */
    public function getUserInfo() {
        //kita ambil dulu session login nya
        $sessionToken = $this->getSessionToken();
        //kita dapatkan id user berdasarkan token yang di simpan di cookie atau session
        $uid = $this->getUserIdByToken($sessionToken);
        //ambil data berdasarkan user_is
        $uinfo = $this->db->query("SELECT * FROM tbl_users WHERE user_id='$uid'");
        if ($uinfo->num_rows > 0) {
            return $uinfo->fetch_assoc();
        } else {
            $this->logout();
            header("location:".base_url('login'));
        }
    }
}
