<?php

class SQL_Query
{
	public function __construct()
	{
		$this->str = '';
	}

	public function __toString()
	{
		return $this->str;
	}

	public function select($selection)
	{
		$this->str .= ' SELECT ';
		if (is_array($selection))
			for ($i = 0; $i < count($selection); ++$i) {
				if ($i !== 0)
					$this->str .= ', ';
				if (is_array($selection[$i]))
					$this->str .= $selection[$i]['name'];
				$this->str .= $selection[$i];
			}
		else
			$this->str .= (string)$selection;
		return $this;
	}

	public function from($from)
	{
		$this->str .= ' FROM ' . $from;
		return $this;
	}

	public function where($where)
	{
		$this->str .= ' WHERE ' . $where;
		return $this;
	}

	public function group($group_by)
	{
		$this->str .= ' GROUP BY ' . $group_by;
		return $this;
	}

	public function order($order_by)
	{
		$this->str .= ' ORDER BY ' . $order_by;
		return $this;
	}

	public function limit($limit)
	{
		$this->str .= ' LIMIT ' . $limit;
		return $this;
	}
}

?>
