var WikiaPhotoGallerySlider = {
	//timer for automatic wikiaPhotoGallery slideshow
	timer: null,

	//ID of image that will be shown on load, integer, 0-3
	initialImageId: 0, //Math.floor(Math.random() * 4),

	sliderId: null,

	log: function(msg) {
		$().log(msg, 'WikiaPhotoGallery:Slider');
	},

	init: function(sliderId) {
		this.sliderId = sliderId;

		//move spotlights
		$('.wikiaPhotoGallery-slider-body').each(function() {
			$(this).css('left', parseInt($(this).css('left')) - (620 * WikiaPhotoGallerySlider.initialImageId));
		});

		//select nav
		$('#wikiaPhotoGallery-slider-' + sliderId + '-' + WikiaPhotoGallerySlider.initialImageId).find('.nav').addClass('selected');

		//show description
		$('#wikiaPhotoGallery-slider-' + sliderId + '-' + WikiaPhotoGallerySlider.initialImageId).find('.description').css('display','block');
		$('#wikiaPhotoGallery-slider-' + sliderId + '-' + WikiaPhotoGallerySlider.initialImageId).find('.description-background').css('display','block');

		//bind events
		$('#wikiaPhotoGallery-slider-body-' + sliderId + ' .nav').click(function() {
			if ( $('#wikiaPhotoGallery-slider-body-' + sliderId + ' .wikiaPhotoGallery-slider').queue().length == 0 ){
				clearInterval(WikiaPhotoGallerySlider.timer);
				WikiaPhotoGallerySlider.scroll($(this));
			}
		});

		$('#wikiaPhotoGallery-slider-body-' + sliderId).css('display', 'block');

		this.timer = setInterval(this.slideshow, 7000);
	},

	scroll: function(nav) {
		//setup variables
		var thumb_index = nav.parent().prevAll().length;
		var scroll_by = parseInt(nav.parent().find('.wikiaPhotoGallery-slider').css('left'));
		var slider_body = nav.closest('.wikiaPhotoGallery-slider-body');
		var parent_id = slider_body.attr('id');

		if ($('#' + parent_id + ' .wikiaPhotoGallery-slider').queue().length == 0) {

			//set 'selected' class
			$('#' + parent_id + ' .nav').removeClass('selected');
			nav.addClass('selected');

			//hide description
			$('#' + parent_id + ' .description').clearQueue().hide();

			//scroll
			$('#' + parent_id + ' .wikiaPhotoGallery-slider').animate({
				left: '-=' + scroll_by
			}, function() {
				slider_body.find( '.wikiaPhotoGallery-slider-' + thumb_index ).find( '.description' ).fadeIn();
			});
		}
	},

	slideshow: function() {
		var current = $('#wikiaPhotoGallery-slider-body-' + WikiaPhotoGallerySlider.sliderId + ' .selected').parent().prevAll().length;
		var next = ( ( current == $('#wikiaPhotoGallery-slider-body-' + WikiaPhotoGallerySlider.sliderId + ' .nav').length - 1 ) || ( current > 3 ) ) ? 0 : current + 1;
		WikiaPhotoGallerySlider.scroll($('#wikiaPhotoGallery-slider-' + WikiaPhotoGallerySlider.sliderId + '-' + next).find('.nav'), WikiaPhotoGallerySlider.sliderId);
	}
}