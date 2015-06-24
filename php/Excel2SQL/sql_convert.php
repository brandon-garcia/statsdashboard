<?php

require_once __DIR__ . "/PHPExcel/PHPExcel.php";
require_once __DIR__ . "/PHPExcel/PHPExcel/IOFactory.php";
require_once __DIR__ . "/PHPExcel/PHPExcel/Worksheet.php";
require_once __DIR__ . "/../SQL/sql.php";
require_once __DIR__ . "/sql_type.php";
require_once __DIR__ . "/sql_table.php";


function load_file($input_filepath)
{
	try {
		$objPHPExcel = PHPExcel_IOFactory::load($input_filepath);
		return $objPHPExcel;
	} catch (PHPExcel_Reader_Exception $e) {
		die("Error loading file: " . $e->getMessage());
	}
}

#
# processes a spreadsheet file into
# corresponding SQL statements
#
function sql_convert($input_filepath, $dbname)
{
	$objPHPExcel = load_file($input_filepath);
	$tables      = array();
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
		$tbl                = new SQL_Table($worksheet, $input_filepath);
		$tables[$tbl->name] = $tbl->finalize();
	}

	$sql = new SQL();
	$sql->set_db($dbname);

	$table_names = array();
	foreach ($tables as $tname => $queries) {
		$sql->query($queries["drop"]);
		$sql->query($queries["creation"]);
		$sql->query($queries["insertion"]);
		$table_names[] = $tname;
	}
	return $table_names;
}

?>
