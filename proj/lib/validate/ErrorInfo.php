<?php
require_once(VALID_DIR ."Reason.php");

class ErrorInfo
{
	public	$errors	= array();

	function isValid()
	{
		return count($this->errors);
	}

	function hasErrors()
	{
		return Reason::isError($this->errors);
	}

	function addError($what, $how, $why, ...$params)
	{
		$this->errors[] = new Reason($what, $how, $why, Reason::ERROR, $params);
	}

	function addWarning($what, $how, $why, ...$params)
	{
		$this->errors[] = new Reason($what, $how, $why, Reason::WARNIG, $params);
	}

	function addInfo($what, $how, $why, ...$params)
	{
		$this->errors[] = new Reason($what, $how, $why, Reason::INFO, $params);
	}

} // CLASS-EOF
?>
