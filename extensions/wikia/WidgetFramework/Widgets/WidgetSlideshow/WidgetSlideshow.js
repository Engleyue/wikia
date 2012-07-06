// widget init (on add / on load)
function WidgetSlideshow_init(id, widget) {
	$.loadJQuerySlideshow(function() {
		// preload images
		$('#widget_' + id + '-images').find('li').each( function() {
			$(this)
				.css('backgroundImage', 'url(' + $(this).attr('title') + ')')
				.removeAttr('title');
		});

		// initialize slideshow
		$('#widget_' + id + '_content').slideshow({
			slidesClass:	'WidgetSlideshowImages',
			buttonsClass:	'WidgetSlideshowControls',
			nextClass:	'WidgetSlideshowControlNext',
			prevClass:	'WidgetSlideshowControlPrev',
			pauseClass:	'WidgetSlideshowControlPause',
			startClass:	'WidgetSlideshowControlPlay',
			blockedClass:	'blocked',
			slideWidth:	'186px'
		});

		$().log('#widget_' + id + ' initialized', 'Slideshow');
	});
}

// called after widget settings are changed
function WidgetSlideshow_after_edit(id, widget) {
	WidgetSlideshow_init(id, widget);
}
