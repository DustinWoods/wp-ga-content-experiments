
jQuery(document).ready( function($) {
	var $postExperimentMetaBox = $('#wpgacxm-meta-box'),
		postId = $('#post_ID').val() || 0;

	var format_percent = function(dec) {
		return Math.round(parseFloat(dec)*10000)/100+"%";
	};

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


	$('.edit-area input[type=range]').each(function(i) {
		var $adjacentLabel = $(this).parent().children('.range-display');
		$adjacentLabel = $adjacentLabel[0] ? $($adjacentLabel[0]) : false;
		if($adjacentLabel) {
			$(this).on('mousemove',function(e) {
				$adjacentLabel.text(format_percent($(this).val()));
			});
		}
	});

	//Handles clicking editable settings in meta boxes
	$postExperimentMetaBox.find('.edit-experiment-prop .edit-link').click( function( event ) {
		var $editarea = $(this).parent().find('.edit-area'),
			$textedit = $(this),
			$input = $editarea.find('input, select, textarea').first();

		$editarea.show();

		$input.focus();
		if($input.prop('tagName').toLowerCase == 'select') {
			$input.click();
		}

		var save_value = function() {
			var newvalue = $input.val();
			var type = (typeof $input.attr('type') != 'undefined' ? $input.attr('type') : '');
			switch (($input.prop('tagName')+type).toLowerCase()) {
				case 'inputrange':
					if($input.attr('data-format') == 'percent') {
						newvalue = format_percent($input.val());
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
			var close_it = function() {
				$editarea.hide();
				save_value();
				$('html').off('click.editing');
				$input.off('change.editing');
			};
			$('html').one('click.editing',close_it);
			$input.one('change.editing',close_it);
		},0)
		event.preventDefault();
	});
	$postExperimentMetaBox.find('.edit-experiment-prop .edit-area').blur( function( event ) {
		$this.hide();
	});
});