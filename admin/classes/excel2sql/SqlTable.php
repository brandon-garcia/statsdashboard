<?php

namespace excel2sql;

class SqlTable {

    public function __construct(\PHPExcel_Worksheet $worksheet, $parent_filename) {
        $this->name = $this->genName($parent_filename, $worksheet->getTitle());
        $row_it = $worksheet->getRowIterator();
        $this->header = new \excel2sql\SqlHeader($row_it->current());
        $this->entries = array();

        for ($row_it->next(); $row_it->valid(); $row_it->next()) {
            $rowindex = $row_it->current()->getRowIndex();
            $this->entries[] = new \excel2sql\SqlEntry($rowindex, $this->header, $worksheet);
        }
    }

    public function finalize() {
        if ($header = $this->header->finalize()) {

            $entries = array();
            foreach ($this->entries as $entry) {
                if ($str = $entry->finalize()) {
                    $entries[] = $str;
                }
            }

            if (!empty($entries)) {
                return array(
                    'drop' => "DROP TABLE IF EXISTS $this->name;",
                    'creation' => "CREATE TABLE $this->name ($header->creation);",
                    'insertion' => "INSERT INTO $this->name ($header->insertion) VALUES ".\util\Misc::array2str($entries).';'
                );
            }
        }

        return false;
    }

    //ex: 'filename_worksheet_name'
    private function genName($filename, $worksheet_name) {
        $filename = \util\Misc::regexExtractFilename($filename);
        $tbl_name = "{$filename[0]}_$worksheet_name";

        # ' ' => '_'
        $tbl_name = preg_replace("#[^\\w]+#", '_', $tbl_name);

        # '__' => '_'
        $tbl_name = preg_replace("#[_]{2}[_]*#", '_', $tbl_name);

        return $tbl_name;
    }

}