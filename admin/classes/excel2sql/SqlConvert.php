<?php

namespace excel2sql;

class SqlConvert {
    public static function loadFile($input_filepath) {
        try {
            $objPHPExcel = \PHPExcel_IOFactory::load($input_filepath);
            return $objPHPExcel;
        } catch (\PHPExcel_Reader_Exception $e) {
            die("Error loading file: " . $e->getMessage());
        }
    }

    #
    # processes a spreadsheet file into
    # corresponding SQL statements
    #

    public static function convertToSql($input_filepath, $dbname) {
        $objPHPExcel = self::loadFile($input_filepath);
        $tables = array();
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $tbl = new \excel2sql\SqlTable($worksheet, $input_filepath);
            if ($tbl->finalize()) {
                $tables[$tbl->name] = $tbl->finalize();
            } else {
                error_log("table import failed: $input_filepath/{$tbl->name}");
            }
        }

        $sql = new \sql\Database();
        $sql->selectDB($dbname);

        $table_names = array();
        foreach ($tables as $tname => $queries) {
            $sql->query($queries["drop"]);
            $sql->query($queries["creation"]);
            $sql->query($queries["insertion"]);
            $table_names[] = $tname;
        }
        return $table_names;
    }

    #processes a cell into a {type,value} pair
    public static function convertCellData(\PHPExcel_Cell $cell) {
        $ret = array();
        $datatype = $cell->getDataType();

        if ($datatype === \excel2sql\Type::EXCEL_NUMERIC) {
            $format = $cell->getStyle()->getNumberFormat()->getFormatCode();
            if (array_key_exists($format, \excel2sql\Type::$numeric_convert_map)) {
                $format = \excel2sql\Type::$numeric_convert_map[$format];
            }

            $ret['type'] = \excel2sql\Type::$numeric_to_sql_map[$format];
            $ret['value'] = \PHPExcel_Style_NumberFormat::toFormattedString($cell->getValue(), $format);
            if ($format === \excel2sql\Type::EXCEL_DATE || $format === \excel2sql\Type::EXCEL_DATETIME || $format === \excel2sql\Type::EXCEL_TIME) {
                $ret['value'] = "'{$ret['value']}'";
            }
        } else {
            $ret['type'] = \excel2sql\Type::$excel_to_sql_map[$datatype];
            if ($ret['value'] = $cell->getFormattedValue()) {
                if ($datatype===\excel2sql\Type::EXCEL_STRING || $datatype===\excel2sql\Type::EXCEL_STRING2) {
                    $ret['value'] = "\"".preg_replace("[\"]", "''", $ret['value'])."\"";
                }
            } else {
                $ret['value'] = "null";
            }
        }

        return $ret;
    }
}
