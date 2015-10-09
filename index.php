<?php

require_once __DIR__ . "/bootstrap.php";

$header = new \html\Header();
$header->title("Wheaton College - DataVis")
       ->css('css/style.min.css')
       ->js('js/jquery.min.js','js/bootstrap.min.js');

echo "<!DOCTYPE html>
<html lang='en'>"
.$header->html().
		"<body>";

$fname = false;
if (isset($_POST['submit'])) {
    $file = new \util\FileUpload('spreadsheet');
    if ( ($fname = $file->moveToDir("tmp")) === false) {
        $_POST['errormsg'] = "Upload Failed!";
    }
}

if ($fname) {
    echo \html\FormPanel::makeHiddenForm("UploadForm","upload_form_submit.php",array('spreadsheet'=>$fname));
} else {

    echo "<header>"
            .\util\Html::genNavbar().
        "</header>";
    $form = new \html\FormPanel("Import Spreadsheets","form-panel",'md-10',"form-horizontal","");
    if (isset($_POST['errormsg'])) {
        $form->error($_POST['errormsg']);
    }
    $form
        ->file('spreadsheet','')
        ->button('submit','Confirm/Upload','btn btn-primary');

    $container = new \html\GridDiv("container");
    $container
        ->row()
            ->column('md-1')
            ->column('md-10',null,"
                        <h1>Welcome!</h1>
                        <p>
                        Uploading Spreadsheet files will convert them into a database
                        for use in generating reports.
                        <br>Multiple files may be uploaded at one time.
                        <br><b>Supported file extensions: [ .xls , .xml ]</b>
                        </p>
                    ")
        ->row()
            ->column('md-1')
            ->column('md-10',null,$form->html())
            ->column('md-1');

    echo $container->html();
}
echo "</body></html>";
