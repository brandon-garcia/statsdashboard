<?php

namespace util;

class Html {

    public static function genNavbar() {
        $menu_tables = self::genDropdownBox('Tables', self::genTableList());
        $menu_reports = self::genDropdownBox('reports', self::genReportList());
        return "<nav class='navbar navbar-nav navbar-fixed-top' role='navigation'>
        <div class='container-fluid'>
            <div id='navbar' class='collapse navbar-collapse'>
                <ul class='nav navbar-nav'>
                <li><a href='index.php'>Home</a></li>
                $menu_tables
                $menu_reports
                <li><a href='create_report.php'>Create Report</a></li>
                </ul>
            </div>
        </div>
	</nav>";
    }

    public static function genTableList() {
        $sql = new \sql\Database();
        $sql->selectDB(\util\Config::$database->tablesDB);
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
        return "<li class='dropdown'>
                  <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>$name <span class='caret'></span></a>
                  <ul class='dropdown-menu'>
                    $contents
                  </ul>
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
}
