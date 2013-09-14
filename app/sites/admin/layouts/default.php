<script type="text/javascript" src="<?= XBRAND_BASE_URL ?>/includes/xbrander_admin.js"></script>

<div id="xbrand_admin">

	<h1 style="margin: 5px;"><img src="<?= XBRAND_BASE_URL ?>/includes/images/xbrander_logo.png" width="225" height="40" alt="xBrander" title="xBrander" border="0" /><span style="font-size: 12px;"> v<?php echo XBRAND_VERSION ?></span></h1>

	<table id="xbrand_layout_table" style="width: 800px;">
	<tr>
		<td>
			<ul id="xbrand_nav_tabs">
				<li<?php if (self::$action == 'files' || self::$action == 'file_attributes'){ echo ' class="selected"'; } ?>><a href="admin.php?page=<?= self::$name ?>&action=files">Files</a></li>
				<li<?php if (self::$action == 'settings'){ echo ' class="selected"'; } ?>><a href="admin.php?page=<?= self::$name ?>&action=settings">Settings</a></li>
			</ul>
			<div id="xbrand_view_wrapper">
				<div id="xbrand_view_box">
					<div style="margin: 15px;">
					<?php require($view_path); ?>
					</div>
				</div>
				<div id="xbrand_spacer"></div>
				<div style="clear: both;"></div>
			</div>
		</td>
	</tr>
	</table>

</div>