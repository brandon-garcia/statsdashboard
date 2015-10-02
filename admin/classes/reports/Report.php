<?php

namespace reports;

class Report {

    protected static $LIST = null;

    public static function getList() {
        $sql = new \sql\Database();
        $sql->selectDB(\globals\dbname_reports);
        $table_names = $sql->tableNames();
        if (empty($table_names))
            return array();

        $table = $table_names[0];
        $result = $sql->query(
            (new \sql\SqlQuery())
                ->select('title')
                ->from($table)
        );
        return $result->records();
    }

    public static function get($title) {
        $sql = new \sql\Database();
        $sql->selectDB(\globals\dbname_reports);
        $table_names = $sql->tableNames();
        if (empty($table_names))
            die("Invalid Report request.");
        $table = $table_names[0];
        $result = $sql->query(
            (new \sql\SqlQuery())
                ->select('serial')
                ->from($table)
                ->where("title='$title'")
        );
        return \reports\Report::unserialize($result->records()[0][0]);
    }

    public static function add($report) {
        $sql = new \sql\Database();
        $sql->selectDB(\globals\dbname_reports);
        $sql->query("CREATE TABLE IF NOT EXISTS reports (title VARCHAR(255) NOT NULL, "
            . "PRIMARY KEY(title), "
            . "serial VARCHAR(10000))");
        $sql->query("INSERT INTO reports VALUES ('" . $report->title() . "', '" . $report->serialize() . "')");
    }

    public static function update($report) {
        $sql = new \sql\Database();
        $sql->selectDB(\globals\dbname_reports);
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

    public function html($div_id) {
        return $this->chart->html($div_id);
    }

}