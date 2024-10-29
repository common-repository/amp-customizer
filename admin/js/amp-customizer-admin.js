(function( $ ) {

	$(function() {
		$('.amp-color-field').wpColorPicker();
	});

})( jQuery );

jQuery(function($){
	var frame,
		fieldContainer = $('.image-field-container'),
		delImgLink = $('.amp-media-remove');

	delImgLink.addClass('hidden');

	fieldContainer.each(function(){
		if($(this).find('.image-preview').length){
			$(this).find('.amp-media').addClass('hidden');
			$(this).find('.amp-media-remove').removeClass('hidden');
		}
	});

	$('.amp-media').on( 'click', function( event ) {
		event.preventDefault();

		var container = $(this).data('container'),
			imgContainer = $('#' + container),
			imgIdInput = imgContainer.find('.image-field-hidden'),
			addImgLink = imgContainer.find('.amp-media'),
			delImgLink = imgContainer.find('.amp-media-remove');

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media({
			multiple: false
		});

		frame.on( 'select', function() {
			var selection = frame.state().get('selection');
			var attachment = frame.state().get('selection').first().toJSON();

			console.log('selection made!');
			console.log(attachment.url);

			imgContainer.prepend( '<img class="image-preview" src="'+attachment.url+'" style="max-width:75px; margin-right: 5px;"/>' );
			imgIdInput.val( attachment.id );
			addImgLink.addClass( 'hidden' );
			delImgLink.removeClass( 'hidden' );
		});

		frame.open();
	});
	
	delImgLink.on( 'click', function( event ){

		var container = $(this).data('container'),
			imgContainer = $('#' + container),
			imgIdInput = imgContainer.find('.image-field-hidden'),
			addImgLink = imgContainer.find('.amp-media'),
			delImgLink = imgContainer.find('.amp-media-remove');

		event.preventDefault();

		imgContainer.find('.image-preview').remove();
		addImgLink.removeClass( 'hidden' );
		delImgLink.addClass( 'hidden' );
		imgIdInput.val( '' );

	});

});
