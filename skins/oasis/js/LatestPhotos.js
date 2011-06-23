$(function() {
    LatestPhotos.init();
    UploadPhotos.init();
});

var UploadPhotos = {
	d: false,
	destfile: false,
	filepath: false,
	doptions: {persistent: false, width:600},
	status: false,
	libinit: false,
	init: function() {
		if(!($(".upphotoslogin").exists())) {
			$(".upphotos").click(UploadPhotos.showDialog);
		}
	},
	showDialog: function(evt) {
		if(evt) {
			evt.preventDefault();
		}
		$.get(wgScript, {
			action: 'ajax',
			rs: 'moduleProxy',
			moduleName: 'UploadPhotos',
			actionName: 'Index',
			outputType: 'html',
			title: wgPageName,
			cb: wgCurRevisionId,
			uselang: wgUserLanguage
		}, function(html) {
			// pre-cache dom elements
			UploadPhotos.d = $(html).makeModal(UploadPhotos.doptions);
			UploadPhotos.destfile = UploadPhotos.d.find("input[name=wpDestFile]");
			UploadPhotos.filepath = UploadPhotos.d.find("input[name=wpUploadFile]");
			UploadPhotos.status = UploadPhotos.d.find("div.status");
			UploadPhotos.advanced = UploadPhotos.d.find(".advanced a");
			UploadPhotos.advancedChevron = UploadPhotos.d.find(".advanced .chevron");
			UploadPhotos.options = UploadPhotos.d.find(".options");
			UploadPhotos.uploadbutton = UploadPhotos.d.find("input[type=submit]");
			UploadPhotos.step1 = UploadPhotos.d.find(".step-1");
			UploadPhotos.step2 = UploadPhotos.d.find(".step-2");
			UploadPhotos.conflict = UploadPhotos.d.find(".conflict");
			UploadPhotos.ignore = UploadPhotos.d.find("input[name=wpIgnoreWarning]");
			UploadPhotos.override = UploadPhotos.d.find(".override");
			UploadPhotos.overrideinput = UploadPhotos.override.find("input");
			UploadPhotos.ajaxwait = UploadPhotos.d.find(".ajaxwait");
			UploadPhotos.dfcache = {};

			// event handlers
			UploadPhotos.filepath.change(UploadPhotos.filePathSet);
			UploadPhotos.destfile.blur(UploadPhotos.destFileSet);
			UploadPhotos.advanced.click(function(evt) {
				evt.preventDefault();

				//set correct text for link and arrow direction
				if (UploadPhotos.options.is(":visible")) {
					UploadPhotos.advanced.text(UploadPhotos.advanced.data("more"));
					UploadPhotos.advancedChevron.removeClass("up");
				} else {
					UploadPhotos.advanced.text(UploadPhotos.advanced.data("fewer"));
					UploadPhotos.advancedChevron.addClass("up");
				}

				UploadPhotos.options.slideToggle("fast");
			});
			UploadPhotos.destfile.keyup(function() {
				if(UploadPhotos.dftimer) {
					clearTimeout(UploadPhotos.dftimer);
				}
				UploadPhotos.dftimer = setTimeout(UploadPhotos.destFileSet, 500);
			});
			$.tracker.byStr('action/uploadphoto/dialog');
		});
		if (!UploadPhotos.libinit) {
			$.getScript(wgExtensionsPath + "/wikia/ThemeDesigner/js/aim.js");	// TODO: find a permanent place for aim
			UploadPhotos.libinit = true;
		}
	},
	uploadCallback: {
		onComplete: function(res) {
			res = $("<div/>").html(res).text();
			var json = $.evalJSON(res);
			if(json) {
				if(json['status'] == 0) {	// 0 is success...
					$.tracker.byStr('action/uploadphoto/upload');
					window.location = wgArticlePath.replace('$1', 'Special:NewFiles');
				} else if(json['status'] == -2) {	// show conflict dialog
					UploadPhotos.step1.hide(400, function() {
						UploadPhotos.conflict.html(json['statusMessage']);
						UploadPhotos.step2.show(400, function() {
							UploadPhotos.uploadbutton.removeAttr("disabled").show();
							UploadPhotos.ajaxwait.hide();
						});
					});
					UploadPhotos.ignore.attr("checked", true);
				} else {
					UploadPhotos.status.addClass("error").show(400).html(json['statusMessage']);
					UploadPhotos.uploadbutton.removeAttr("disabled").show();
					UploadPhotos.ajaxwait.hide();
				}
			}
		},
		onStart: function() {
			UploadPhotos.uploadbutton.attr("disabled", "true").hide();
			UploadPhotos.ajaxwait.show();
			UploadPhotos.status.hide("fast", function() {$(this).removeClass("error")});
		}
	},
	filePathSet: function() {
		if (UploadPhotos.filepath.val()) {
			var filename = UploadPhotos.filepath.val().replace(/^.*\\/, '');
			UploadPhotos.destfile.val(filename);
			UploadPhotos.destFileSet();
		}
	},
	destFileSet: function() {
		if (UploadPhotos.destfile.val()) {
			var df = UploadPhotos.destfile.val();
			if (UploadPhotos.dfcache[df]) {
				UploadPhotos.destFileInputSet(UploadPhotos.dfcache[df]);
			} else {
				$.get(wgScript, {
					wpDestFile: UploadPhotos.destfile.val(),
					action: 'ajax',
					rs: 'moduleProxy',
					moduleName: 'UploadPhotos',
					actionName: 'ExistsWarning',
					outputType: 'html',
					title: wgPageName,
					cb: wgCurRevisionId,
					uselang: wgUserLanguage
				}, function(html) {
					UploadPhotos.dfcache[df] = html;
					UploadPhotos.destFileInputSet(html);
				});
			}
		}
	},
	destFileInputSet: function(html) {
		if(html && $.trim(html)) {
			UploadPhotos.override.fadeIn(400);
			UploadPhotos.status.removeClass("error").html(html).show(400);
		} else {
			UploadPhotos.override.fadeOut(400);
			UploadPhotos.overrideinput.attr("checked", false);
			UploadPhotos.status.removeClass("error").hide(400);
		}
	}
}

