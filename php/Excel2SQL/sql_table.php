<?php

require_once __DIR__ . '/../util/util.php';

#processes a cell into a {type,value} pair
function SQL_DATA(PHPExcel_Cell $cell)
{
	return array(
		"type"  => Type::SQLType($cell),
		"value" => Type::SQLValue($cell)
	);
}

class SQL_Field
{
	public function __construct(PHPExcel_Cell $cell)
	{
		# SQL field names cannot contain spaces
		$this->sql_insertion = preg_replace("#[^\\w]#", '_', $cell->getValue());

		$this->members = array();
	}

	#prep for SQL
	public function finalize()
	{
		$currentType = null;
		for ($i = 0; $i < count($this->members); ++$i) {
			if (is_null($currentType))
				$currentType = $this->members[$i];
			else if (strcmp($currentType, $this->members[$i]) !== 0) {
				$currentType = Type::SQL_STRING;
				break;
			}
		}
		if (is_null($currentType))
			$currentType = Type::SQL_STRING;
		return array(
			'creation'  => $this->sql_insertion . ' ' . $currentType,
			'insertion' => $this->sql_insertion
		);
	}
}

class SQL_Header
{
	public function __construct(PHPExcel_Worksheet_Row $row)
	{
		$cell_it = $row->getCellIterator();
		$cell_it->setIterateOnlyExistingCells(false);
		$this->fields = array();
		for ($cell_it; $cell_it->valid(); $cell_it->next()) {
			$val = $cell_it->current()->getValue();
			if (empty($val))
				continue;
			$this->fields[] = new SQL_Field($cell_it->current());
		}
	}

	#prep for SQL
	public function finalize()
	{
		$sql_segments        = $this->fields[0]->finalize();
		$this->sql_insertion = ' ( ' . $sql_segments['insertion'];
		$this->sql_creation  = ' ( ' . $sql_segments['creation'];
		for ($i = 1; $i < count($this->fields); ++$i) {
			$sql_segments = $this->fields[$i]->finalize();
			$this->sql_insertion .= ', ' . $sql_segments['insertion'];
			$this->sql_creation .= ', ' . $sql_segments['creation'];
		}
		$this->sql_insertion .= ' )';
		$this->sql_creation .= ' )';
		return $this;
	}
}

class SQL_Entry
{
	public function __construct($rowindex, SQL_Header $header, PHPExcel_Worksheet $worksheet)
	{
		$this->cells = array();
		for ($i = 0; $i < count($header->fields); ++$i) {
			$cell                          = SQL_DATA($worksheet->getCellByColumnAndRow($i, $rowindex));
			$header->fields[$i]->members[] = $cell["type"];
			$this->cells[]                 = $cell;
		}
	}

	#prep for SQL
	public function finalize()
	{
		$this->sql = ' ( ' . $this->cells[0]["value"];
		for ($i = 1; $i < count($this->cells); ++$i) {
			$this->sql .= ', ' . $this->cells[$i]["value"];
		}
		$this->sql .= ' )';
		return $this;
	}
}

class SQL_Table
{
	public function __construct(PHPExcel_Worksheet $worksheet, $parent_filename)
	{
		$this->name    = $this->make_tbl_name($parent_filename, $worksheet->getTitle());
		$this->header  = new SQL_Header($worksheet->getRowIterator()->current());
		$this->entries = array();
		$row_it        = $worksheet->getRowIterator();
		$row_it->next(); #first row is header
		$rowindex        = $row_it->current()->getRowIndex();
		$this->entries[] = new SQL_Entry($rowindex, $this->header, $worksheet);
		for ($row_it->next(); $row_it->valid(); $row_it->next()) {
			$rowindex        = $row_it->current()->getRowIndex();
			$this->entries[] = new SQL_Entry($rowindex, $this->header, $worksheet);
		}
	}

	#prep for SQL
	public function finalize()
	{
		$this->header->finalize();
		$this->sql_drop      = 'DROP TABLE IF EXISTS ' . $this->name . ';';
		$this->sql_creation  = 'CREATE TABLE ' . $this->name . ' ' . $this->header->sql_creation . ';';
		$this->sql_insertion = 'INSERT INTO ' . $this->name . ' ' . $this->header->sql_insertion . ' VALUES ';
		$this->sql_insertion .= $this->entries[0]->finalize()->sql;
		for ($i = 1; $i < count($this->entries); ++$i)
			$this->sql_insertion .= ', ' . $this->entries[$i]->finalize()->sql;
		$this->sql_insertion .= ';';

		return array(
			'drop'      => $this->sql_drop,
			'creation'  => $this->sql_creation,
			'insertion' => $this->sql_insertion
		);
	}

	#ex: 'filename_worksheet_name'
	private function make_tbl_name($filename, $worksheet_name)
	{
		$filename = util::regex_extract_filename($filename)[0];
		$tbl_name = $filename . '_' . $worksheet_name;

		# ' ' => '_'
		$tbl_name = preg_replace("#[^\\w]+#", '_', $tbl_name);

		# '__' => '_'
		$tbl_name = preg_replace("#[_]{2}[_]*#", '_', $tbl_name);

		return $tbl_name;
	}
}

?>
