<?php

require_once __DIR__ . "/bootstrap.php";


$errors = array();
if (isset($_POST['submit'])) {
    foreach($_POST as $k => $v) {
        $_POST[$k] = trim($v);
    }

    if (!empty($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        $errors[] = "You must provide a title in order to create a report!";
    }

    if (!empty($_POST['sql'])) {
        $query = $_POST['sql'];
    } else {
        $errors[] = "You must provide sql in order to create a report!";
    }

    if (count($errors)===0) {
        $sql = new \sql\Database();
        $sql->selectDB(\util\Config::$database->tablesDB);

        $hAxis = null;
        if (isset($_POST['hAxis']))
            $hAxis = $_POST['hAxis'];

        $vAxis = null;
        if (isset($_POST['vAxis']))
            $vAxis = $_POST['vAxis'];

        $column1 = null;
        if (isset($_POST['column1']))
            $column1 = $_POST['column1'];

        $column2 = null;
        if (isset($_POST['column2']))
            $column2 = $_POST['column2'];

        $sortcolumn = null;
        if (isset($_POST['sortcolumn']))
            $sortcolumn = $_POST['sortcolumn'];

        $type = preg_replace("#[\\s]#", '', $_POST['type']);

        $chart = new \reports\Chart();
        $chart
            ->setTitle($title)
            ->setAxisLabels($vAxis, $hAxis)
            ->setColumnNames(array($column1, $column2))
            ->setType($type);

        $report = new \reports\Report();
        $report
            ->setTitle($title)
            ->setQuery($query)
            ->setChart($chart);

        if (isset($_POST['submit_type']) && $_POST['submit_type'] === 'save_report') {
            \reports\Report::add($report);
            header("Location: run_report.php?report=$title");
        } else {
            $serial = $report->serialize();
            header("Location: run_report.php?serial=$serial");
        }
        exit;
    }
}

$header = new \html\Header();
$header->title("Wheaton College - DataVis")
       ->css('css/style.min.css')
       ->js('js/jquery.min.js','js/bootstrap.min.js');

$form = new \html\FormPanel("Import Spreadsheets","form-panel",'md-10',"form-horizontal","");
foreach ($errors as $err) {
    $form->error($err);
}

echo "<header>"
            .\util\Html::genNavbar().
        "</header>";
    $form = new \html\FormPanel("Create Report","form-panel",'md-10',"form-horizontal","");
    if (isset($_POST['errormsg'])) {
        $form->error($_POST['errormsg']);
    }
    $form
        ->textInput('title','Title*',true)
        ->textArea('sql','SQL*',true)
        ->select('type','Chart Type*',true)
            ->option('AreaChart','Area Chart')
            ->option('BarChart','Bar Chart')
            ->option('BubbleChart','Bubble Chart')
            ->option('CandlestickChart','Candlestick Chart')
            ->option('ColumnChart','Column Chart')
            ->option('ComboChart','Combo Chart')
            ->option('LineChart','Line Chart')
            ->option('PieChart','Pie Chart')
            ->option('ScatterChart','Scatter Chart')
            ->option('SteppedAreaChart','Stepped Area Chart')
            ->option('Table','Table')
            ->option('Timeline','Timeline')
            ->option('Histogram','Histogram')
            ->textInput('hAxis','hAxis Label')
            ->textInput('vAxis','vAxis Label')
            ->textInput('column1','Column #1 Name')
            ->textInput('column2','Column #2 Name')
            ->numberInput('sortcolumn','Sort by Column#',1)
        ->select('submit_type','Submit Type*',true)
            ->option('preview_report',"View Only")
            ->option('save_report','Save and View')
        ->button('submit','Confirm/Upload','btn btn-primary');

    $container = new \html\GridDiv("container");
    $container
        ->row()
            ->column('md-1')
            ->column('md-10',null,$form->html())
            ->column('md-1');

echo "<!DOCTYPE html>
<html lang='en'>"
.$header->html().
		"<body>";
echo "<header>".\util\Html::genNavbar()."</header>";
echo $container->html(),"</body></html>";