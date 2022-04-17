<?php 
//operasi untuk mengupload berkas pendukung pendaftaran
$response = array();
$file = $_FILES['file'];
$nama_berkas = input_post('nama_berkas',true);
$dir = "media/berkas/".str_replace(['\\','/'],['-','-'],$me['nisn']."-".$me['full_name']);
$upload = new Upload();
$upload->setInputFile($file);
//set extensi file yang di perbolehkan
$upload->setAllowedFile("pdf|jpg|jpeg|png");
//target directory untuk menympan file
$upload->setTargetDir($dir);
//batas ukuran file yang di perbolehkan
$upload->maxSize(10000000);
//untuk penamaan file
$upload->setCustomFileName("berkas-".str_replace(['\\','/'], '-', strtolower($me['kode_pendaftaran']))."-".$nama_berkas."-(".pathinfo($file['name'],PATHINFO_EXTENSION).")-".rand());
if ($upload->upload()) {
	$fileFolder = $dir."/".$upload->uploadInfo['filename'];
	$SQL = "INSERT INTO tbl_berkas (kode_pendaftaran,nama_berkas,file) VALUES ('".$me['kode_pendaftaran']."','$nama_berkas','$fileFolder')";
	if ($konek->query($SQL)) {
		$response['status'] = true;
		$response['code'] = 200;
		$response['message'] = "Upload berkas berhasil";
	} else {
		@unlink($upload->uploadInfo['file_upload_dir']);
	}
} else {
	$response['status'] = false;
	$response['code'] = 200;
	$response['message'] = $upload->uploadError;
}

if (!empty($response)) {
	exit(json_encode($response));
}

?>