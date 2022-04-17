<?php
//submit form
function submitPendaftaran() {
	global $konek;
	$response = array();
    //tangkap data dari inputan
	$nama_lengkap            	= 	input_post("nama_lengkap", true);
	$nisn                    	= 	input_post("nisn", true);
	$nik_siswa               	= 	input_post("nik_siswa", true);
	$jenis_kelamin           	= 	input_post("jenis_kelamin", true);
	$tempat_lahir            	= 	input_post('tempat_lahir', true);
	$tanggal_lahir           	= 	input_post("tanggal_lahir", true);
	$agama                   	= 	input_post("agama", true);
	$nomor_telepon_siswa     	= 	input_post("nomor_telepon_siswa", true);
	$hobi                    	= 	input_post("hobi", true);
	$kota                    	= 	input_post("kota", true);
	$kecamatan               	= 	input_post('kecamatan', true);
	$kelurahanordesa         	= 	input_post("kelurahanordesa", true);
	$alamat_lengkap          	= 	input_post("alamat_lengkap", true);
	$asal_sekolah            	= 	input_post("asal_sekolah", true);
	$tahun_lulus             	= 	input_post("tahun_lulus", true);
	$berat_badan             	= 	input_post("berat_badan", true);
	$tinggi_badan            	= 	input_post('tinggi_badan', true);
	$anak_ke                 	= 	input_post("anak_ke", true);
	$jumlah_saudara          	= 	input_post("jumlah_saudara", true);
	$jalur_pendaftaran       	= 	input_post("jalur_pendaftaran", true);
	$alamat_asal_sekolah     	= 	input_post("alamat_asal_sekolah", true);
	$pendidikan_terakhir_ayah	= 	input_post("pendidikan_terakhir_ayah", true);
	$pendidikan_terakhir_ibu	= 	input_post("pendidikan_terakhir_ibu", true);
	$pekerjaan_ayah          	= 	input_post('pekerjaan_ayah', true);
	$pendapatan_ayah         	= 	input_post("pendapatan_ayah", true);
	$jurusan				 	= 	input_post("jurusan", true);
	$jurusan2				 	= 	input_post("jurusan2", true);
	$nomor_telepon_ayah 		= 	input_post("nomor_telepon_ayah", true);
	$nomor_telepon_ibu			=	input_post("nomor_telepon_ibu", true);
	$nama_ibu					= 	input_post("nama_ibu", true);
	$pekerjaan_ibu 				=	input_post("pekerjaan_ibu", true);
	$pendapatan_ibu				=   input_post("pendapatan_ibu", true);
	$alamat_sekolah_asal		=	input_post("alamat_sekolah_asal", true);
	$nama_ayah					=   input_post("nama_ayah", true);
    //foto
	$targetDir = "media/avatar/".date("Y");
	//untuk mengupload file
	$upload = new Upload();
	$upload->setInputFile($_FILES['photo']);
	$upload->setAllowedFile("png|jpg|jpeg");
	//batas upload gambar 3 MB
	$upload->maxSize(3145728);
	$upload->setTargetDir($targetDir);
	if (!$upload->upload()) {
		$response = array(
			"success"=>false,
			"code" => 200,
			"message"=>$upload->uploadError
		);
		exit(json_encode($response));
	}
	$userinfo = User::getInstance()->getUserInfo();
	$pendaftaran = new Pendaftaran();
	$kode_pendaftaran = $userinfo['kode_pendaftaran'];
	$photo_calon_siswa = str_replace('\\', '/', $targetDir)."/".$upload->uploadInfo['filename'];
	$sql = "INSERT INTO `tbl_formulir_pendaftaran` (`kode_pendaftaran`, `status`, `nama_lengkap`, `nisn`, `nik_siswa`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `telepon_siswa`, `hobi`, `kota`, `kecamatan`, `kelurahan`, `alamat_siswa`, `asal_sekolah`, `tahun_lulus`, `berat_badan`, `tinggi_badan`, `anak_ke`, `jumlah_saudara`, `jalur_pendaftaran`, `alamat_sekolah_asal`, `photo_calon_siswa`, `nama_ayah`, `pendidkan_terakhir_ayah`, `pekerjaan_ayah`, `pendapatan_ayah`, `nomor_telepon_ayah`, `nama_ibu`, `pendidikan_terakhir_ibu`, `pekerjaan_ibu`, `pendapatan_ibu`, `nomor_telepon_ibu`, `jurusan`, `jurusan2`) VALUES ('$kode_pendaftaran', 0, '$nama_lengkap', '$nisn', '$nik_siswa', '$jenis_kelamin', '$tempat_lahir','$tanggal_lahir','$agama','$nomor_telepon_siswa','$hobi','$kota','$kecamatan','$kelurahanordesa','$alamat_lengkap','$asal_sekolah','$tahun_lulus','$berat_badan', '$tinggi_badan', '$anak_ke', '$jumlah_saudara', '$jalur_pendaftaran', '$alamat_sekolah_asal', '$photo_calon_siswa', '$nama_ayah', '$pendidikan_terakhir_ayah', '$pekerjaan_ayah', '$pendapatan_ayah', '$nomor_telepon_ayah', '$nama_ibu', '$pendidikan_terakhir_ibu', '$pekerjaan_ibu', '$pendapatan_ibu', '$nomor_telepon_ibu', '$jurusan', '$jurusan2')";
	//insert data
	if ($konek->query($sql)) {
		$response = array(
			"success"=>true,
			"code" => 200,
			"message"=>"formulir pendaftaran sukses di kirim!"
		);
		exit(json_encode($response));
	} else {
		$response = array(
			"success"=>false,
			"code" => 200,
			"message"=>$konek->error
		);
		exit(json_encode($response));
	}



}

?>