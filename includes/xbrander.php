<?php

if (is_array($attrib))
{
	$plugin_url = Xbrand_Base::$plugin_url;

	$result = '<div class="xb_download_button" style="position: relative;"><form id="xbrander_form_'.(int)$file['file_id'].'" class="xbrander_form"><input type="hidden" name="file" value="'.$file['file_key'].'" />';

	$row = -1;

	foreach ($attrib as $field)
	{
		if ($field['search'])
		{
			$row++;

			$result .= '<input type="hidden" name="search_'.$row.'" value="'.addslashes($field['search']).'" />';

			$replace = $field['replace'];

			if ((int)$field['form_entry'])
			{
				switch ($field['type'])
				{
					case 'shortcode':
						break;

					case 'text':
						$replace = htmlspecialchars($replace);
						break;

					case 'php':
						$replace = eval($replace);
						break;
				}

				$result .= '<div class="xb_form_row"><label>'.htmlspecialchars($field['label']).':</label><input type="text" name="replace_'.$row.'" value="'.$replace.'" /></div>';
			}
			else
			{
				$result .= '<input type="hidden" name="replace_'.$row.'" value="'.$replace.'" />';
			}
		}
	}

	$plugin_url = plugin_dir_url(__FILE__);

	$result .= '<div class="xb_button_row"><input type="button" value="Download PDF" /><img src="'.$plugin_url.'icons/loader-ball.gif" id="xb_loader_ball" width="32" height="32" alt="Preparing your PDF..." title="Preparing your PDF..." style="display: none; border: none; border-radius: 0; box-shadow: 0 0 0; vertical-align: middle;" /></div></form></div>';
}

$result = str_replace("\n", '', $result);