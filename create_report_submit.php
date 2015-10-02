<?php

require_once __DIR__ . "/bootstrap.php";

$sql = new \sql\Database();
$sql->selectDB(\globals\dbname);

if (isset($_POST['title']))
    $title = $_POST['title'];
if (isset($_POST['sql']))
    $query = $_POST['sql'];

if (isset($_POST['hAxis']))
    $hAxis = $_POST['hAxis'];
if (isset($_POST['vAxis']))
    $vAxis = $_POST['vAxis'];

if (isset($_POST['column1']))
    $column1 = $_POST['column1'];
if (isset($_POST['column2']))
    $column2 = $_POST['column2'];

if (isset($_POST['sortcolumn']))
    $sortcolumn = $_POST['sortcolumn'];

$type = preg_replace("#[\\s]#", '', $_POST['type']);

$chart = (new \reports\Chart())
    ->setTitle($title)
    ->setAxisLabels($vAxis, $hAxis)
    ->setColumnNames(array($column1, $column2))
    ->setType($type);

$report = (new \reports\Report())
    ->setTitle($title)
    ->setQuery($query)
    ->setChart($chart);

if (isset($_POST['SaveReport']) and $_POST['SaveReport'] == 'true') {
    \reports\Report::add($report);
    header("Location: run_report.php?report=$title");
} else {
    $serial = $report->serialize();
    header("Location: run_report.php?serial=$serial");
}
exit;
?>
