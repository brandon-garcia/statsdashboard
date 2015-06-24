<?php

require __DIR__ . "/php/Excel2SQL/sql_convert.php";
require_once __DIR__ . "/php/SQL/sql_query.php";
require_once __DIR__ . "/php/SQL/sql.php";
require_once __DIR__ . "/php/Reports/report.php";
require_once __DIR__ . "/php/Reports/chart.php";
require_once __DIR__ . "/php/globals.php";


$file_tables = array();
foreach ($_FILES["spreadsheets"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["spreadsheets"]["tmp_name"][$key];
        $name = $_FILES["spreadsheets"]["name"][$key];
        $name = preg_replace('/\s+/', '_', $name);
        move_uploaded_file($tmp_name, "tmp/$name");
        $file_tables[] = sql_convert("tmp/$name",\globals\dbname);
        unlink("tmp/$name");
    }
}

$sql = new SQL();
$sql->set_db(\globals\dbname);

$reports = array();
foreach($file_tables as $tables){
    foreach($tables as $tname){
        $query = (new SQL_Query())
            ->select('*')
            ->from($tname);

        $chart = (new Chart())
            ->setType('Table');

        $reports[] = (new Report())
            ->setTitle($tname)
            ->setQuery($query)
            ->setChart($chart)
            ->run($sql);

    }
}

if(!empty($reports)){ 
 
	$head = '';       
	for($ir=0; $ir<count($reports); ++$ir)
		$head .= $reports[$ir]->html("chart_container_$ir"); 
    
    $content = "";
	for($ir = 0; $ir<count($reports); ++$ir)
		$content .= "<div id=\"chart_container_$ir\"></div><br><br>";
	echo \html\gen_html($head,$content);
}

else{
	$content = <<<CODE
	<div class="box half-width center">"No tables were Uploaded!"</div>;
CODE;
	echo \html\gen_html('',$content);
}


