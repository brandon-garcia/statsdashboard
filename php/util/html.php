<?php namespace html;

require_once __DIR__ . "/../SQL/sql.php";
require_once __DIR__ . '/../Reports/report.php';
require_once __DIR__ . "/../globals.php";

function gen_navbar(){
    $g_AppName = \globals\AppName;
    $menu_tables = gen_dropdown_box('Tables',gen_table_list());
    $menu_reports = gen_dropdown_box('Reports',gen_report_list());
    return <<<CODE
	<nav>
		<ul> 
		<li><a href="index.php">Home</a></li>
		$menu_tables
		$menu_reports
		<li><a href="create_report.php">Create Report</a></li>
		</ul>
	</nav>
	
CODE;
}

#functions

function gen_table_list()
{
	$sql = new \SQL();
	$sql->set_db(\globals\dbname);
	$table_names = $sql->table_names();
	$list        = "";
	foreach ($table_names as $tname)
		$list .= "<li><a href=\"run_report.php?table=" . $tname . "\">" . $tname . "</a></li>";
	return $list;
}

function gen_report_list()
{
	$reports = \Report::GetList();
	$list    = '';
	for($i=0; $i<count($reports); ++$i)
		$list .= "<li><a href=\"run_report.php?report=" . $reports[$i][0] . "\">" . $reports[$i][0] . "</a></li>";
	return $list;
}

function gen_dropdown_box($name, $contents)
{
    return <<<HTML
        <li>
            <a href="#">$name &#9660</a></div>
            <ul>$contents</ul>
        </li>
HTML;
}

function gen_table($caption, $fields, $entries)
{
	$table = '<table>' . '<caption>'.$caption.'<caption>';
	$row   = "";
	foreach ($fields as $field) {
		if (is_array($field))
			$row .= '<th>'.$field['name'].'</th>';
		else
			$row .= '<th>'.$field.'</th>';
	}
	$table .= '<tr>'.$row.'</tr>';

	foreach ($entries as $entry) {
		$row = "";
		foreach ($entry as $dat)
			$row .= '<td>'.$dat.'</td>';
		$table .= '<tr>'.$row.'</tr>';
	}
	return $table . '</table>';
}

function gen_html($head,$content){
	$navbar = gen_navbar();
	
return <<<CODE
	<!DOCTYPE html>
	<html lang=\"en\">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="">
			<meta name="author" content="">
			<title>Wheaton College - DataVis</title>
			<link rel="stylesheet" href="css/style.css">
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
				<script type="text/javascript" src="js/html5shiv.js"></script>
			<![endif]-->
			<script src="js/jquery.min.js"></script>
			$head
		</head>
		<body>
			<header>
				<noscript>
					JavaScript must be enabled in order for you to use this service.
					However, it seems JavaScript is either disabled or not supported by your browser. 
					To use this service, enable JavaScript by changing your  browser options, 
					then try again.
				</noscript>
				$navbar
			</header>
			<article>
				$content
			</article>
			<footer>
			</footer>
		</body>
	</html>
CODE;
}

?>
