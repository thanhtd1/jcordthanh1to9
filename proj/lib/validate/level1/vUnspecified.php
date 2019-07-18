<?php
class vUnspecified
{
	public static function set(&$value) {
		unset($value);
	}

	public static function is($value) {
		if ($value === null)	{return false;}	// nullはfalse
		if (!isset($value))	{return true;}	// 未設定はtrue

		//$type	= gettype($value);
		//if ($type === "boolean")	return $id == spl_object_hash(self::$BOOL);
		//if ($type === "inteter")	return $id == spl_object_hash(self::$INT);
		//if ($type === "float")	return $id == spl_object_hash(self::$FLOAT);
		//if ($type === "string") {
		//	if (self::$STRING == $value) return true;
		//	if (self::$STRING === $value) return true;
		//}
		return false;
	}

} // CLASS-EOF
?>
