var WikiaPhotoGallerySlider = {
	//timer for automatic wikiaPhotoGallery slideshow
	timer: null,
	//sliderEnable: true,

	sliderId: null,

	log: function(msg) {
		console.log(msg, 'WikiaPhotoGallery:Slider');
	},

	init: function(sliderId) {
		this.sliderId = sliderId;
		var 	slider = $('#wikiaPhotoGallery-slider-body-' + sliderId ),
			initialImageId = 0, //for now always first
			initialSlider = $('#wikiaPhotoGallery-slider-' + sliderId + '-' + initialImageId),
			image = initialSlider.find('a img');
		
		//select nav
		initialSlider.find('.nav').addClass('selected');

		//show description
		initialSlider.find('.description').addClass('visible');
		
		//load image
		image.show().attr('src', image.data('src'));
		
		//bind events
		slider.delegate('.nav', 'click', function() {
				WikiaPhotoGallerySlider.scroll($(this))
		});
	},

	scroll: function(nav) {
		//setup variables
		var 	parentNav = nav.parent(),
			image = parentNav.find('img'),
			imageData = image.data('src'),
			slider = parentNav.parents('.wikiaPhotoGallery-slider-body');

		//set 'selected' class
		slider.find('.nav').removeClass('selected');
		nav.addClass('selected');

		//show relevant description
		slider.find('.description').removeClass('visible');
		parentNav.find('.description').addClass('visible');
		
		//show relevant img
		slider.find('a img').hide();
		image.show();
		if( imageData && imageData != image.attr('src')) {
			console.log("asd");
			image.attr('src', imageData);			
		}

	}
}