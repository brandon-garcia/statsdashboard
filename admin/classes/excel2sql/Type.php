<?php

namespace excel2sql;

class Type {

    const SQL_STRING = "VARCHAR(255)";
    const EXCEL_NUMERIC = \PHPExcel_Cell_DataType::TYPE_NUMERIC;
    const EXCEL_STRING = \PHPExcel_Cell_DataType::TYPE_STRING;
    const EXCEL_STRING2 = \PHPExcel_Cell_DataType::TYPE_STRING2;
    const EXCEL_DATE = \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2;
    const EXCEL_DATETIME = \PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME;
    const EXCEL_TIME = \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4;

    public static $excel_to_sql_map = array(
        self::EXCEL_STRING => self::SQL_STRING,
        self::EXCEL_STRING2 => self::SQL_STRING,
        \PHPExcel_Cell_DataType::TYPE_BOOL => "BOOL",
        self::EXCEL_NUMERIC => "NUMERIC"
    );

    public static $numeric_to_sql_map = array(
        'GENERAL' => 'INT',
        \PHPExcel_Style_NumberFormat::FORMAT_GENERAL => "INT",
        \PHPExcel_Style_NumberFormat::FORMAT_TEXT => self::SQL_STRING,
        \PHPExcel_Style_NumberFormat::FORMAT_NUMBER => "INT",
        \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 => "FLOAT",
        \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 => "DOUBLE",
        \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2 => "DOUBLE",
        \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE => "INT",
        \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00 => "FLOAT",
        self::EXCEL_DATE => "DATE",
        self::EXCEL_DATETIME => "DATETIME",
        self::EXCEL_TIME => "TIME",
        \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE => "DOUBLE",
        \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD => "DOUBLE",
        \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE => "DOUBLE"
    );

    public static $numeric_convert_map = array(
        'M/D/YYYY' => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_DMMINUS => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_MYMINUS => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14 => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15 => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX16 => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX17 => self::EXCEL_DATE,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22 => self::EXCEL_DATETIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME1 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME2 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME5 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME6 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME7 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8 => self::EXCEL_TIME,
        \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH => self::EXCEL_DATETIME
    );

}