var ActivityFeedTag = {};

// setup onclick events for image/video thumbnails
ActivityFeedTag.setupThumbnails = function(node) {
	$(node).find('.activityfeed-image-thumbnail').click(ActivityFeedTag.loadFullSizeImage);
	$(node).find('.activityfeed-video-thumbnail').click(ActivityFeedTag.loadVideoPlayer);
}

ActivityFeedTag.ajax = function(method, params, callback) {
	$.getJSON(wgScript + '?action=ajax&rs=MyHomeAjax&method=' + method, params, callback);
}


ActivityFeedTag.loadVideoPlayer = function(ev) {
	ev.preventDefault();

	var title = $(this).attr('title');

	// catch doubleclicks on video thumbnails
	if (ActivityFeedTag.videoPlayerLock) {
		return;
	}

	ActivityFeedTag.videoPlayerLock = true;

	ActivityFeedTag.ajax('getVideoPlayer', {'title': title}, function(res) {
		// replace thumbnail with video preview
		if (res.html) {
			// open modal
			title = title.replace(/_/g, ' ');
			$.getScript(stylepath + '/common/jquery/jquery.wikia.modal.js?' + wgStyleVersion, function() {
				var html = '<div id="myhome-video-player" title="' + title  +'">' + res.html + '</div>';
				$("#positioned_elements").append(html);
				$('#myhome-video-player').makeModal({
					'id': 'myhome-video-player-popup',
					'width': res.width
				});
			});

			// remove lock
			delete ActivityFeedTag.videoPlayerLock;
		}
	});
}


ActivityFeedTag.loadFullSizeImage = function(ev) {
	ev.preventDefault();

	var title = $(this).attr('title');
	var timestamp = $(this).attr('ref');

	timestamp = parseInt(timestamp) ? timestamp : 0;

	// catch doubleclicks on video thumbnails
	if (ActivityFeedTag.imagePreviewLock) {
		return;
	}

	ActivityFeedTag.imagePreviewLock = true;

	ActivityFeedTag.ajax('getImagePreview', {
		'title': title,
		'timestamp': timestamp,
		'maxwidth': $.getViewportWidth(),
		'maxheight': $.getViewportHeight()
	}, function(res) {
		// replace thumbnail with video preview
		if (res.html) {
			// open modal
			title = title.replace(/_/g, ' ');
			$.getScript(stylepath + '/common/jquery/jquery.wikia.modal.js?' + wgStyleVersion, function() {
				var html = '<div id="myhome-image-preview" title="' + title  +'">' + res.html + '</div>';
				$("#positioned_elements").append(html);
				$('#myhome-image-preview').makeModal({
					'id': 'myhome-image-preview-popup',
					'width': res.width
				});
			});

			// remove lock
			delete ActivityFeedTag.imagePreviewLock;
		}
	});
}

ActivityFeedTag.loadFreshData = function(id, params) {
	params = params.replace(/&amp;/g, '&');
	$.getJSON(wgScript + '?action=ajax&rs=ActivityFeedAjax', {params: params}, function(json){
		var tmpDiv = document.createElement('div');
		tmpDiv.innerHTML = json.data;
		$('#' + id).html($(tmpDiv).find('ul').html());
		ActivityFeedTag.setupThumbnails($('#' + id));
	});
}

ActivityFeedTag.addTracking = function(id) {
	$('#' + id).find('li').each(function(n) {
		$(this).find('strong').find('a').click( function(e) {
			WET.byStr('activityfeedtag/title');
		});
		$(this).find('cite').find('a').eq(0).click( function(e) {
			WET.byStr('activityfeedtag/user');
		});
		$(this).find('cite').find('a').eq(1).click( function(e) {
			WET.byStr('activityfeedtag/diff');
		});
	});
}

ActivityFeedTag.initActivityTag = function(id, params) {
	ActivityFeedTag.loadFreshData(id, params);
	ActivityFeedTag.addTracking(id);
}

wgAfterContentAndJS.push(function() {
	ActivityFeedTag.setupThumbnails($('.activityfeed'));
});
