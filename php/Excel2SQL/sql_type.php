<?php

function correct_string($val)
{
	$str = preg_replace("[\"]", "''", $val);
	return $str;
}

function correct_format($fmt)
{
	$fmt = preg_replace("#[-]#", '/', $fmt);
	return $fmt;
}

function convert_datetime($val, $from, $to)
{
	$timestamp = strtotime($val);
	$date      = date('Y-m-d', $timestamp);
	return $date;
}

class Type
{

	# to save on typing
	const SQL_STRING     = "VARCHAR(255)";
	const EXCEL_NUMERIC  = PHPExcel_Cell_DataType::TYPE_NUMERIC;
	const EXCEL_STRING   = PHPExcel_Cell_DataType::TYPE_STRING;
	const EXCEL_STRING2  = PHPExcel_Cell_DataType::TYPE_STRING2;
	const EXCEL_DATE     = PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2;
	const EXCEL_DATETIME = PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME;
	const EXCEL_TIME     = PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4;

	#finds a similar numeric format in excel that is usable in SQL
	private static function numericFormat(PHPExcel_Cell $cell)
	{
		$format = $cell->getStyle()->getNumberFormat()->getFormatCode();
		if (array_key_exists($format, Type::$numeric_convert_map))
			return Type::$numeric_convert_map[$format];
		else
			return $format;
	}

	#formats a cell's value for insertion into SQL
	public static function SQLValue(PHPExcel_Cell $cell)
	{
		if ($cell->getDataType() === Type::EXCEL_NUMERIC) {
			$format = Type::numericFormat($cell);
			$val    = PHPExcel_Style_NumberFormat::toFormattedString($cell->getValue(), $format);
			if ($format === Type::EXCEL_DATE || $format === Type::EXCEL_DATETIME || $format === Type::EXCEL_TIME)
				return "'" . $val . "'";
			return $val;
		}
		$val = $cell->getFormattedValue();
		if (empty($val))
			return "null";
		if ($cell->getDataType() === Type::EXCEL_STRING ||
			$cell->getDataType() === Type::EXCEL_STRING2
		)
			return "\"" . correct_string($val) . "\"";
		return $val;
	}

	#returns the SQL type corresponding to the cell's datatype/formatting
	public static function SQLType(PHPExcel_Cell $cell)
	{
		if (array_key_exists($cell->getDataType(), Type::$excel_to_sql_map)) {
			if ($cell->getDataType() === Type::EXCEL_NUMERIC) {
				return Type::$numeric_to_sql_map[Type::numericFormat($cell)];
			}
			return Type::$excel_to_sql_map[$cell->getDataType()];
		}
		return Type::SQL_STRING;
	}

	private static $excel_to_sql_map = array(
		Type::EXCEL_STRING                => Type::SQL_STRING,
		Type::EXCEL_STRING2               => Type::SQL_STRING,
		PHPExcel_Cell_DataType::TYPE_BOOL => "BOOL",
		Type::EXCEL_NUMERIC               => "NUMERIC"
	);

	private static $numeric_to_sql_map = array(
		PHPExcel_Style_NumberFormat::FORMAT_GENERAL                 => "INT",
		PHPExcel_Style_NumberFormat::FORMAT_TEXT                    => Type::SQL_STRING,
		PHPExcel_Style_NumberFormat::FORMAT_NUMBER                  => "INT",
		PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00               => "FLOAT",
		PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 => "DOUBLE",
		PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2 => "DOUBLE",
		PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE              => "INT",
		PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00           => "FLOAT",
		Type::EXCEL_DATE                                            => "DATE",
		Type::EXCEL_DATETIME                                        => "DATETIME",
		Type::EXCEL_TIME                                            => "TIME",
		PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE     => "DOUBLE",
		PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD            => "DOUBLE",
		PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE     => "DOUBLE"
	);

	private static $numeric_convert_map = array(
		PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD      => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY      => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH      => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS      => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_DMMINUS       => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_MYMINUS       => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14        => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15        => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX16        => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX17        => Type::EXCEL_DATE,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22        => Type::EXCEL_DATETIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME1         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME2         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME5         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME6         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME7         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8         => Type::EXCEL_TIME,
		PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH => Type::EXCEL_DATETIME
	);
}

?>
