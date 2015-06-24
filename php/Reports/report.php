<?php

require_once __DIR__ . '/../util/html.php';
require_once __DIR__ . '/chart.php';
require_once __DIR__ . '/../SQL/sql_query.php';

class Report
{
	protected static $LIST = null;

	public static function GetList()
	{
		$sql = new SQL();
		$sql->set_db(\globals\dbname_reports);
		$table_names = $sql->table_names();
		if (empty($table_names))
			return array();

		$table  = $table_names[0];
		$result = $sql->query(
					  (new SQL_Query())
						  ->select('title')
						  ->from($table)
		);
		return $result->records();
	}

	public static function Get($title)
	{
		$sql = new SQL();
		$sql->set_db(\globals\dbname_reports);
		$table_names = $sql->table_names();
		if (empty($table_names))
			die("Invalid Report request.");
		$table  = $table_names[0];
		$result = $sql->query(
					  (new SQL_Query())
						  ->select('serial')
						  ->from($table)
						  ->where("title='$title'")
		);
		return Report::unserialize($result->records()[0][0]);
	}

	public static function Add($report)
	{
		$sql = new SQL();
		$sql->set_db(\globals\dbname_reports);
		$sql->query("CREATE TABLE IF NOT EXISTS Reports (title VARCHAR(255) NOT NULL, "
					. "PRIMARY KEY(title), "
					. "serial VARCHAR(10000))");
		$sql->query("INSERT INTO Reports VALUES ('" . $report->title() . "', '" . $report->serialize() . "')");
	}

	public static function Update($report)
	{
		$sql = new SQL();
		$sql->set_db(\globals\dbname_reports);
		$sql->query("UPDATE Reports SET serial='" . $report->serialize() . "'"
					. "WHERE title='" . $report->title() . "'");
	}

	public function __construct()
	{
	}

	public static function unserialize($serial){
		return unserialize(base64_decode($serial));
	}
	
	public function serialize(){
		$report = new Report();
		$report->setTitle($this->name);
		$report->setQuery($this->query);
		$this->chart->data = null;
		$report->setChart($this->chart);
		return base64_encode(serialize($report));
	}

	public function setTitle($title)
	{
		$this->name = $title;
		return $this;
	}

	public function title()
	{
		return $this->name;
	}

	public function setQuery($query)
	{
		$this->query = $query;
		return $this;
	}

	public function setChart($chart)
	{
		$this->chart = $chart;
		return $this;
	}

	public function run($sql)
	{
		$result = $sql->query($this->query);
		$this->chart->setData($result);
		return $this;
	}

	public function html($div_id)
	{
		 return $this->chart->html($div_id);
	}
}

?>
