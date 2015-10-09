<?php

require_once __DIR__ . "/bootstrap.php";

$file_tables = array();
if (!empty($_POST["spreadsheet"])) {
    $file_table = \excel2sql\SqlConvert::convertToSql($_POST['spreadsheet'], \util\Config::$database->tablesDB);
    unlink($_POST['spreadsheet']);
}

$sql = new \sql\Database();
$sql->selectDB(\util\Config::$database->tablesDB);

$reports = array();
foreach ($file_table as $tname) {

    $chart = new \reports\Chart();
    $chart->setType('Table');

    $report = new \reports\Report();
    $report->setTitle($tname)
           ->setQuery("SELECT * FROM $tname")
           ->setChart($chart)
           ->run($sql);
    $reports[] = $report;
}
$header = new \html\Header();
$header->title(\util\Config::$app->name)
       ->css('css/style.min.css')
       ->js('js/jquery.min.js','js/bootstrap.min.js');

if (!empty($reports)) {

    $head = '';
    for ($ir = 0; $ir < count($reports); ++$ir)
        $head .= $reports[$ir]->script("chart_container_$ir");

    $content = "";
    for ($ir = 0; $ir < count($reports); ++$ir)
        $content .= "<div id=\"chart_container_$ir\"></div><br><br>";

    $header->addToIncludes($head);
    
} else {
    $content = "<div class='box half-width center'>'No tables were Uploaded!'</div>";
}

echo "<!DOCTYPE html>
<html lang='en'>"
.$header->html().
		"<body>
			<header>
				<noscript>
					JavaScript must be enabled in order for you to use this service.
					However, it seems JavaScript is either disabled or not supported by your browser.
					To use this service, enable JavaScript by changing your  browser options,
					then try again.
				</noscript>". \util\Html::genNavbar()
			."</header>
			<article>
				$content
			</article>
			<footer>
			</footer>
		</body>
	</html>";

