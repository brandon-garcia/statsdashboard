<?php

namespace sql;

class Database {

    public function __construct() {
        $this->link = (\mysql_connect(\globals\dbhost, \globals\dbuser, \globals\dbpass) or die(\mysql_error()));
    }

    public function __destruct() {
        #mysql_close($this->link);  #seems to cause more problems than its worth
    }

    public function selectDB($dbname) {
        $this->dbname = $dbname;
        $this->query("CREATE DATABASE IF NOT EXISTS " . $dbname);
        \mysql_select_db($dbname) or die('%SQL.set_db(): ' . "Unable to select database: " . $dbname);
    }

    public function query($query) {
        $result = \mysql_query(((string) $query) . ';');
        if (!$result)
            die('%SQL.query(): <br><br>' . "$query <br><br>" . \mysql_error());
        return new \sql\SqlResult($result);
    }

    public function tableNames() {
        $result = $this->query("SHOW TABLES FROM " . $this->dbname);
        $names = array();
        while ($row = \mysql_fetch_row($result->data))
            $names[] = $row[0];
        return $names;
    }

}