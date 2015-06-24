<?php

require __DIR__ . '/underscorePHP/underscore.php';

class util
{
	public static function isarray($val)
	{
		return ((array)$val === $val);
	}

	public static function struct2str($a)
	{
		
		if (!isarray($a))
			return $a;
		$list  = "";
		$first = true;
		foreach ($a as $name => $val) {
			if ($first)
				$first = false;
			else
				$list .= ', ';

			$list .= $name . ': ';
			$list .= struct2str($val);
		}
		return '{' . $list . '}';
	}

	public static function array2str($a)
	{
		$list  = '';
		$first = true;
		foreach ($a as $val) {
			if ($first)
				$first = false;
			else
				$list .= ', ';
			$list .= $val;
		}
		return $list;
	}

	public static function regex_extract_filename($filepath)
	{
		$regex_filename = "#[-\\w\\s\\(\\)]+(?=[.][\\w]+$)#";
		preg_match($regex_filename, $filepath, $filename);
		return $filename;
	}
}

?>
