<?php
//======================================================
//
// 機能名：	if JSON Class.
//
// 機能ID：	ifJSON.php
// 継承  ：	none
// 概要  ：	if JSON class.
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

class ifJSON extends ifCore
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
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));
		//debug_log(print_r($pq,True));
		//debug_log(print_r($pp,True));
		//debug_log(print_r($pr,True));

		$v_obj			= new Smarty();
		$v_obj->caching		= 0;
		$v_obj->debugging	= false;
		$v_obj->cache_lifetime	= 120;
		$v_obj->template_dir	= TMP_DIR;
		$v_obj->compile_dir	= CMP_DIR;

		// head value
		debug_log(print_r($err,True));
		$v_obj->assign("head",$err);

		// data value
		$out_arr		= array();
		$out_arr		= $out->getData();

		debug_log(print_r($out_arr,True));
		$v_obj->assign("data",$out_arr);


		// output
		header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );

		$v_obj->display($this->name . "_" . $this->func . ".tpl");

		debug_log("<< 1") ;
		return 1;
	}

	//public function action($attr,$in,&$out,&$err,$pq=null,$pp=null,$pr=null)

	public function main()
	{
		debug_log(">>()") ;
		$out	= null;
		$err	= array();
		$in	= null;
		$pq	= array();
		$pp	= array();
		$pr	= array();
		$attr	= null;
		$json	= null ;

		//
		// PARAM
		//
		//foreach ( $_SERVER as $k =>$v ){	debug_log("SERVER $k:$v");	}
		if ( ! isset( $_SERVER['REQUEST_URI'] ) )
		{
			return ;
		}
		$p_param	= $_SERVER['REQUEST_URI'];
		//echo "REQUEST_URI:$p_param\n";

		$p_name	= null ;
		$p_func	= null ;

		//
		// INPUT
		//
		// [get]
		if ( preg_match( "/\/lab1\/lib\/api\/member\/([^\/]+)\/([^\/]+)\/([0-9]+)/",$p_param,$match ) )
		{
			$p_name	= $match[1];
			$p_func	= $match[2];
			$attr	= $match[3];

			debug_log("MATCH get($p_name:$p_func:$attr)") ;
		}
		// [query]
		// /lab1/lib/api//member/person/list/query?q=%7B%0A%20%22isConverted%22:%20true%0A%7D&p=%7B%0A%20%22lines%22:%201000,%0A%20%22page%22:%200,%0A%20%22sortKey%22:%20%5B%0A%20%20%22recid%22%0A%20%5D,%0A%20%22sortDir%22:%20%22%22%0A%7D&r=1
		else if ( preg_match( "/\/lab1\/lib\/api\/member\/([^\/]+)\/([^\?]*)\/(query)\?/",$p_param,$match ) )
		{
			$p_name	= $match[1];
			$p_func	= $match[2];
			$attr	= $match[3];
		 	foreach ( $_GET as $k =>$v )
			{
				debug_log("GET $k:$v");

				if ($k == 'q')	  {  $pq	= json_decode($v);	}
				if ($k == 'p')	  {  $pp	= json_decode($v);	}
				if ($k == 'r')	  {  $pr	= json_decode($v);	}
			}
			debug_log("MATCH query($p_name:$p_func:$attr)") ;
		}
		// other
		else if ( preg_match( "/\/lab1\/lib\/api\/member\/([^\/]+)\/([^\?]*)\/([^\?]*)/",$p_param,$match ) )
		{
			$p_name	= $match[1];
			$p_func	= $match[2];
			$attr	= $match[3];
			$json	= json_decode(file_get_contents('php://input'),true);

			debug_log("MATCH other($p_name:$p_func:$attr)") ;
			debug_log(print_r($json,True));
		}

		//
		// CLASS
		//
		$this->name	= $p_name;
		$this->func	= $p_func;
		require_once(API_DIR . API_CGI_API . $p_name . API_CGI_EXT);
		require_once(APD_DIR . API_CGI_APD . $p_name . API_CGI_EXT);
		$w_class	= API_CGI_API . $this->name ;
		$this->api	= new $w_class();

		$w_class	= API_CGI_APD . $this->name ;
		$in		= new $w_class();
		$out		= new $w_class();
		if ( $json != null )
		{
			if ( $this->func == 'upd' )
			{
				$in->convertUpdateData($json);
			}
			else
			{
				$in->convertData($json);
			}
		}

		//
		// ACTION
		//
		$rtn = $this->action($attr,$in,$out,$err,$pq,$pp,$pr) ;

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
			debug_log(print_r($e,True));

			$v_obj			= new Smarty();
			$v_obj->caching		= 0;
			$v_obj->debugging	= false;
			$v_obj->cache_lifetime	= 120;
			$v_obj->template_dir	= TMP_DIR;
			$v_obj->compile_dir	= CMP_DIR;

			// head value
			$v_obj->assign("head",array('status'=>'FAILED','message'=>'failed'));

			$v_obj->display("Error.tpl");
		}
	}
);
// EXEC
$obj = new ifJSON();
$obj->main();
?>