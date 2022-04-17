<?php
class Upload {
    public $allowedFile = "all";
    public $target;
    public $fileInput;
    public $uploadError;
    public $uploadInfo;
    public $maxSize;
    public $filename;
    public function __construct() {
    }
    public function maxSize($size) {
        $this->maxSize = $size;
    }
    public function setInputFile($fileInput = array()) {
        if (!empty($fileInput)) {
            $this->fileInput = $fileInput;
        }
    }
    /**
     * method untuk mengset extensi yang di perbolehkan untuk di upload
     * @param string $extensi
     * @return void
     */
    public function setAllowedFile($extensi = "") {
        if (!empty($extensi)) {
            $extensi = explode("|",$extensi);
            $this->allowedFile = $extensi;
        }
    }
    public function setTargetDir($dir) {
        $dir = BASEPATH.DS.$dir;
        $this->target = $dir;
    }
    //move uploaded file
    private function _move_uploaded_file($tmp_name, $dir) {
        if (move_uploaded_file($tmp_name,$dir)) {
            return true;
        } else {
            return false;
        }
    }
    //template name
    public function _tmp_name() {
        return $this->fileInput['tmp_name'];
    }
    public function _file_size() {
        return $this->fileInput['size'];
    }
    /** @deprecated */
    public function setCustomFileName($filename = "") {
        if (empty($filename)) {
            $this->filename = time()."-".md5(uniqid());
        } else {
            $this->filename = $filename;
        }
    }
    public function _filename() {
        return $this->fileInput['name'];
    }
    public function _file_extension() {
        return strtolower(pathinfo($this->_filename(),PATHINFO_EXTENSION));
    }
    /**
     * Untuk mengecek extensi file yang di upload itu di perbolehkan atau tidak
     * @return boolean
     */
    public function extensi_diperbolehkan() {
        if ($this->allowedFile == "all") {
            return true;
        } else {
            if (in_array($this->_file_extension(), $this->allowedFile)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function cekSize() {
      if (!empty($this->maxSize)) {
        if ($this->fileInput['size'] <= $this->maxSize) {
            return true;
        }
    }else {
        return true;
    }
}
//upload file
public function upload() {
    if ($this->fileInput['error'] == 0) {
        if ($this->extensi_diperbolehkan()) {
            if ($this->cekSize()) {
                $newFileName = $this->filename.".".$this->_file_extension();
                if (!file_exists($this->target)) {
                    @mkdir($this->target);
                }
                if ($this->_move_uploaded_file($this->fileInput['tmp_name'], $this->target.DS.$newFileName)) {
                    $this->uploadInfo = array(
                        "file_upload_dir"=>$this->target.DS.$newFileName,
                        "filename" => $newFileName
                    );
                    return true;
                } else {
                    $this->uploadError = "File gagal di upload please try again";
                    return false;
                } 
            }else{
                $this->uploadError = "Ukuran file maxsimal ".bytes2SatuanPenyimpanan($this->maxSize);
                return false;
            }   
        } else {
            $this->uploadError = "Extensi file harus ".implode(",",$this->allowedFile);
            return false;
        }
    } else {
        if ($this->fileInput['error'] == 4) {
            $this->uploadError = "File gagal di upload! Tidak ada file yang di upload";
        }
        return false;
    }
}
}

?>