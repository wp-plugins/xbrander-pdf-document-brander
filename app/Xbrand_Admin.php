<?php

require_once 'Xbrand_Base.php';

class Xbrand_Admin extends Xbrand_Base
{


	public static function init()
	{
		self::initBase();

		define('XBRAND_SITE_PATH',	XBRAND_APP_PATH.'/sites/admin');

		add_action('admin_init',	array('Xbrand_Admin', 'doBeforeHeaders'), 1);
		add_action('admin_head',	array('Xbrand_Admin', 'addToHead'));
	}

	public static function doBeforeHeaders()
	{
		if ($_GET['page'] == self::$name)
		{
			global $wpdb;

			if (isset($_POST['form_vars']))
			{
				$_POST['form_vars'] = self::array_stripslashes($_POST['form_vars']);

				self::$form_vars = $_POST['form_vars'];
			}

			if (isset($_GET['action']))
			{
				self::$action = preg_replace('/[^0-9a-zA-Z\_\-]+/is', '', strtolower($_GET['action']));
			}

			if (!self::$action)
			{
				self::$action = 'files';
			}

			$file_path = XBRAND_SITE_PATH.'/actions/default.php';

			if (is_file($file_path))
			{
				require $file_path;
			}

			$file_path = XBRAND_SITE_PATH.'/actions/'.self::$action.'.php';

			if (is_file($file_path))
			{
				require $file_path;
			}
		}
	}

	public static function addToHead()
	{
		echo '<link rel="stylesheet" href="'.XBRAND_BASE_URL.'/includes/style_admin.css" type="text/css" media="all" />';
	}

	public static function displayView()
	{
		global $wpdb;

		require XBRAND_APP_PATH.'/Xbrand_Form.php';

		$layout_path = XBRAND_SITE_PATH.'/layouts/default.php';

		if (is_file($layout_path))
		{
			$view_path = XBRAND_SITE_PATH.'/views/'.self::$action.'.php';

			if (!is_file($view_path))
			{
				exit('Invalid View: '.$view_path);
			}

			require($layout_path);
		}
		else
		{
			exit('Invalid Layout: '.$layout_path);
		}
	}

	public static function upgradePlugin()
	{
		global $wpdb;

		if (file_exists(ABSPATH.'/wp-admin/upgrade-functions.php'))
		{
			require_once(ABSPATH.'/wp-admin/upgrade-functions.php');
		}
		else
		{
			require_once(ABSPATH.'/wp-admin/includes/upgrade.php');
		}

		dbDelta('CREATE TABLE `'.self::$table['file'].'` (
			`file_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) unsigned NOT NULL,
			`file_key` varchar(32) NOT NULL,
			`title` varchar(255) NOT NULL,
			`summary` text,
			`description` mediumtext,
			`aff_signup_url` text,
			`show_in_directory` tinyint(1) unsigned NOT NULL,
			`original_file_name` varchar(255) NOT NULL,
			`attrib` text,
			`created` int(11) unsigned NOT NULL,
			`modified` int(11) unsigned NOT NULL,
			PRIMARY KEY (`file_id`)
		);');
	}

	public static function array_stripslashes($array)
	{
		if (is_array($array))
		{
			foreach ($array as $field => $value)
			{
				if (is_array($value))
				{
					$array[$field] = self::array_stripslashes($value);
				}
				else
				{
					$array[$field] = stripslashes($value);
				}
			}
		}

		return $array;
	}
}