var LatestPhotos = {
	browsing: false,
	transition_speed: 500,
	enable_next: true,
	enable_previous: false,
	carousel: false,

	init: function() {
		LatestPhotos.carousel = $('.LatestPhotosModule').find('.carousel');
		LatestPhotos.attachListeners();
		//LatestPhotos.lazyLoadImages(3);
	},

	lazyLoadImages: function(limit) {
		//var firstInit = true;
		var images = this.carousel.find('img').filter('[data-src]');
		$().log('lazy loading rest of images', 'LatestPhotos');

		var count = 0;
		images.each(function() {
			count ++;
			if (count > limit) { // exit the loop for init image loading.
				return false;
			}
			//if ( ( firstInit == false  && count > LatestPhotos.initLoadedImages) || firstInit == true) {
				var image = $(this);
				image.
					attr('src', image.attr('data-src')).
					removeAttr('data-src');
			//}
		});

	},

	attachListeners: function() {
		LatestPhotos.attachBlindImages();
		$('.LatestPhotosModule .next').click(LatestPhotos.nextImage);
		$('.LatestPhotosModule .previous').click(LatestPhotos.previousImage);

		$(".LatestPhotosModule").one('mouseover', function() {
			LatestPhotos.lazyLoadImages('rest');
		});

		LatestPhotos.enableBrowsing();
		LatestPhotos.addLightboxTracking();
	},

	attachBlindImages: function() {
		if ($('.carousel li').length == 5) {
			$('.carousel').append("<li class='blind'></li>");
		}
		else if ($('.carousel li').length == 4) {
			$('.carousel').append("<li class='blind'></li>");
			$('.carousel').append("<li class='blind'></li>");
		}

		$('.carousel li').first().addClass("first-image");
		$('.carousel li').last().addClass("last-image");

	},

	previousImage: function() {
		var width = LatestPhotos.setCarouselWidth();

		LatestPhotos.enableBrowsing();

		if (LatestPhotos.browsing == false && LatestPhotos.enable_previous == true) {
			LatestPhotos.browsing = true;
			var images = $('.carousel li').length;
			for (i=0; i < 3; i++) {
				$('.carousel').prepend( $('.carousel li').eq(images -1) ) ;
			}
			$(".carousel-container div").css('left', - width + 'px');

			$(".carousel-container div").animate({
				left:  '0px'
			}, LatestPhotos.transition_speed, function() {
				LatestPhotos.browsing = false;
			});
		}
		return false;
	},

	nextImage: function() {
		var width = LatestPhotos.setCarouselWidth();

		LatestPhotos.enableBrowsing();

		if (LatestPhotos.browsing == false && LatestPhotos.enable_next == true) {
			LatestPhotos.browsing = true;
			$(".carousel-container div").animate({
				left: '-' + width
			}, LatestPhotos.transition_speed, function() {
				LatestPhotos.removeFirstPhotos();
				LatestPhotos.browsing = false;
			});
		}
		return false;
	},

	enableBrowsing: function() {
		var current = $('.carousel li').slice(0, 3).each(function (i) {
			if ($(this).is('.last-image')) {
				LatestPhotos.enable_next = false;
				return false;
			}
			else {
				LatestPhotos.enable_next = true;
			}

			if ($(this).is('.first-image')) {
				LatestPhotos.enable_previous = false;
				return false;
			}
			else {
				LatestPhotos.enable_previous = true;
			}
		});
	},

	// add extra tracking for lightbox shown for image from latest photos module (RT #74852)
	addLightboxTracking: function() {
		this.carousel.bind('lightbox', function(ev, lightbox) {
			$().log('lightbox shown', 'LatestPhotos');

			var fakeUrl = 'module/latestphotos/';
			var lightboxCaptionLinks = $('#lightbox-caption-content').find('a');

			// user name
			lightboxCaptionLinks.eq(0).trackClick(fakeUrl + 'lightboxusername');

			// page name
			lightboxCaptionLinks.filter('.wikia-gallery-item-posted').trackClick(fakeUrl + 'lightboxlink');

			// "more"
			lightboxCaptionLinks.filter('.wikia-gallery-item-more').trackClick(fakeUrl + 'lightboxmore');
		});
	},

	removeFirstPhotos: function() {
		var old = $('.carousel li').slice(0,3);
		$('.carousel-container div').css('left', '0px');
		$('.carousel li').slice(0,3).remove();
		$('.carousel').append(old);

	},

	setCarouselWidth: function() {
		var width = $(".carousel li").outerWidth() * 3 + 6;
		$('.carousel').css('width', width * $(".carousel li").length + 'px'); // all li's in one line
		return width;
	}
};