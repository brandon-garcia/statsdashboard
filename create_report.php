<?php
require_once __DIR__ . "/php/SQL/sql.php";
require_once __DIR__ . "/php/util/html.php";
require_once __DIR__ . "/php/globals.php";

$script = <<<CODE
CODE;

$content = <<<CODE
	<div class="box half-width center">
		<form id="CreateReportForm" name='my_report' class="" method="POST" action="create_report_submit.php">
			<fieldset>
				<legend>Required</legend>
					<input class="half-width" name="title" type="text" placeholder="Title" required><br><br>
					<input class="half-width" name="sql" type="text" placeholder="SQL" required><br><br>
					
					<select class="half-width" name="type" required>
						<option value="AreaChart">Area Chart</option>
						<option value="BarChart">Bar Chart</option>
						<option value="BubbleChart">Bubble Chart</option>
						<option value="CandlestickChart">Candlestick Chart</option>
						<option value="ColumnChart">Column Chart</option>
						<option value="ComboChart">Combo Chart</option>
						<option value="LineChart">Line Chart</option>
						<option value="PieChart">Pie Chart</option>
						<option value="ScatterChart">Scatter Chart</option>
						<option value="SteppedAreaChart">Stepped Area Chart</option>
						<option value="Table">Table</option>
						<option value="Timeline">Timeline</option>
						<option value="Histogram">Histogram</option>
					</select><br><br>
			</fieldset>
			<fieldset>
				<legend>Optional</legend>
					<input class="half-width" name="hAxis" type="text" placeholder="hAxis Label"><br><br>
					<input class="half-width" name="vAxis" type="text" placeholder="vAxis Label"><br><br>
					<input class="half-width" name="column1" type="text" placeholder="Column #1 Name"><br><br>
					<input class="half-width" name="column2" type="text" placeholder="Column #2 Name"><br><br>
					<input class="half-width" name="sortcolumn" type="number" min="1" placeholder="Sort by Column#"><br><br>
			</fieldset>
			<fieldset>
				<button id="preview_report" type="submit" class="submit-secondary half-width">Preview Report</button><br><br>
				<button id="save_report" type="submit" name="SaveReport" class="half-width" value="true">Save Report</button>
			</fieldset>
		</form>
	</div>
CODE;

echo \html\gen_html($script,$content);
