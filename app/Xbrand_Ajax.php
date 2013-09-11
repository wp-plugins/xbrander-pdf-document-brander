<?php

require_once 'Xbrand_Base.php';

class Xbrand_Ajax extends Xbrand_Base
{

	public static function init()
	{
		self::initBase();

		define('XBRAND_SITE_PATH',	XBRAND_APP_PATH.'/sites/ajax');
	}

	public static function doAction($action)
	{
		global $wpdb;

		$file_path = XBRAND_SITE_PATH.'/default.php';

		if (is_file($file_path))
		{
			require $file_path;
		}

		$file_path = XBRAND_SITE_PATH.'/'.$action.'.php';

		if (is_file($file_path))
		{
			require $file_path;
		}
	}
}