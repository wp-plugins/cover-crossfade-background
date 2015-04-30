var $ = jQuery.noConflict();

$(document).on('click', '.coverthumb', function() {
	
	var val = $('[name="coverbginput"]').val();
	val = val.split(',');
	var imgs = Array();
	$.each(val, function(index, value) {
		imgs.push(value);
	});
	
    var img = $(this).attr('name');
    img = img.split('_');
    img = img[1];
    
	if ($(this).is('.selected')) {
		$('[name="thumbs_' + img + '"]').removeClass('selected');

		if ($.inArray(img, imgs) !== -1) {
			imgs.splice($.inArray(img, imgs),1);
			console.log(imgs);
			$('[name="coverbginput"]').val(imgs.join(','));
			
		}

	} else {
		$('[name="thumbs_' + img + '"]').addClass('selected');
		if ($.inArray(img, imgs) == -1) {
			imgs.push(img);
			$('[name="coverbginput"]').val(imgs.join(','));
		}
		
		
	}

});

$(document).ready(function() {
	var val = $('[name="coverbginput"]').val();
	
	
	val = val.split(',');

	var imgs = Array();

	$.each(val, function(index, value) {
		imgs.push(value);
		$('[name="thumbs_' + value + '"]').addClass('selected');
	});
	
	


});

