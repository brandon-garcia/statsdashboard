<?php

require_once __DIR__ . "/bootstrap.php";

$file_tables = array();
foreach ($_FILES["spreadsheets"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["spreadsheets"]["tmp_name"][$key];
        $name = $_FILES["spreadsheets"]["name"][$key];
        $name = preg_replace('/\s+/', '_', $name);
        move_uploaded_file($tmp_name, "tmp/$name");
        $file_tables[] = \excel2sql\SqlConvert::convertToSql("tmp/$name", \globals\dbname);
        unlink("tmp/$name");
    }
}

$sql = new \sql\Database();
$sql->selectDB(\globals\dbname);

$reports = array();
foreach ($file_tables as $tables) {
    foreach ($tables as $tname) {
        $query = (new \sql\SqlQuery())
            ->select('*')
            ->from($tname);

        $chart = (new \reports\Chart())
            ->setType('Table');

        $reports[] = (new \reports\Report())
            ->setTitle($tname)
            ->setQuery($query)
            ->setChart($chart)
            ->run($sql);
    }
}

if (!empty($reports)) {

    $head = '';
    for ($ir = 0; $ir < count($reports); ++$ir)
        $head .= $reports[$ir]->html("chart_container_$ir");

    $content = "";
    for ($ir = 0; $ir < count($reports); ++$ir)
        $content .= "<div id=\"chart_container_$ir\"></div><br><br>";
    echo \util\Html::genHtml($head, $content);
} else {
    $content = <<<CODE
	<div class="box half-width center">"No tables were Uploaded!"</div>;
CODE;
    echo \util\Html::genHtml('', $content);
}


