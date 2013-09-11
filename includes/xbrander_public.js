function Xbrand_mask(obj)
{
	obj.append('<div class="xbrand_mask" style="position: absolute; top: 0; background-color: #EEE; border: 1px solid #CCC; height: ' + obj.height() + 'px; width: ' + obj.width() + 'px; text-align: center;"><img src="' + xbrand_plugin_url + 'includes/icons/loader-ball.gif" width="32" height="32" alt="" style="margin-top: ' + Math.floor((obj.height() - 32) / 2) + 'px;" /></div>');
}

function Xbrand_unmask(obj)
{
	obj.find('.xbrand_mask').remove();
}

jQuery(document).ready(function() {

	jQuery('.xbrander_form').each(function() {
		var theForm = jQuery(this);
		var theDiv = theForm.parent();

		theForm.find('input:button').click(function(e) {
			//jQuery('#xb_loader_ball').show();
			Xbrand_mask(theDiv);

			jQuery.ajax({
				type : 'POST',
				url : xbrand_ajax_url,
				data : {
					'action' : 'xbrand_get_file_url',
					'data' : theForm.serializeArray()
				},
				dataType : 'json',
				cache : false,
				complete: function(){
					//jQuery('#xb_loader_ball').hide();
					Xbrand_unmask(theDiv);
				},
				success : function(data) {
					if (data.file_url == null || data.file_url == '')
					{
						alert('We\'re sorry!  There was a problem.  Please try again.');
						Xbrand_unmask(theDiv);
					}
					else
					{
						window.location.href = data.file_url;
					}
				}
			});
		});
	});
});