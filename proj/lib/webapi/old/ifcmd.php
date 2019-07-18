<?php
//======================================================
//
// 機能名：	if cmd Class.
//
// 機能ID：	ifcmd.php
// 継承  ：	none
// 概要  ：	if cmd class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once("../../.config.php");

require_once(TOP_DIR . "lib/smarty/libs/Smarty.class.php");

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

require_once(IF_DIR . "ifCore.php");

class ifcmd extends ifCore
{
	function __construct()
	{
		debug_log(">>()") ;
		parent::__construct();
		debug_log("<<") ;
	}

	//public function pre($rtn,$attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)

	//public function exec($rtn,$attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)

	public function post($rtn,$attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)
	{
		debug_log(">>($rtn,$attr)") ;

		// head value
		print("err\n");
		print_r($err);

		print("out\n");
		print_r($out);

		debug_log("<< 1") ;
		return 1;
	}

	//public function action($attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)

	public function main()
	{
		debug_log(">>()") ;
		$out	= array();
		$err	= array();
		$in	= array();
		$pq	= array();
		$pp	= array();
		$pr	= array();
		$attr	= null;

		//
		// PARAM
		//
		$argv = $_SERVER['argv'];
		if ( count($argv) < 4 )
		{
			echo "usage: name func attr (param...)\n";
			return;
		}
		$p_name	= $argv[1];
		$p_func	= $argv[2];
		$attr	= $argv[3];

		//
		// INPUT
		//
		for($i=4; count($argv) > 4 && $i<count($argv); $i++)
		{
			$p	= explode("=",$argv[$i]);
			if ( count($p) == 2 )
			{
				$in[ $p[0] ] = $p[1];
			}
			else
			{
				$in['recid'] = $p[0];
			}
		}

		//
		// CLASS
		//
		$this->name	= $p_name;
		$this->func	= $p_func;
		require_once(API_DIR . API_CGI_API . $p_name . API_CGI_EXT);
		$w_class	= API_CGI_API . $this->name ;
		$this->api	= new $w_class();

		$w_class	= API_CGI_APD . $this->name ;

		//
		// ACTION
		//
		$this->action($attr,$in,$out,$err,$pq,$pp,$pr) ;

		debug_log("<<") ;
		return ;
	}
}

// EXIT function
register_shutdown_function(
	function() {
		$e = error_get_last();
		if ( isset($e) )
		{
			// PHP error
			print_r($e,True);
		}
	}
);
// EXEC
$obj = new ifcmd();
$obj->main();
?>