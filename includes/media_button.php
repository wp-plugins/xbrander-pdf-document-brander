<?php

if (!defined('XBRAND_CURRENT_PAGE'))
{
	define('XBRAND_CURRENT_PAGE', basename($_SERVER['PHP_SELF']));
}

function Xbrand_formButton()
{
	$is_post_edit_page = in_array(XBRAND_CURRENT_PAGE, array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
	if (!$is_post_edit_page)
	{
		return;
	}

	echo '
	<style>
		.xbrand_popup_h3 {
			color: #FFF;
			font-size: 14px;
			font-family: verdana, arial, helvetica;
			padding: 3px 5px;
			border-radius: 4px;
			border: 1px solid #000;
			background-color: #006;
		}
		.xbrand_edit_icon {
			background: url(' . plugins_url('/icons/xbrander-icon.png', __FILE__) . ') no-repeat top left;
			display: inline-block;
			height: 16px;
			margin: 3px 2px 0 0;
			vertical-align: text-top;
			width: 16px;
			float: left;
		}
		.wp-core-ui a.xbrand_edit_link {
			padding-left: 0.4em;
		}
		#xbrand_search_results {
			border: 1px solid #777;
			height: 200px;
			overflow: auto;
			border-radius: 4px;
		}
		.xbrand_search_result {
			padding: 5px 8px;
			border-bottom: 1px solid #CCC;
		}
		.xbrand_search_result:hover {
			background-color: #FFC;
			cursor: pointer;
		}
	</style>
	<a href="#TB_inline?width=500&height=600&inlineId=xbrand_form_popup" class="thickbox button xbrand_edit_link" id="add_gform">
		<span class="xbrand_edit_icon"></span>
		xBrander
	</a>';
}

function Xbrand_formPopup()
{
	global $wpdb;

	include_once XBRAND_APP_PATH.'/Xbrand_Admin.php';
	Xbrand_Admin::init();
	
	$file_list = $wpdb->get_results('SELECT * FROM '.Xbrand_Admin::$table['file'].' ORDER BY title', ARRAY_A);
?>
<script>
function xbrand_searchVideo()
{
	jQuery('#xbrand_loader_ball').show();
	jQuery('#xbrand_search_results').load('admin.php?page=mn_video&action=video_search&search=' + jQuery('#xbrand_search_string').val(), null, function(){ jQuery('#xbrand_loader_ball').hide(); });
}

function xbrand_insertFile(shortcode)
{
	window.send_to_editor(shortcode);
	tb_remove();
}
</script>

<div id="xbrand_form_popup" style="display: none;">
	<div class="wrap" style="height: 600px;">
		<div style="height: 600px;">
			<div style="padding: 15px 15px 0 15px;">
				<h3 class="xbrand_popup_h3">Select a File...</h3>
				<div id="xbrand_search_results" style="margin-top: 3px; clear: both;">
<?php
foreach ($file_list as $file)
{
	echo '<div class="xbrand_search_result" onclick="xbrand_insertFile(\'[xbrander file='.$file['file_key'].']\')">'.$file['title'].'</div>';
}
?>
				</div>
			</div>
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div style="clear: both;"></div>
</div>
	<?php
}

add_action('media_buttons', 'Xbrand_formButton', 25);

if (in_array(XBRAND_CURRENT_PAGE, array('post.php', 'page.php', 'page-new.php', 'post-new.php')))
{
	add_action('admin_footer', 'Xbrand_formPopup');
}