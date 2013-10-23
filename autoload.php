<?php
include_once __DIR__."/vendor/autoload.php";

$GLOBALS["TESTCASE_DEBUG"] = false;

function debugOn(){
	$GLOBALS["TESTCASE_DEBUG"] = true;
}

function debugOff(){
	$GLOBALS["TESTCASE_DEBUG"] = false;
}

function _d(){
	if( $GLOBALS["TESTCASE_DEBUG"])
	{
		$args = func_get_args();
		$args[0] .= "\n";
		foreach ($args as &$arg) {
			if(is_array($arg))
				$arg = join(', ', $arg);
		}
		return call_user_func_array('printf', $args);
	}
}


/**
 * Dump Functions
 */

function du(){
	foreach (func_get_args() as $a)
		var_dump($a);
}

function dd(){
	foreach (func_get_args() as $a)
		var_dump($a);
	die();
}