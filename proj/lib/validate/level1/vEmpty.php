<?php
class vEmpty
{
	public static function set(&$value) {
		$value	= null;
	}

	public static function is(&$value) {
		if (!isset($value))	{return true;}	// 未設定はtrue
		if ($value === null)	{return true;}	// nullはtrue

		$type	= gettype($value);
		//if ($type === "boolean")	{return $id === spl_object_hash(self::BOOL);}
		//if ($type === "integer")	{return $id === spl_object_hash((object)self::INT);}
		//if ($type === "float")	{return $id === spl_object_hash(self::$FLOAT);}
		if ($type === "string")		{return $value === "" ;}

		return false;
	}

} // CLASS-EOF
?>
