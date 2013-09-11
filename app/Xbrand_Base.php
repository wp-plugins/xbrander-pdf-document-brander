<?php

class Xbrand_Base
{
	public static $name			= 'xbrander';

	public static $action		= NULL;

	public static $form_vars	= array();

	public static $errors		= array();

	public static $table		= array();

	public static $context		= NULL;

	public static $plugin_url	= NULL;


	public static function initBase()
	{
		if (!self::$plugin_url)
		{
			global $wpdb;

			self::$table = array(
			'file'	=> $wpdb->base_prefix.'xbrand_file'
			);

			self::$plugin_url = XBRAND_BASE_URL;
		}
	}

	public static function getUserId()
	{
		global $current_user;

		return $current_user->ID;
	}

	public static function updateFileList()
	{
		global $wpdb;

		$url = 'http://xbrander.com/api.php?action=file_list';

		$args = array('body' => array('api_key' => get_option('xbrand_api_key'), 'version' => XBRAND_VERSION));

		$file_list = $wpdb->get_results('SELECT file_id, attrib FROM '.self::$table['file'].' ORDER BY file_id', ARRAY_A);

		if ($file_list)
		{
			foreach ($file_list as $file)
			{
				$args['body']['file_list'][$file['file_id']] = $file;
			}
		}

		$result = wp_remote_post($url, $args);

		$response = json_decode($result['body'], true);

		$file_id_list = array(0);

		delete_option('xbrand_notification');

		if ($response['success'] == TRUE && is_array($response['file_list']) && count($response['file_list']))
		{
			foreach ($response['file_list'] as $file)
			{
				$file_id_list[] = $file['file_id'];

				$o_file = $wpdb->get_row('SELECT * FROM '.self::$table['file'].' WHERE file_id = '.(int)$file['file_id'], ARRAY_A);

				if ($o_file['file_id'])
				{
					$new = FALSE;
				}
				else
				{
					$new = TRUE;
					$o_file = array();
				}

				foreach ($file as $field => $value)
				{
					$o_file[$field] = $value;
				}

				if (!$new)
				{
					$wpdb->update(self::$table['file'], $o_file, array('file_id' => $o_file['file_id']));
				}
				else
				{
					$wpdb->insert(self::$table['file'], $o_file);
				}
			}

			$wpdb->query('DELETE FROM '.self::$table['file'].' WHERE file_id NOT IN ('.implode(',', $file_id_list).')');
		}
		else
		{
			if (@$response['error_message'])
			{
				update_option('xbrand_notification', $response['error_message']);
			}
		}
	}

	public static function makeUrl($vars = NULL)
	{
		$this_url = 'http://';
		if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')
		{
			$this_url = 'https://';
		}

		$path_parts = explode('?', $_SERVER['REQUEST_URI']);
		$this_url .= $_SERVER['HTTP_HOST'].$path_parts[0];
		parse_str($_SERVER['QUERY_STRING'], $query);

		$query['app'] = self::$name;

		$anchor = '';

		if (is_array($vars))
		{
			foreach ($vars as $name => $value)
			{
				if ($name == '#')
				{
					$anchor = '#'.$value;
				}
				else
				{
					if (strlen($value))
					{
						$query[$name] = $value;
					}
					else
					{
						unset($query[$name]);
					}
				}
			}
		}

		return $this_url.'?'.http_build_query($query).$anchor;
	}
}