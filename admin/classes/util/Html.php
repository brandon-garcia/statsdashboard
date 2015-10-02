<?php

namespace util;

class Html {

    public static function genNavbar() {
        $g_AppName = \globals\AppName;
        $menu_tables = self::genDropdownBox('Tables', self::genTableList());
        $menu_reports = self::genDropdownBox('reports', self::genReportList());
        return "<nav>
		<ul>
		<li><a href='index.php'>Home</a></li>
		$menu_tables
		$menu_reports
		<li><a href='create_report.php'>Create Report</a></li>
		</ul>
	</nav>";
    }

    public static function genTableList() {
        $sql = new \sql\Database();
        $sql->selectDB(\globals\dbname);
        $table_names = $sql->tableNames();
        $list = "";
        foreach ($table_names as $tname)
            $list .= "<li><a href=\"run_report.php?table=" . $tname . "\">" . $tname . "</a></li>";
        return $list;
    }

    public static function genReportList() {
        $reports = \reports\Report::getList();
        $list = '';
        for ($i = 0; $i < count($reports); ++$i)
            $list .= "<li><a href=\"run_report.php?report=" . $reports[$i][0] . "\">" . $reports[$i][0] . "</a></li>";
        return $list;
    }

    public static function genDropdownBox($name, $contents) {
        return "<li>
            <a href='#'>$name &#9660</a></div>
            <ul>$contents</ul>
        </li>";
    }

    public static function genTable($caption, $fields, $entries) {
        $table = '<table>' . '<caption>' . $caption . '<caption>';
        $row = "";
        foreach ($fields as $field) {
            if (is_array($field))
                $row .= '<th>' . $field['name'] . '</th>';
            else
                $row .= '<th>' . $field . '</th>';
        }
        $table .= '<tr>' . $row . '</tr>';

        foreach ($entries as $entry) {
            $row = "";
            foreach ($entry as $dat)
                $row .= '<td>' . $dat . '</td>';
            $table .= '<tr>' . $row . '</tr>';
        }
        return $table . '</table>';
    }

    public static function genHtml($head, $content) {
        $navbar = self::genNavbar();

        return "<!DOCTYPE html>
	<html lang=\'en\'>
		<head>
			<meta charset='utf-8'>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<meta name='description' content=''>
			<meta name='author' content=''>
			<title>Wheaton College - DataVis</title>
			<link rel='stylesheet' href='css/style.css'>
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
				<script type='text/javascript' src='js/html5shiv.js'></script>
			<![endif]-->
			<script src='js/jquery.min.js'></script>
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
	</html>";
    }

}
