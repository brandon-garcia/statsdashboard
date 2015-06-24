<?php

require_once __DIR__ . '/../globals.php';
require_once __DIR__ . '/../util/html.php';
require_once __DIR__ . '/../util/util.php';
require_once __DIR__ . '/visualization/chart_data.php';
require_once __DIR__ . '/visualization/chart_code.php';
require_once __DIR__ . '/visualization/chart_options.php';

class Chart
{
	public function __construct()
	{
		$this->options = (new ChartOptions())
					->set('animation',"{duration:1000, easing: 'out',}");
	}

	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	public function setTitle($title)
	{
		$this->options->set('title', "'$title'");
		return $this;
	}

	public function setAxisLabels($vAxis,$hAxis){
		$this->options->set('hAxis',"{title:'$hAxis'}")
					  ->set('vAxis',"{title:'$vAxis'}");
		return $this;
	}

	public function setData($sql_result){
		$this->data = new ChartData($sql_result);
		if(isset($this->names))
			$this->data->setColumnNames($this->names);
		return $this;
	}

	public function setColumnNames($names){
		$this->names = $names;
		return $this;
	}

	public function html($div_id){
        return ChartCode::html($this,$div_id);
	}
}

?>
