<?php

class Xbrand_Form
{
	public static $return = 'echo';

	public static $tr_class = '';


	public static function hidden($name, $value)
	{
		$snippet = '<input type="hidden" id="'.self::makeId($name).'" name="'.$name.'" value="'.htmlspecialchars($value).'" />'."\n";

		return self::returnSnippet($snippet);
	}

	public static function text($label, $name, $value, $size = 50, $caption = '', $required = FALSE)
	{
		if (!intval($size)){ $size = 50; }

		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		$class = '';
		if (self::$tr_class)
		{
			$class = ' class="'.self::$tr_class.'"';
		}

		$snippet = '<tr id="'.self::makeId($name).'_tr"'.$class.'>'."\n";
		$snippet .= "\t".'<th valign="top">'.$label.':</th>'."\n";
		$snippet .= "\t".'<td>'."\n";
		$snippet .= "\t\t".'<input type="text" id="'.self::makeId($name).'" name="'.$name.'" value="'.htmlspecialchars($value).'" size="'.$size.'" onchange="smcn_form_changed = true;" />'."\n";

		if (strlen(trim($caption)))
		{
			$snippet .= "\t\t".'<div class="caption">'.$caption.'</div>'."\n";
		}
		$snippet .= "\t".'</td>'."\n";
		$snippet .= '</tr>'."\n\n";

		return self::returnSnippet($snippet);
	}

