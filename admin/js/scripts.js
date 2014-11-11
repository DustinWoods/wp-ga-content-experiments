
jQuery(document).ready( function($) {
	var $postExperimentMetaBox = $('#wpgacxm-meta-box'),
		postId = $('#post_ID').val() || 0;

	$postExperimentMetaBox.find('.create-experiment').click( function( event ) {
		$.post(ajaxurl, {
				action: 'wpgacxm-create-experiment',
				post_id: postId
			}, function(response) {
			if(response != 0) {
				location.reload();
			} else {
				alert('Error creating experiment');
			}
		});
		event.preventDefault();
	});
	$postExperimentMetaBox.find('.edit-experiment-prop .edit-link').click( function( event ) {
		var $editarea = $(this).parent().find('.edit-area'),
			$textedit = $(this),
			$input = $editarea.find('input, select, textarea').first();

		$editarea.show();

		var save_value = function() {
			var newvalue = $input.val();
			var type = (typeof $input.attr('type') != 'undefined' ? $input.attr('type') : '');
			switch (($input.prop('tagName')+type).toLowerCase()) {
				case 'inputrange':
					if($input.attr('data-format') == 'percent') {
						newvalue = (parseFloat($input.val())*100)+"%";
					}
					break;

				case 'select':
					newvalue = $input.find('option[value='+newvalue+']').text();
					break;
			};
			$textedit.find('span').text(newvalue);
		};

		//close edit window when complete
		setTimeout(function() {
			$editarea.click(function(ee) {
				ee.stopPropagation();
			});
			$('html').one('click',function() {
				$editarea.hide();
				save_value();
			});
		},0)
		event.preventDefault();
	});
	$postExperimentMetaBox.find('.edit-experiment-prop .edit-area').blur( function( event ) {
		$this.hide();
	});
});