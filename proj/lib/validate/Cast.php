<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(VALID_L1_DIR ."vEmpty.php");
require_once(VALID_L1_DIR ."vUnspecified.php");

class Cast
{
	public static function toBool($src,&$dst) {
		if (vUnspecified::is($src))	{ vUnspecified::set($dst);	return -1; }
		if (vEmpty::is($src))		{ vEmpty::set($dst);		return -1; }

		$type	= gettype($src);
		// boolean -> boolean
		if ($type === "boolean")	{ $dst = $src;			return 0; }

		$l_cnv	= null;
		// -> boolean
		switch (strtolower($src)) {
			case '1':
			case 'true':
				$l_cnv	= true;
				break;
			case '0':
			case 'false':
				$l_cnv	= false;
				break;
			default:
		}
		//

		if (vEmpty::is($l_cnv)) 	{ vEmpty::set($dst);		return -1; }
		//
		$dst	= $l_cnv;
		return 0;
	}

	public static function toInt($src,&$dst) {
		if (vUnspecified::is($src))	{ vUnspecified::set($dst);	return -1; }
		if (vEmpty::is($src))		{ vEmpty::set($dst);		return -1; }

		$type	= gettype($src);
		// integer -> integer
		if ($type === "integer")	{ $dst = $src;			return 0; }

		$l_cnv	= null;
		// string -> integer
		$l_cnv	= ctype_digit($src) ? intval($src) : null;
		//

		if (vEmpty::is($l_cnv)) 	{ vEmpty::set($dst);		return -1; }
		//
		$dst	= $l_cnv;
		return 0;
	}

	public static function toFloat($src,&$dst) {
		if (vUnspecified::is($src))	{ vUnspecified::set($dst);	return -1; }
		if (vEmpty::is($src))		{ vEmpty::set($dst);		return -1; }

		$type	= gettype($src);
		// float or double -> float
		if ($type === "float")	{ $dst = $src;			return 0; }
		if ($type === "double")	{ $dst = $src;			return 0; }

		$l_cnv	= null;
		// string -> float
		$l_cnv	= ctype_digit($src) ? floatval($src) : null;
		//

		if (vEmpty::is($l_cnv)) 	{ vEmpty::set($dst);		return -1; }
		//
		$dst	= $l_cnv;
		return 0;
	}

	public static function toString($src,&$dst) {
		if (vUnspecified::is($src))	{ vUnspecified::set($dst);	return -1; }
		if (vEmpty::is($src))		{ vEmpty::set($dst);		return -1; }

		$type	= gettype($src);
		// string -> string
		if ($type === "string")		{ $dst = $src;			return 0; }

		$l_cnv	= null;
		// -> string
		$l_cnv	= "$src";
		//

		if (vEmpty::is($l_cnv)) 	{ vEmpty::set($dst);		return -1; }
		//
		$dst	= $l_cnv;

		return 0;
	}
} // CLASS-EOF
?>
