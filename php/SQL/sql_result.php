<?php

class SQL_Result
{
	public function __construct($sql_result)
	{
		$this->data = $sql_result;
	}

	public function table($caption = '')
	{
		return gen_table($caption, $this->fields(), $this->records());
	}

	public function fields()
	{
		$fields = array();
		if (!mysql_num_fields($this->data))
			die('%SQL_Result.fields(): ' . mysql_error());
		$num_fields = mysql_num_fields($this->data);
		for ($f = 0; $f < $num_fields; ++$f)
			$fields[] = array(
				'name' => mysql_field_name($this->data, $f),
				'type' => mysql_field_type($this->data, $f)
			);
		return $fields;
	}

	public function records()
	{
		$entries = array();
		while ($row = mysql_fetch_row($this->data)) {
			$entries[] = $row;
		}

		return $entries;
	}
}

?>
