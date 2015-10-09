<?php

namespace util;

class Misc {

    public static function loadClass($classname) {
        if (stripos($classname,'PHPExcel') !== false) {
            return false;
        }

        $filename = "admin/classes/"
        . str_replace('\\', DIRECTORY_SEPARATOR, ltrim($classname, '\\'))
        . ".php";

        if ($filepath = stream_resolve_include_path($filename)) {
            require $filepath;
        }
        return $filepath !== false;
    }

    public static function array2str($a) {
        $list = '';
        $first = true;
        foreach ($a as $val) {
            if ($first)
                $first = false;
            else
                $list .= ',';
            $list .= $val;
        }
        return $list;
    }

    public static function regexExtractFilename($filepath) {
        $regex_filename = "#[-\\w\\s\\(\\)]+(?=[.][\\w]+$)#";
        $filename = array();
        \preg_match($regex_filename, $filepath, $filename);
        return $filename;
    }

}

?>
