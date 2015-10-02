<?php

namespace reports;

class Chart {

    public function __construct() {
        $this->options = new \reports\visualization\ChartOptions();
        $this->options->set('animation', "{duration:1000, easing: 'out',}");
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setTitle($title) {
        $this->options->set('title', "'$title'");
        return $this;
    }

    public function setAxisLabels($vAxis, $hAxis) {
        $this->options->set('hAxis', "{title:'$hAxis'}")
            ->set('vAxis', "{title:'$vAxis'}");
        return $this;
    }

    public function setData($sql_result) {
        $this->data = new \reports\visualization\ChartData($sql_result);
        if (isset($this->names))
            $this->data->setColumnNames($this->names);
        return $this;
    }

    public function setColumnNames($names) {
        $this->names = $names;
        return $this;
    }

    public function html($div_id) {
        return \reports\visualization\ChartCode::html($this, $div_id);
    }

}

?>