	public static function checkbox($label, $name, $value, $caption = '', $required = FALSE)
	{
		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		$snippet = "\n".'<tr><th>'.$label.':</th><td>'."\n";
		$checked = '';
		if (intval($value))
		{
			$checked = ' checked';
		}
		$snippet .= '<input type="checkbox" id="'.self::makeId($name).'" name="'.$name.'" value="'.$value.'"'.$checked.' onchange="this.checked ? this.value = 1 : this.value = 0;" />';

		if (strlen(trim($caption)))
		{
			$snippet .= '<div class="caption">'.$caption.'</div>'."\n";
		}
		$snippet .= '</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function radio($label, $name, $value, $caption = '', $required = FALSE)
	{
		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		$snippet = "\n".'<tr><th>'.$label.':</th><td>'."\n";

		$checked = '';
		if (intval($value))
		{
			$checked = ' checked';
		}

		$snippet .= '<input type="radio" id="'.self::makeId($name).'" name="'.$name.'" value="'.$value.'"'.$checked.' onchange="this.checked ? this.value = 1 : this.value = 0;" />';

		if (strlen(trim($caption)))
		{
			$snippet .= '<div class="caption">'.$caption.'</div>'."\n";
		}
		$snippet .= '</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function textarea($label, $name, $value, $rows = 3, $cols = 40, $caption = '', $max_length = NULL, $required = FALSE)
	{
		if (!intval($rows)){ $row = 3; }
		if (!intval($cols)){ $cols = 40; }

		if (intval($max_length)){ $max_length = ' maxlength="'.$max_length.'"'; }

		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		if (self::$tr_class)
		{
			$class = ' class="'.self::$tr_class.'"';
		}

		$snippet = '<tr id="'.self::makeId($name).'_tr"'.$class.'>'."\n";
		$snippet .= "\t".'<th valign="top" style="vertical-align: top;">'.$label.':</th>'."\n";
		$snippet .= "\t".'<td>'."\n";

		$snippet .= "\t\t".'<textarea id="'.self::makeId($name).'" name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'"'.$max_length.' onchange="smcn_form_changed = true;">'.htmlspecialchars($value).'</textarea>';
		if (strlen(trim($caption)))
		{
			$snippet .= "\t\t".'<div class="caption">'.$caption.'</div>'."\n";
		}
		$snippet .= "\t".'</td>'."\n";
		$snippet .= '</tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function file($label, $name, $value = '', $size = 50, $caption = '', $required = FALSE)
	{
		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		$snippet = "\n".'<tr><th valign="top">'.$label.':</th><td>'."\n";
		$snippet .= '<input type="file" id="'.self::makeId($name).'" name="'.$name.'" value="'.htmlspecialchars($value).'" size="'.$size.'" />';

		if (strlen(trim($caption)))
		{
			$snippet .= '<div class="caption">'.$caption.'</div>'."\n";
		}
		$snippet .= '</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function select($label, $name, $value, $list, $caption = '', $required = FALSE)
	{
		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		$snippet = "\n".'<tr><th>'.$label.':</th><td>'."\n";
		$snippet .= '<select name="'.$name.'" id="'.self::makeId($name).'" onchange="smcn_form_changed = true;">';

		if (is_array($list))
		{
			foreach ($list as $key => $opt_value)
			{
				$selected = '';
				if (trim($key) == trim($value))
				{
					$selected = ' selected';
				}
				$snippet .= '<option value="'.$key.'"'.$selected.'>'.$opt_value.'</option>';
			}
		}
		$snippet .= '</select>';

		if (strlen(trim($caption)))
		{
			$snippet .= '<div class="caption">'.$caption.'</div>'."\n";
		}
		$snippet .= '</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function datetime($label, $name, $value, $year = 0, $caption = NULL)
	{
		if ($required)
		{
			$label = '<span class="required">*</span>'.$label;
		}

		$snippet = "\n".'<tr><th>'.$label.':</th><td>'."\n";
			
		if ((string)$value != (string)intval($value))
		{
			$value = strtotime($value);
		}

		if (!$value)
		{
			$value = time();
		}

		$selected_month		= date('n', $value);
		$selected_day		= date('d', $value);
		$selected_year		= date('Y', $value);
		$selected_hour		= date('G', $value);
		$selected_minute	= date('i', $value);

		$snippet .= '<select name="'.$name.'[month]">';
		for ($i = 1; $i <= 12; $i++)
		{
			$selected = '';
			if ($selected_month == $i)
			{
				$selected = ' selected';
			}

			$snippet .= '<option value="'.$i.'"'.$selected.'>'.strftime('%B', mktime(12, 0, 0, $i ,1)).'</option>';
		}
		$snippet .= '</select>';

		$snippet .= '<select name="'.$name.'[day]">';
		for ($i = 1; $i <= 31; $i++)
		{
			$selected = '';
			if ($selected_day == $i)
			{
				$selected = ' selected';
			}

			$snippet .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
		}
		$snippet .= '</select>';

		$snippet .= '<select name="'.$name.'[year]">';
		for ($i = date('Y', time()); $i >= $year; $i--)
		{
			$selected = '';
			if ($selected_year == $i)
			{
				$selected = ' selected';
			}

			$snippet .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
		}
		$snippet .= '</select>&nbsp;&nbsp;&nbsp;';

		$snippet .= '<select name="'.$name.'[hour]">';
		for ($i = 0; $i <= 23; $i++)
		{
			if ($i == 0)
			{
				$a = 12;
			}
			else if ($i > 12)
			{
				$a = $i - 12;
			}
			else
			{
				$a = $i;
			}

			if ($i > 11)
			{
				$a .= ' pm';
			}
			else
			{
				$a .= ' am';
			}

			$selected = '';
			if (date('H',$value) == $i)
			{
				$selected = ' selected';
			}

			$snippet .= '<option value="'.$i.'"'.$selected.'>'.$a.'</option>';
		}
		$snippet .= '</select>';

		$snippet .= '<select name="'.$name.'[minute]">';
		for ($i = 0; $i <= 59; $i++)
		{
			$selected = '';
			if (date('i',$value) == $i)
			{
				$selected = ' selected';
			}

			$snippet .= '<option value="'.$i.'"'.$selected.'>'.sprintf('%02d', $i).'</option>';
		}
		$snippet .= '</select>';

		if (strlen(trim($caption)))
		{
			$snippet .= '<div class="caption">'.$caption.'</div>'."\n";
		}

		$snippet .= '</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function startTable($class = NULL)
	{
		if (!$class)
		{
			$class = 'xbrand_form_table';
		}

		$snippet = '<table class="'.$class.'" cellpadding="0" cellspacing="5" width="100%">';

		return self::returnSnippet($snippet);
	}

	public static function fadeSave()
	{
		$snippet = '';

		if (isset($_GET['saved']) && $_GET['saved'])
		{
			$snippet .= '<div id="xbrand_saved">Saved</div>';
			$snippet .= '<script language="Javascript">xbrand_fade("xbrand_saved");</script>';
		}

		return self::returnSnippet($snippet);
	}

	public static function listErrors($error_list)
	{
		$snippet = '';

		if (is_array($error_list) && count($error_list))
		{
			$snippet .= "\n".'<tr><td colspan="2">';
			$snippet .= '<ul class="xbrand_error_list">';
			foreach ($error_list as $msg)
			{
				$snippet .= '<li>'.$msg.'</li>';
			}
			$snippet .= '</ul>';
			$snippet .= '</td></tr>'."\n";
		}

		return self::returnSnippet($snippet);
	}

	public static function label($label, $value)
	{
		$snippet = "\n".'<tr><th>'.$label.':</th><td style="vertical-align: middle;">'.$value.'</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function requiredMessage()
	{
		$snippet = "\n".'<tr><td>&nbsp;</td><td style="text-align: right;"><span class="required">*</span>Required fields</td></tr>';

		return self::returnSnippet($snippet);
	}

	public static function blankRow($text = '&nbsp;')
	{
		$snippet = "\n".'<tr><th>&nbsp;</th><td>'.$text.'</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function clearRow($text = '&nbsp;')
	{
		if (self::$tr_class)
		{
			$class = ' class="'.self::$tr_class.'"';
		}

		$snippet = '<tr'.$class.'><td colspan="2">'.$text.'</td></tr>'."\n";

		return self::returnSnippet($snippet);
	}

	public static function section($title)
	{
		if (self::$tr_class)
		{
			$class = ' class="'.self::$tr_class.'"';
		}

		$snippet .= '<tr'.$class.'><td colspan="2"><h3 class="xbrand_section ui-widget-header ui-corner-all">'.$title.'</h3></td></tr>';

		return self::returnSnippet($snippet);
	}

	public static function endTable()
	{
		$snippet = '</table>';

		return self::returnSnippet($snippet);
	}

	public static function makeOptionList($list, $id, $value)
	{
		$result = array();

		foreach ($list as $record)
		{
			$result[$id] = $value;
		}

		return $result;
	}

	public static function makeId($name)
	{
		$name = preg_replace('/[^a-z0-9]+/is', '_', strtolower($name));
		$name = str_replace('__', '_', $name);
		$name = str_replace('__', '_', $name);
		$name = trim($name, '_');

		return $name;
	}

	public static function returnSnippet($snippet)
	{
		if (self::$return == 'value')
		{
			return $snippet;
		}
		else
		{
			echo $snippet;
		}
	}
}