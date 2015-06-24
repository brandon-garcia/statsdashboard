<?php
require_once __DIR__ . "/php/SQL/sql.php";
require_once __DIR__ . "/php/util/html.php";
require_once __DIR__ . "/php/globals.php";
require_once __DIR__ . "/php/Reports/chart.php";
require_once __DIR__ . "/php/Reports/report.php";

$sql = new SQL();
$sql->set_db(\globals\dbname);

if(isset($_POST['title']))
	$title = $_POST['title'];
if(isset($_POST['sql']))
	$query = $_POST['sql'];

if(isset($_POST['hAxis']))
	$hAxis = $_POST['hAxis'];
if(isset($_POST['vAxis']))
	$vAxis = $_POST['vAxis'];

if(isset($_POST['column1']))
	$column1 = $_POST['column1'];
if(isset($_POST['column2']))
	$column2 = $_POST['column2'];

if(isset($_POST['sortcolumn']))
	$sortcolumn = $_POST['sortcolumn'];

$type = preg_replace("#[\\s]#",'',$_POST['type']);

$chart = (new Chart())
	->setTitle($title)
	->setAxisLabels($vAxis,$hAxis)
	->setColumnNames(array($column1,$column2))
	->setType($type);

$report = (new Report())
	->setTitle($title)
	->setQuery($query)
	->setChart($chart);

if(isset($_POST['SaveReport']) and $_POST['SaveReport']=='true'){
	Report::Add($report);
	header("Location: run_report.php?report=$title");
}
else{
	$serial = $report->serialize();
	header("Location: run_report.php?serial=$serial");
}
exit;

?>
