<?php

namespace excel2sql;

class SqlEntry {

    public $cells;
    public $sql;

    public function __construct($rowindex, \excel2sql\SqlHeader $header, $worksheet) {
        $this->cells = array();
        $this->sql = false;
        $n = count($header->fields);
        for ($i = 0; $i < $n; ++$i) {
            $cell = \excel2sql\SqlConvert::convertCellData($worksheet->getCellByColumnAndRow($i, $rowindex));
            $header->fields[$i]->members[] = $cell["type"];
            $this->cells[] = $cell;
        }
    }


    public function finalize() {
        if (count($this->cells)) {
            if ($str = \util\Utility::array2str(array_column($this->cells,'value'))) {
                return " ($str)";
            }
        }
        return "";
    }

}