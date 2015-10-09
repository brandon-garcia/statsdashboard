<?php

namespace util;

class FileUpload {
    protected $id;

    public function __construct($id) {
        $this->id = $id;
    }

    protected function checkForErrors() {
        $ok = isset($_FILES[$this->id])
            && $_FILES[$this->id]['tmp_name']!=="";
        return !$ok;
    }

    public function read() {
        if ($this->checkForErrors()) {
            return FALSE;
        }

        return file_get_contents(
            $_FILES[$this->id]['tmp_name']
        );
    }

    public function moveToDir($dir) {
        $tmp_name = $_FILES[$this->id]["tmp_name"];
        $name = preg_replace('/\s+/', '_', $_FILES[$this->id]["name"]);

        if (move_uploaded_file($tmp_name,"$dir/$name") === false) {
            error_log("failed to move uploaded file to $dir/$name");
            return false;
        }
        
        return "$dir/$name";
    }
}
