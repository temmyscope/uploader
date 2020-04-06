<?php
namespace Seven\Globals;

class Server
{
	
	public function __call($method, $args)
	{
		return $_SERVER[ strtoupper($method) ] ?? null;
	}

	public function get($key)
	{
		return $_SERVER[$key];
	}

	public static function uagent_no_version(){
		return preg_replace('/\/[a-zA-Z0-9.]*/', '', getenv('HTTP_USER_AGENT') );
	}
}