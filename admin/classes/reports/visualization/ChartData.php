<?php

namespace reports\visualization;

class ChartData {

    private static function formatVal($type, $val) {
        if (strcmp($type, 'string') == 0)
            $ret = "'" . $val . "'";
        else if (strcmp($type, 'date') == 0) {
            list($yy, $mm, $dd) = explode("-", $val);
            $ret = "new Date($yy,$mm - 1,$dd)"; #subtract 1 from month because index starts at 0
        } else if (empty($val))
            $ret = 'null';
        else
            $ret = $val;

        return $ret;
    }

    public function __construct($sql_result) {
        $this->myFields = $sql_result->fields();
        $this->myRecords = $sql_result->records();
        $map = array(
            'string' => 'string',
            'real' => 'number',
            'int' => 'number',
            'date' => 'date',
            'datetime' => 'datetime'
        );
        for ($i = 0; $i < count($this->myFields); ++$i)
            $this->myFields[$i]['type'] = $map[$this->myFields[$i]['type']];
        for ($irec = 0; $irec < count($this->myRecords); ++$irec)
            for ($ival = 0; $ival < count($this->myRecords[$irec]); ++$ival)
                $this->myRecords[$irec][$ival] = self::formatVal($this->myFields[$ival]['type'], $this->myRecords[$irec][$ival]);
    }

    public function setColumnNames($names) {
        for ($i = 0; $i < count($names); ++$i)
            $this->myFields[$i]['name'] = $names[$i];
        return $this;
    }

    public function columns() {
        return $this->myFields;
    }

    public function rows() {
        return $this->myRecords;
    }

}