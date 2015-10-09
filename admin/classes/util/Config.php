<?php

namespace util;

class Config {

    public static $xhgui;
    public static $database;
    public static $app;

    public static function init() {
        $data = parse_ini_file("admin/config.ini", true);
        self::$database = (object) $data['database'];
        self::$app = (object) $data['app'];

        if (isset($data['xhgui'])) {
            self::$xhgui = (object) $data['xhgui'];
        } else {
            self::$xhgui = new \stdClass;
        }

        if (!isset(self::$xhgui->installed)) {
            self::$xhgui->installed = false;
        }
    }

}
