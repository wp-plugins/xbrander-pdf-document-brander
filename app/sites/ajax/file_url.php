<?php

$errors = array();

$response = array(
'success'	=> TRUE
);

$data = array('api_key' => get_option('xbrand_api_key'), 'version' => XBRAND_VERSION);

if (is_array($_REQUEST['data']))
{
	foreach ($_REQUEST['data'] as $row)
	{
		$temp = explode('_', $row['name']);

		if (count($temp) > 1)
		{
			$data['attrib'][$temp[1]][$temp[0]] = $row['value'];
		}
		else
		{
			$data[$row['name']] = $row['value'];
		}
	}
}

$url = 'http://xbrander.com/api.php?action=file_url';

$args = array(
'timeout' => 120,
'body' => $data
);

$result = wp_remote_post($url, $args);

$body = json_decode($result['body'], true);

if (!$body['file_url'])
{
	$response = array(
	'success'	=> TRUE,
	'error'		=> 'Unable to prepare file for download'
	);
}
else
{
	$response['file_url'] = $body['file_url'];
}

exit(json_encode($response));