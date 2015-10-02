<?php

require_once __DIR__ . "/admin/globals.php";
require_once __DIR__ . "/admin/classes/util/Utility.php";
spl_autoload_register("\util\Utility::loadClass");
require_once __DIR__ . "/admin/classes/excel2sql/PHPExcel/PHPExcel.php";
