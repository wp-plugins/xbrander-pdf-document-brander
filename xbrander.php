<?php
/*
Plugin Name: xBrander - PDF document brander
Plugin URI: http://xBrander.com
Description: Let your affiliates download your reports, branded with their affiliate links and text
Author: xBrander
Author URI: http://xBrander.com
Tags: docx, pdf, branded, affiliate, membership
Requires at least: 3.0
Tested up to: 3.6
Stable tag: stable
License: GPLv2 or later
Version: 1.5
 */

/**

Copyright 2013 Summit Media Concepts LLC (email : admin@SummitMediaConcepts.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

define('XBRAND_VERSION',	'1.5');

define('XBRAND_PATH',		dirname(__FILE__));
define('XBRAND_APP_PATH',	XBRAND_PATH.'/app');

$upload_dir = wp_upload_dir();

define('XBRAND_BASE_URL',		plugin_dir_url(__FILE__));
define('XBRAND_UPLOAD_PATH',	rtrim($upload_dir['basedir'], '/').'/brandable_pdfs/');

if (is_admin())
{
	// Do admin stuff

	function Xbrand_loadPlugin()
	{
		if ($_REQUEST['page'] == 'xbrander')
		{
			include_once XBRAND_APP_PATH.'/Xbrand_Admin.php';
			Xbrand_Admin::init();
			//Xbrand_Admin::upgradePlugin();
		}
	}

	function Xbrand_displayView()
	{
		include_once XBRAND_APP_PATH.'/Xbrand_Admin.php';
		Xbrand_Admin::displayView();
	}

	function Xbrand_addAdminMenu()
	{
		add_menu_page('xBrander', 'xBrander', 'update_core', 'xbrander', 'Xbrand_displayView', XBRAND_BASE_URL.'/includes/icons/xbrander-icon.png');
	}

	function Xbrand_activatePlugin()
	{
		include_once XBRAND_APP_PATH.'/Xbrand_Admin.php';
		Xbrand_Admin::init();
		Xbrand_Admin::upgradePlugin();
	}

	function Xbrand_enqueueScript()
	{
		wp_enqueue_script('jquery-form');
	}

	function Xbrand_admin_notice()
	{
		$notice = get_option('xbrand_notification');

		if ($notice)
		{
			echo '<div class="updated"><p>'.$notice.'</p></div>';
		}
	}

	add_action('admin_notices', 'Xbrand_admin_notice');
	add_action('wp_enqueue_scripts', 'Xbrand_enqueueScript');
	add_action('wp_loaded', 'Xbrand_loadPlugin', 1);
	add_action('admin_menu', 'Xbrand_addAdminMenu');
	register_activation_hook(__FILE__, 'Xbrand_activatePlugin' );

	require XBRAND_PATH.'/includes/media_button.php';
}
else
{
	// Do public stuff

	function Xbrand_addToHead()
	{
		$ajax_url	= admin_url('admin-ajax.php');

		echo '
<link rel="stylesheet" href="'.XBRAND_BASE_URL.'/includes/style_public.css" type="text/css" media="all" />
<script src="'.XBRAND_BASE_URL.'/includes/xbrander_public.js"></script>
<script type="text/javascript">
var xbrand_ajax_url = "'.$ajax_url.'";
var xbrand_plugin_url = "'.XBRAND_BASE_URL.'";
</script>
		';
	}

	add_action('wp_head', 'Xbrand_addToHead');

	function Xbrand_handleShortcode2($content)
	{
		if (preg_match_all('/\[xbrander file=([0-9a-z]+)\]/is', $content, $match_list))
		{
			if (isset($match_list[0]) && count($match_list[0]))
			{
				global $wpdb;

				include_once XBRAND_APP_PATH.'/Xbrand_Base.php';
				Xbrand_Base::initBase();

				foreach ($match_list[0] as $i => $short_code)
				{
					$result = '';

					$file_key = $match_list[1][$i];

					$file = $wpdb->get_row('SELECT * FROM '.Xbrand_Base::$table['file'].' WHERE file_key = "'.addslashes($file_key).'"', ARRAY_A);

					if (@$file['file_id'])
					{
						$attrib = unserialize($file['attrib']);

						$file_path = XBRAND_PATH.'/includes/xbrander.php';

						if (is_file($file_path))
						{
							require $file_path;
						}
					}

					$content = str_replace($short_code, $result, $content);
				}
			}
		}

		return $content;
	}

	add_filter('the_content', 'Xbrand_handleShortcode2', 0);

	function Xbrand_enqueueScripts()
	{
		wp_enqueue_script('jquery');
	}

	add_action('wp_enqueue_scripts', 'Xbrand_enqueueScripts');
}

function Xbrand_getFileUrl()
{
	require_once XBRAND_APP_PATH.'/Xbrand_Ajax.php';
	Xbrand_Ajax::init();

	Xbrand_Ajax::doAction('file_url');
}

add_action('wp_ajax_xbrand_get_file_url', 'Xbrand_getFileUrl');
add_action('wp_ajax_nopriv_xbrand_get_file_url', 'Xbrand_getFileUrl');