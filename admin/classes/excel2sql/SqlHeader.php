<?php

namespace excel2sql;

class SqlHeader {

    public function __construct(\PHPExcel_Worksheet_Row $row) {
        $cell_it = $row->getCellIterator();
        $cell_it->setIterateOnlyExistingCells(false);
        $this->fields = array();
        for ($cell_it; $cell_it->valid(); $cell_it->next()) {
            $val = $cell_it->current()->getValue();
            if (empty($val))
                continue;
            $this->fields[] = new \excel2sql\SqlField($cell_it->current());
        }
    }

    #prep for SQL

    public function finalize() {
        $insertion = array();
        $creation = array();

        foreach ($this->fields as $fld) {
            if ($fld = $fld->finalize()) {
                $insertion[] = $fld->name;
                $creation[] = "$fld->name $fld->type";
            }
        }
        if (empty($insertion)) {
            return false;
        }

        $ret = new \stdClass();
        $ret->insertion = \util\Utility::array2str($insertion);
        $ret->creation = \util\Utility::array2str($creation);

        return $ret;
    }

}