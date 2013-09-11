	<h2>Settings</h2>

	<form action="admin.php?page=xbrander&action=settings_save" method="POST">
<?php

if (!count(self::$errors))
{
	self::$form_vars['api_key']	= get_option('xbrand_api_key');
}

Xbrand_Form::fadeSave();

Xbrand_Form::startTable();

Xbrand_Form::listErrors(self::$errors);

Xbrand_Form::text('API Key', 'form_vars[api_key]', self::$form_vars['api_key'], NULL, 'Copy and paste your API Key from the xBrander dashboard area.<br /><a href="http://xbrander.com/dashboard/" target="_blank">http://xbrander.com/dashboard/</a>');

?>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="button-primary action" value="Save" />
			</td>
		</tr>
	</table>

	</form>
