<?php

require_once __DIR__ . "/bootstrap.php";

$content = <<<CODE
	<div class="box half-width center">
		<h1>Welcome!</h1>
		<p>
		Uploading Spreadsheet files will convert them into a database
		for use in generating reports.
		<br>Multiple files may be uploaded at one time.
		<br><b>Supported file extensions: [ .xls , .xml ]</b>
		</p>
		<form method="POST" enctype="multipart/form-data" action="upload_form_submit.php">
			<fieldset>
				<input type="file" name="spreadsheets[]" multiple=true accept=".xls,.xml">
				<button type="submit">Confirm/Upload</button>
			</fieldset>
		</form>
	</div>
CODE;

echo \util\Html::genHtml('', $content);
