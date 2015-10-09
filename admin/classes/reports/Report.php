<?php

namespace reports;

class Report {

    protected static $LIST = null;

    public static function getList() {
        $sql = new \sql\Database();
        $sql->selectDB(\util\Config::$database->reportsDB);
        $table_names = $sql->tableNames();
        if (empty($table_names))
            return array();

        $table = $table_names[0];
        $result = $sql->query("SELECT title FROM $table");
        return $result->records();
    }

    public static function get($title) {
        $sql = new \sql\Database();
        $sql->selectDB(\util\Config::$database->reportsDB);
        $table_names = $sql->tableNames();
        if (empty($table_names))
            die("Invalid Report request.");
        $table = $table_names[0];
        $result = $sql->query("SELECT serial FROM $table WHERE title='$title'");
        return \reports\Report::unserialize($result->records()[0][0]);
    }

    public static function add($report) {
        $sql = new \sql\Database();
        $sql->selectDB(\util\Config::$database->reportsDB);
        $sql->query("CREATE TABLE IF NOT EXISTS reports (title VARCHAR(255) NOT NULL, "
            . "PRIMARY KEY(title), "
            . "serial VARCHAR(10000))");
        $sql->query("INSERT INTO reports VALUES ('" . $report->title() . "', '" . $report->serialize() . "')");
    }

    public static function update($report) {
        $sql = new \sql\Database();
        $sql->selectDB(\util\Config::$database->reportsDB);
        $sql->query("UPDATE reports SET serial='" . $report->serialize() . "'"
            . "WHERE title='" . $report->title() . "'");
    }

    public function __construct() {

    }

    public static function unserialize($serial) {
        return unserialize(base64_decode($serial));
    }

    public function serialize() {
        $report = new \reports\Report();
        $report->setTitle($this->name);
        $report->setQuery($this->query);
        $this->chart->data = null;
        $report->setChart($this->chart);
        return base64_encode(serialize($report));
    }

    public function setTitle($title) {
        $this->name = $title;
        return $this;
    }

    public function title() {
        return $this->name;
    }

    public function setQuery($query) {
        $this->query = $query;
        return $this;
    }

    public function setChart($chart) {
        $this->chart = $chart;
        return $this;
    }

    public function run($sql) {
        $result = $sql->query($this->query);
        $this->chart->setData($result);
        return $this;
    }

    public function script($div_id) {
        $header = "<script src='https://www.google.com/jsapi'></script>
            <script type='text/javascript'>
                google.load('visualization','1.0',{'packages':['corechart','table','timeline']});
                google.setOnLoadCallback(drawChart);
                function drawChart(){";
        if (strcmp($this->chart->type, "Table") === 0 and false) {
            $this->chart->options->set('cssClassNames', 'cssClassNames');
            $header .= "var cssClassNames = {
				'headerRow': 'table-header',
				'tableRow': 'table-row',
				'oddTableRow': 'table-row-odd',
				'selectedTableRow': 'table-row-selected',
				'hoverTableRow': 'table-row-hover',
				'headerCell': 'table-header-cell',
				'tableCell': 'table-cell',
				'rowNumberCell': ''};";
        }
        $header .= "var data = new google.visualization.DataTable();";

        foreach ($this->chart->data->columns() as $fld)
            $header .= "data.addColumn('" . $fld['type'] . "', '" . $fld['name'] . "');\n";

        $recs = array();
        foreach ($this->chart->data->rows() as $rec)
            $recs[] = '[' . \util\Misc::array2str($rec) . ']';
        $header .= "data.addRows([\n" . \util\Misc::array2str($recs) . "\n]);\n";

        $header .= "var options = {";
        $optArray = $this->chart->options->getAll();
        $strArray = array();
        foreach ($optArray as $opt => $val)
            $strArray[] = "$opt : $val";
        $header .= \util\Misc::array2str($strArray) . "};\n";

        $header .= "var chart = new google.visualization." . $this->chart->type . "(document.getElementById('$div_id'));\n";
        $header .= "chart.draw(data,options);\n";
        $header .= "} </script>";

        return $header;
    }

}