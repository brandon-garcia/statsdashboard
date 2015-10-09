<?php

require_once __DIR__ . "/bootstrap.php";

$sql = new \sql\Database();
$report = null;

if (isset($_GET['report'])) {
    $report = \reports\Report::get($_GET['report']);
    $sql->selectDB(\util\Config::$database->tablesDB);
    $report->run($sql);
} else if (isset($_GET['table'])) {
    $sql->selectDB(\util\Config::$database->tablesDB);
    $table = $_GET['table'];

    $chart = new \reports\Chart();
    $chart->setType('Table');

    $report = new \reports\Report();
    $report->setTitle($table)
           ->setQuery("SELECT * FROM $table")
           ->setChart($chart)
           ->run($sql);
} else if (isset($_GET['serial'])) {
    $report = \reports\Report::unserialize($_GET['serial']);
    $sql->selectDB(\util\Config::$database->tablesDB);
    $report->run($sql);
}

if (!is_null($report)) {
    $header = new \html\Header();
    $header->title(\util\Config::$app->name)
           ->css('css/style.min.css')
           ->js('js/jquery.min.js','js/bootstrap.min.js')
           ->addToIncludes($report->script('chart_container'));
    $content = "<div id='chart_container' class='chart'></div>";
    echo "<!DOCTYPE html>
<html lang='en'>"
.$header->html().
		"<body>
			<header>".\util\Html::genNavbar()."</header>
			<article>
				$content
			</article>
			<footer>
			</footer>
		</body>
	</html>";
}
