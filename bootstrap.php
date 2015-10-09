<?php

require_once __DIR__ . "/admin/classes/util/Misc.php";
spl_autoload_register("\util\Misc::loadClass");
require_once __DIR__ . "/admin/classes/excel2sql/PHPExcel/PHPExcel.php";

\util\Config::init();
