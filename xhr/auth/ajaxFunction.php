<?php
/**
 * Fungsi untuk menangani ajax registrasi
 * @author dadan
 */
function ajax_register() {
	global $konek;//call konek variabel to function
	$response = array();
	$user = new User();//instance user class
	$nama_lengkap   = input_post("nama_lengkap", true);
	$nisn           = input_post("nisn", true);
	$email          = input_post("email", true);
	$password       = input_post("password", true);
	$passwordRepeat = input_post("password2", true);
	/**
	 * set nama nisn email dan nama lengkap
	 * untuk data registrasi
	 **/
	$user->setNamaLengkap($nama_lengkap);
	$user->setNisn($nisn);
	$user->setEmail($email);
	$user->setPassword($password);
	//email regex
	$email_val = preg_match("/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_-]+)*@([a-zA-Z0-9_]+)*(\.[a-zA-Z0-9_-]{2,5})/", $email);
	if (empty($nisn) || empty($email) || empty($password) ||  empty($passwordRepeat) || empty($nama_lengkap)) {
		$response['code'] = 200;
		$response['success'] = false;
		$response['message'] = "Semua inputan tidak boleh ada yang kosong! please check again";
		die(json_encode($response));
	} elseif ($password != $passwordRepeat) {
		$response['code'] = 200;
		$response['success'] = false;
		$response['message'] = "Password harus sama dengan yang sebelumnya! Please check again your fields";
		die(json_encode($response));
	} elseif (strlen($nisn) > 10) {
		$response['code'] = 200;
		$response['success'] = false;
		$response['message'] = "Nisn harus berisi MAX 10 karakter";
		die(json_encode($response));
	} elseif (!$email_val) {
		$response['code'] = 200;
		$response['success'] = false;
		$response['message'] = "Email yang kamu ketikan tidak valid! Contoh email yang valid dadan@gmail.com";
		die(json_encode($response));
	} else {
		if ($user->emailExists()) {
			$response['code'] = 200;
			$response['success'] = false;
			$response['message'] = "Email sudah terdaftar oleh akun lain";
			die(json_encode($response));	
		} elseif($user->nisnExists()) {
			$response['code'] = 200;
			$response['success'] = false;
			$response['message'] = "Nisn sudah terdaftar dengan akun lain!";
			die(json_encode($response));
		} else {			
			if ($user->register()) {
				$response['code'] = 200;
				$response['success'] = true;
				$response['message'] = "Pendaftaran berhasil! Silahkan login";
				die(json_encode($response));
			} else {
				$response['code'] = 200;
				$response['success'] = false;
				$response['message'] = "Pendaftaran gagal! :(";
				die(json_encode($response));
			}
		}	
	}

}


?>
