$(function() {
	WikiBuilder.init();

	ThemeDesigner.themeTabInit();
});

// ThemeDesigner.js overwrites
ThemeDesigner.init = function() {};
ThemeDesigner.set = function(setting, newValue) {
	var t = themes[newValue];
	var owb = '/__sass/extensions/wikia/WikiBuilder/css/WikiBuilder.scss/3337777333333/';
	var sass = '/__sass/skins/oasis/css/oasis.scss/3337777333333/';
	var params = '';
	params += 'color-body=' + escape(t['color-body']);
	params += '&color-page=' + escape(t['color-page']);
	params += '&color-buttons=' + escape(t['color-buttons']);
	params += '&color-links=' + escape(t['color-links']);
	params += '&background-image=' + encodeURIComponent(t['background-image']);
	params += '&background-align=' + escape(t['background-align']);
	params += '&background-tiled=' + escape(t['background-tiled']);
	$('.ThemeDesignerSASS').addClass('remove');
	$('<style class="ThemeDesignerSASS">').appendTo('head').load(sass + params, function() {
		$('.ThemeDesignerSASS.remove').remove();
		$('.WikiBuilderSASS').addClass('remove');
		$('<style class="WikiBuilderSASS">').appendTo('head').load(owb + params, function() {
			$('.WikiBuilderSASS.remove').remove();
		});
	});
	ThemeDesigner.settings = t;
};

