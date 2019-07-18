<?php
//======================================================
//
// 機能名：	if Core Class.
//
// 機能ID：	ifCore.php
// 継承  ：	none
// 概要  ：	if core class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

//require_once("../../.config.php");

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

class ifCore
{
	public	$name	= null ;
	public	$func	= null ;
	public	$api	= null ;

	function __construct()
	{
		debug_log(">>()") ;
		debug_log("<<") ;
	}

	public function pre($rtn,$attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)
	{
		debug_log(">>($rtn,$attr)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		debug_log("<< 1") ;
		return 1;
	}

	public function exec($rtn,$attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)
	{
		debug_log(">>($rtn,$attr)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		$ret = ($this->api)->{$this->func}($attr,$in,$out,$err,$pq,$pp,$pr);

		debug_log("<< $ret") ;
		return $ret ;
	}

	public function post($rtn,$attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)
	{
		debug_log(">>($rtn,$attr)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		debug_log("<< 1") ;
		return 1;
	}

	public function action($attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)
	{
		debug_log(">>($attr)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		$rtn	= 1;
		$rtn = $this->pre($rtn,$attr,$in,$out,$err,$pq,$pp,$pr) ;
		debug_log("<< (pre:$rtn)") ;

		$rtn = $this->exec($rtn,$attr,$in,$out,$err,$pq,$pp,$pr) ;
		debug_log("<< (pre:$rtn)") ;

		$rtn = $this->post($rtn,$attr,$in,$out,$err,$pq,$pp,$pr) ;
		debug_log("<< (pre:$rtn)") ;

		debug_log("<<") ;
		return ;
	}
}
?>
