<?php

require_once __DIR__ . "/php/SQL/sql_query.php";
require_once __DIR__ . "/php/SQL/sql.php";
require_once __DIR__ . "/php/Reports/report.php";
require_once __DIR__ . "/php/Reports/chart.php";
require_once __DIR__ . "/php/globals.php";

$sql = new SQL();
$report = null;

if(isset($_GET['report'])){
    $report = Report::Get($_GET['report']);
    $sql->set_db(\globals\dbname);
    $report->run($sql);
}

else if(isset($_GET['table'])){
	$sql->set_db(\globals\dbname);
    $table = $_GET['table'];
    $query = (new SQL_Query())
        ->select('*')
        ->from($table);

    $chart = (new Chart())
        ->setType('Table');

    $report = (new Report())
        ->setTitle($table)
        ->setQuery($query)
        ->setChart($chart)
        ->run($sql);
}

else if(isset($_GET['serial'])){
	$report = Report::unserialize($_GET['serial']);
	$sql->set_db(\globals\dbname);
    $report->run($sql);
}

if(!is_null($report)){ 

	$head = $report->html('chart_container');
	$content = <<<CODE
		<div id="chart_container" class="chart"></div>
CODE;
	echo \html\gen_html($head,$content);
}
