<?php

namespace excel2sql;

class SqlField {

    public function __construct(\PHPExcel_Cell $cell) {
        # SQL field names cannot contain spaces
        $this->name = preg_replace("#[^\\w]#", '_', $cell->getValue());
        $this->members = array();
    }

    #prep for SQL

    public function finalize() {
        if ($this->name) {
            $currentType = null;
            for ($i = 0; $i < count($this->members); ++$i) {
                if (is_null($currentType))
                    $currentType = $this->members[$i];
                else if (strcmp($currentType, $this->members[$i]) !== 0) {
                    $currentType = \excel2sql\Type::SQL_STRING;
                    break;
                }
            }
            if (is_null($currentType)) {
                $currentType = \excel2sql\Type::SQL_STRING;
            }

            $ret = new \stdClass();
            $ret->name = $this->name;
            $ret->type = $currentType;
            return $ret;
        }
        return false;
    }

}
