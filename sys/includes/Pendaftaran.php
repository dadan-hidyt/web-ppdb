<?php 
/**
 * Kelas pendaftaran sebagai blue print 
 * @author dadan
 **/
class Pendaftaran{
	private static $instance = null;
	public $userInstance = null;
	public $kodePendaftaran = null;
	private $db;
	public function __construct() {
		if ($this->userInstance === null) {
			$this->userInstance = User::getInstance();
		}
		$this->db = $GLOBALS['konek'];
	}
	/**
	 * singgleton
	 */
	public static function instance() {
		if (self::$instance == null) {
			self::$instance = new Pendaftaran();
		}
		return self::$instance;
	}
	/**
	 * Fungsi untuk menset kode pendaftaran user
	 * @param string $kodePendaftaran
	 */
	public function setKodePendaftaran (string $kodePendaftaran) {
		$this->kodePendaftaran = $kodePendaftaran;
	}
	/**
	 * fungsi untuk mendapatkan jalur pendaftaran dari databse
	 * @return array
	 */
	public function getJalurPendaftaran() {
		$container = array();
		$query = $this->db->query("SELECT * FROM tbl_jalur_pendaftaran");
		if (false !== $query) {
			if ($query->num_rows > 0) {
				while ($data = mysqli_fetch_assoc($query)) {
					$container[] = $data;
				}
			}
		} else {
			die("databse error: ".$this->db->error);
		}
		return $container;
	}
	/**
	 * fungsi untuk mengecek formulir pendaftaran calon siswa
	 * sudah di isi atau belum
	 * @param string $kode_pendaftaran
	 */
	public function cekStatusPendaftaran() {
		$kode_pendaftaran = $this->kodePendaftaran;
		if ($query = $this->db->query("SELECT kode_pendaftaran,status FROM tbl_formulir_pendaftaran WHERE kode_pendaftaran='$kode_pendaftaran'")) {
			if ($query->num_rows > 0) {
				return $query->fetch_assoc();
			} else {	
				return false;
			}
		} else {
			echo $this->db->error;
		}
	}
	/**
	 * Get data formulir pendaftaran
	 * 
	*/
	public function getFormulirPendaftaran () {
		$data = array();
		$kode_pendaftaran = $this->kodePendaftaran;
		$SQL = "SELECT * FROM tbl_formulir_pendaftaran WHERE kode_pendaftaran='$kode_pendaftaran'";
		$Query = $this->db->query($SQL);
		if ($Query) {
			if ($Query->num_rows > 0) {
				return $Query->fetch_assoc();
			}
		}
		return $data;
	}
	public function generateKodePendaftaran($length = 5, $kapital = false, $number = false, $karakter = false) {
		$prefix = "PPDB";
		$posfix = date("Y")."/".date("Y",strtotime("+1 year"));
		//random
		$char = "abcdefghijklmnopqrstuvwxyz";
		if ($kapital) {
			$char .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		} 
		if ($number) {
			$char .= "1234567890";
		} 
		if ($karakter) {
			$char .= "@$&!-_$";
		} 
		$char = str_shuffle($char);
		if ($length > strlen($char)) {
			$length = strlen($char);
		}
		$random_str = "";
		for ($c = 0; $c <= $length; $c++) {
			$random_str .= $char[$c];
		}
		return $prefix.'-'.$random_str.'-'.$posfix;

	}
	public function berkasPendaftaran () {
		$kode = $this->kodePendaftaran;
		$SQL = "SELECT * FROM tbl_berkas WHERE kode_pendaftaran='$kode'";
		if ($Query = $this->db->query($SQL)) {
			if ($Query->num_rows > 0) {
				$data = array();
				while ($a = $Query->fetch_assoc()) {
					$data[] = $a;
				}
				return $data;
			} else {
				return false;
			}
		}
	}

}

?>