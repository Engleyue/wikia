var WikiaPhotoGallerySlideshow = {
	id: null,
	hash: null,

	log: function(msg) {
		$().log(msg, 'WikiaPhotoGallery:Slideshow');
	},

	init: function(params) {
		var slideshow = $('#' + params.id);
		var hash = slideshow.attr('hash');
		this.id = params.id;
		this.hash = hash;

		var item = slideshow.find('li').first();
		if (item.attr('title') != '') {
			item.css('backgroundImage', 'url(' + item.attr('title') + ')');
		}
		item.removeAttr('title');

		slideshow.slideshow({
			buttonsClass: 'wikia-button',
			nextClass: 'wikia-slideshow-next',
			prevClass: 'wikia-slideshow-prev',
			slideWidth: params.width,
			slidesClass: 'wikia-slideshow-images',
			slideCallback: function(index) {
				var item = slideshow.find('li').eq(index);
				if (item.attr('title')) {
					item.css('backgroundImage', 'url(' + item.attr('title') + ')');
					item.removeAttr('title');
				}
			}
		});

		// handle clicks on "Pop Out" button
		slideshow.find('.wikia-slideshow-popout').click(this.onPopOutClickFn);

		// handle clicks on slideshow images
		slideshow.find('.wikia-slideshow-images a').click(this.onPopOutClickFn);

		// handle clicks on "Add Image"
		slideshow.find('.wikia-slideshow-addimage').click(function(e) {
			WikiaPhotoGalleryView.loadEditorJS(function() {
				// tracking
				WikiaPhotoGalleryView.track('/slideshow/basic/addImage');

				WikiaPhotoGallery.ajax('getGalleryData', {hash:hash, title:wgPageName}, function(data) {
					if (data && data.info == 'ok') {
						data.gallery.id = params.id;
						WikiaPhotoGallerySlideshow.log(data.gallery);
						WikiaPhotoGallery.showEditor({
							from: 'view',
							gallery: data.gallery,
							target: $(e.target).closest('.wikia-slideshow')
						});
					} else {
						WikiaPhotoGallery.showAlert(
							data.errorCaption,
							data.error
						);
					}
				});
			});
		});

		// update counter
		slideshow.bind('slide', function(ev, data) {
			var counter = slideshow.find('.wikia-slideshow-toolbar-counter');
			counter.text( counter.attr('value').replace(/\$1/, 1 + data.currentSlideId) );
		});

		// track clicks on prev / next
		slideshow.bind('onPrev', function() {
			WikiaPhotoGalleryView.track('/slideshow/basic/previous');
		});

		slideshow.bind('onNext', function() {
			WikiaPhotoGalleryView.track('/slideshow/basic/next');
		});

		// on-hover effects
		slideshow.find('.wikia-slideshow-images').bind({
			'mouseover': function(ev) {
				$(this).addClass('hover');
			},
			'mouseout': function(ev) {
				$(this).removeClass('hover');
			}
		});

		// hide "Add photo" button when not in view mode
		if (!WikiaPhotoGalleryView.isViewPage()) {
			slideshow.find('.wikia-slideshow-addimage').hide();
		}

		// show slideshow toolbar
		slideshow.find('.wikia-slideshow-toolbar').show();

		this.log('#' + params.id + ' initialized');
	},

	onPopOutClickFn: function(ev) {
		var node = $(this),
			slideshow = node.closest('wikia-slideshow');

		// stop slideshow animation
		slideshow.trigger('stop');

		// if user clicked on slideshow image, open popout on this image (index)
		var nodeId = node.attr('id');
		var index = nodeId ? parseInt(nodeId.split('-').pop()) : 0;

		var isFromFeed = node.parent().hasClass('wikia-slideshow-from-feed');

		// tracking
		var fakeUrl = '/slideshow/basic';

		if (node.hasClass('wikia-slideshow-popout')) {
			// zoom icon clicked
			fakeUrl += '/popout';
		}
		else {
			// slideshow image clicked
			if (node.attr('href') && !isFromFeed) {
				// linked image
				fakeUrl += '/imageClick/link';
			}
			else {
				fakeUrl += '/imageClick/popout';
			}
		}

		WikiaPhotoGalleryView.track(fakeUrl);

		// linked image - leave here
		if (node.attr('href') && !isFromFeed) {
			return;
		}

		if (isFromFeed) {
			//every image in feed slideshow has href - ctrl+click will lead to that page but by default - display popup
			ev.preventDefault();
		}

		// load popout
		WikiaPhotoGalleryView.loadEditorJS(function() {
			WikiaPhotoGallery.showSlideshowPopOut(WikiaPhotoGallerySlideshow.id, WikiaPhotoGallerySlideshow.hash, index, WikiaPhotoGalleryView.isViewPage(), isFromFeed);
		});
	}
};