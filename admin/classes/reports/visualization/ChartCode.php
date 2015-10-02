<?php

namespace reports\visualization;

class ChartCode {

    public static function html(\reports\Chart $chart, $div_id) {
        $header = <<<CODE
            <script src="https://www.google.com/jsapi"></script>
            <script type="text/javascript">
                google.load('visualization','1.0',{'packages':['corechart','table','timeline']});
                google.setOnLoadCallback(drawChart);
                function drawChart(){
CODE;
        if (strcmp($chart->type, "Table") == 0 and false) {
            $chart->options->set('cssClassNames', 'cssClassNames');
            $header .= <<<CODE
				var cssClassNames = {
				'headerRow': 'table-header',
				'tableRow': 'table-row',
				'oddTableRow': 'table-row-odd',
				'selectedTableRow': 'table-row-selected',
				'hoverTableRow': 'table-row-hover',
				'headerCell': 'table-header-cell',
				'tableCell': 'table-cell',
				'rowNumberCell': ''};
CODE;
        }
        $header .= "var data = new google.visualization.DataTable();";

        foreach ($chart->data->columns() as $fld)
            $header .= "data.addColumn('" . $fld['type'] . "', '" . $fld['name'] . "');\n";

        $recs = array();
        foreach ($chart->data->rows() as $rec)
            $recs[] = '[' . \util\Utility::array2str($rec) . ']';
        $header .= "data.addRows([\n" . \util\Utility::array2str($recs) . "\n]);\n";

        $header .= "var options = {";
        $optArray = $chart->options->getAll();
        $strArray = array();
        foreach ($optArray as $opt => $val)
            $strArray[] = "$opt : $val";
        $header .= \util\Utility::array2str($strArray) . "};\n";

        $header .= "var chart = new google.visualization." . $chart->type . "(document.getElementById('$div_id'));\n";
        $header .= "chart.draw(data,options);\n";
        $header .= "} </script>";

        return $header;
    }

}