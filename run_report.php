<?php

require_once __DIR__ . "/bootstrap.php";

$sql = new \sql\Database();
$report = null;

if (isset($_GET['report'])) {
    $report = \reports\Report::get($_GET['report']);
    $sql->selectDB(\globals\dbname);
    $report->run($sql);
} else if (isset($_GET['table'])) {
    $sql->selectDB(\globals\dbname);
    $table = $_GET['table'];
    $query = (new \sql\SqlQuery())
        ->select('*')
        ->from($table);

    $chart = (new \reports\Chart())
        ->setType('Table');

    $report = (new \reports\Report())
        ->setTitle($table)
        ->setQuery($query)
        ->setChart($chart)
        ->run($sql);
} else if (isset($_GET['serial'])) {
    $report = \reports\Report::unserialize($_GET['serial']);
    $sql->selectDB(\globals\dbname);
    $report->run($sql);
}

if (!is_null($report)) {

    $head = $report->html('chart_container');
    $content = <<<CODE
		<div id="chart_container" class="chart"></div>
CODE;
    echo \util\Html::genHtml($head, $content);
}