var WikiBuilder = {

	init: function() {
		Mediawiki.pullArticleContent(Mediawiki.followRedirect(wgMainpage), WikiBuilder.pullWikiDescriptionCallback, {'rvsection': 1});
		var tracking_prepend = 'wikibuilder/step';
		$('.dialog .step1 input.save').click(function(e){
			e.preventDefault();
			WikiBuilder.saveDescription();
			$.tracker.byStr(tracking_prepend + '1/save');
		});
		$('.dialog .step1 input.skip').click(function(e){
			WikiBuilder.transition(1, 2);
			$.tracker.byStr(tracking_prepend + '1/skip');
		});
		$('.dialog .step2 input.save').click(function(e){
			WikiBuilder.saveTheme();
			$.tracker.byStr(tracking_prepend + '2/save');
		});
		$('.dialog .step2 input.skip').click(function(e){
			WikiBuilder.transition(2, 3);
			$.tracker.byStr(tracking_prepend + '2/skip');
		});
		$('.dialog .step3 input.save').click(function(e){
			WikiBuilder.savePages();
			$.tracker.byStr(tracking_prepend + '3/save');
		});
		$('.dialog .step3 input.skip').click(function(e){
			WikiBuilder.transition(3, 4);
			$.tracker.byStr(tracking_prepend + '3/skip');
		});
		$('.dialog .step4 input.basic').click(function(e){
			window.location.href = redirect;
			$.tracker.byStr(tracking_prepend + '4/save');
		});
		$('.dialog .step4 input.plus').click(function(e){
			WikiBuilder.upgradeToWikiaPlus.call(this);
			$.tracker.byStr(tracking_prepend + '4/skip');
		});

		$('.dialog .step3 input[type=text]').blur(WikiBuilder.pageNameExpansion);
	},

	pageNameExpansion: function() {
		var inputs = $('.dialog .step3 input[type=text]');
		if (inputs.length < 100) {
			var total = 0;
			inputs.each(function(i, el) {
				total = $(el).val() ? total + 1 : total;
			});
			if (total >= (inputs.length - 1)) {
				$(".dialog .step3 form nav").before('<input type="text"><input type="text"><input type="text"><input type="text"><input type="text">');
				$('.dialog .step3 input[type=text]').unbind('blur').blur(WikiBuilder.pageNameExpansion);
			}
		}
	},

	pullWikiDescriptionCallback: function (result) {
        var rg = new RegExp('={2,3}[^=]+={2,3}');

		var match = result.match(rg);
		if (match === null){
			$('#Description').attr('disabled', true);
			WikiBuilder.message(WikiBuilder.msg('owb-unable-to-edit-description'));
		} else {
			// Preserve the existing heading (=== blah ===) , we will tack it on when saving
			WikiBuilder.originalHeading = match[0];
			var text = result.replace(match, '');
			$('#Description').val(jQuery.trim(text));
		}
	},

	saveDescription: function () {
		$('.dialog input').attr('disabled', 'disabled');
		WikiBuilder.message(WikiBuilder.msg('owb-status-saving'), true);
		try {
			var rawtext = $('#Description').val();
			// Strip leading spaces and add original heading
			var text = (WikiBuilder.originalHeading ? WikiBuilder.originalHeading + '\n' : '') + rawtext.replace(new RegExp('^[ \t]+', 'gm'), '');
			// Save the article
			var mainPageEnd = Mediawiki.followRedirect(window.wgMainpage, false); // Should be cached.
			Mediawiki.editArticle({
					'title': mainPageEnd,
					'summary': '',
					'section': 1,
					'text': text
				},
				function(result){
					var cresult = Mediawiki.checkResult(result);
					if (cresult !== true) {
						if (result.error.code == 'readonly'){
							WikiBuilder.message(WikiBuilder.msg('owb-readonly-try-again'), true);
						} else {
							WikiBuilder.apiFailed(null, result.error.info, null);
						}
					} else {
						WikiBuilder.transition(1, 2);
					}
				},
				WikiBuilder.apiFailed
			);
		} catch (e) {
			/*
			Mediawiki.waitingDone();
			Mediawiki.debug(Mediawiki.print_r(e));
			*/
		}
	},

	saveTheme: function () {
		WikiBuilder.message(WikiBuilder.msg('owb-status-saving'), true);
		$('.dialog nav input').attr('disabled', 'disabled');
		ThemeDesigner.save();
		WikiBuilder.transition(2, 3);
	},

	savePages: function() {
		WikiBuilder.message(WikiBuilder.msg('owb-status-saving'), true);
		$('.dialog nav input').attr('disabled', 'disabled');
		try {
			// get titles
			var p = [];
			$('#Pages input[type=text]').each(
				function(i, o){
					var t = o.value.replace(/\|/, '');
					if ( !Mediawiki.e(t) ) {
						p.push(t);
					}
				}
			);
			if (p && p.length > 0) {
				p.reverse();
				pagetext = WikiBuilder.msg('owb-new-pages-text');
				Mediawiki.apiCall(
					{
						'action': 'createmultiplepages',
						'pagelist': p.join('|'),
						'pagetext': pagetext,
						'category': [],
						'type': ''
					},
					function(result) {
						var cresult = Mediawiki.checkResult(result);
						if (cresult !== true) {
							WikiBuilder.message(WikiBuilder.msg('owb-error-saving-articles'));
						} else {
							WikiBuilder.transition(3, 4);
						}
					},
					WikiBuilder.apiFailed,
					'POST'
				);
			} else {
				WikiBuilder.transition(3, 4);
			}
		} catch (e) {
		}
	},

	upgradeToWikiaPlus: function() {
		var throbber = $(this).next('.throbber');
		throbber.show();
		$.getJSON(wgScript,
			{
				'action': 'ajax',
				'rs': 'WikiBuilderAjax',
				'method': 'upgradeToPlus'
			},
			function(res) {
				throbber.hide();
				if (res.status == 'ok') {
					location.href = res.data.url;
				} else {
					$.showModal(res.caption, res.content);
				}
			}
		);
	},

	transition: function(from, to) {
		$('.steps .step' + from).animate({opacity: 0.5}, 'slow', function() {
			$('.steps .chevron').detach().appendTo('.steps .step' + to);
			$('.steps .step' + to).animate({opacity: 1}, 'slow');
		});
		$('.dialog .step' + from).hide('slow', function() {
			$('.dialog .status').html('');
			$('.dialog .step' + to).show('slow', function() {
				$('.dialog input').removeAttr('disabled');
			});
		});
	},

	apiFailed: function(reqObj, msg, error) {
		$().log('API request has failed', 'WikiBuilder');

		// show generic dialog when API request fails / timeouts (BugID: 1702)
		if (!msg) {
			$.showModal(WikiBuilder.msg('owb-api-error-title'), WikiBuilder.msg('owb-api-error'), {id: 'WikiBuilderError', width: 500});

			// unblock disabled buttons and hide progress indicator
			var nav = $('#WikiBuilder > .dialog nav');
			nav.children('.status').html('');
			nav.children('input').attr('disabled', false);

			// track API problems
			$.tracker.byStr('wikibuilder/apiFailed');
		}
		else if (typeof msg == 'object') {
			msg = Mediawiki.print_r(msg);
		}
	},

	message: function(msg, waiting) {
		$('.dialog .status').text(msg);
		if (waiting) {
			$('.dialog .status').append($('.ajaxwait').clone().show());
		}
	},

	msg: function(msg){
		var ret;
		try {
			ret = messages[msg];
		} catch(e) {
			ret = msg;
		}
		return ret;
	}
}