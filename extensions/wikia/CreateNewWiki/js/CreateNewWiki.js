$(function() {
	wgAjaxPath = wgScriptPath + wgScript;
	WikiBuilder.init();
	$('#AjaxLoginButtons').hide();
	$('#AjaxLoginLoginForm').show();
	
	ThemeDesigner.slideByDefaultWidth = 608;
	ThemeDesigner.slideByItems = 4;
	ThemeDesigner.themeTabInit();
});

var WikiBuilder = {
	registerInit: false,
	wntimer: false,
	wdtimer: false,
	createStatus: false,
	createStatusMessage: false,
	themestate: false,
	cityId: false,
	finishCreateUrl: false,
	retryGoto: 0,
	init: function() {
		// pre-cache
		WikiBuilder.wb = $('#CreateNewWiki');
		WikiBuilder.steps = $('#CreateNewWiki .steps .step');
		WikiBuilder.loginEntities = $('#Auth .login-msg, #Auth .signup');
		WikiBuilder.signupEntities = $('#Auth .signup-msg, #Auth .login');
		WikiBuilder.wikiName = $('#NameWiki input[name=wiki-name]');
		WikiBuilder.wikiNameStatus = $('#NameWiki .wiki-name-status-icon');
		WikiBuilder.wikiNameError = $('#NameWiki .wiki-name-error');
		WikiBuilder.wikiDomain = $('#NameWiki input[name=wiki-domain]');
		WikiBuilder.wikiDomainError = $('#NameWiki .wiki-domain-error');
		WikiBuilder.wikiDomainStatus = $('#NameWiki .domain-status-icon');
		WikiBuilder.wikiDomainCountry = $('#NameWiki .domain-country');
		WikiBuilder.nameWikiSubmitError = $('#NameWiki .submit-error');
		WikiBuilder.wikiLanguage = $('#NameWiki select[name=wiki-language]');
		WikiBuilder.wikiCategory = $('#DescWiki select[name=wiki-category]');
		WikiBuilder.descWikiSubmitError = $('#DescWiki .submit-error');
		WikiBuilder.nextButtons = WikiBuilder.wb.find('nav .next');
		WikiBuilder.finishSpinner = $('#CreateNewWiki .finish-status');
		
		// Name Wiki event handlers
		$('#NameWiki input.next').click(function() {
			$.tracker.byStr('createnewwiki/namewiki/next');
			if (!WikiBuilder.wikiDomain.val() || !WikiBuilder.wikiName.val() || $('#NameWiki .wiki-name-error').html() || $('#NameWiki .wiki-domain-error').html()) {
				WikiBuilder.nameWikiSubmitError.show().html(WikiBuilderCfg['name-wiki-submit-error']).delay(3000).fadeOut();
			} else {
				WikiBuilder.saveState({
					wikiName: WikiBuilder.wikiName.val(),
					wikiDomain: WikiBuilder.wikiDomain.val(),
					wikiLang: WikiBuilder.wikiLanguage.find('option:selected').val()
				});
				if ($('#Auth')) {
					//AjaxLogin.init($('#AjaxLoginLoginForm form:first'));
					WikiBuilder.handleRegister();
				}
				if(onFBloaded) {  // FB hax
					onFBloaded();
				}
				WikiBuilder.transition('NameWiki', true, '+');
			}
		});
		WikiBuilder.wikiDomain.keyup(function() {
			if(WikiBuilder.wdtimer) {
				clearTimeout(WikiBuilder.wdtimer);
			}
			WikiBuilder.wdtimer = setTimeout(WikiBuilder.checkDomain, 500);
		});
		WikiBuilder.wikiName.keyup(function() {
			var name = $(this).val();
			name = name.replace(/[^a-zA-Z0-9]+/g, '').replace(/ /g, '');
			WikiBuilder.wikiDomain.val(name.toLowerCase()).trigger('keyup');
			if(WikiBuilder.wntimer) {
				clearTimeout(WikiBuilder.wntimer);
			}
			WikiBuilder.wntimer = setTimeout(WikiBuilder.checkWikiName, 500);
		});
		WikiBuilder.wikiLanguage.bind('change', function () {
			WikiBuilder.checkWikiName();
			WikiBuilder.checkDomain();
			var selected = WikiBuilder.wikiLanguage.find('option:selected').val();
			WikiBuilder.wikiDomainCountry.html((selected && selected !== 'en') ? selected + '.' : '');
		});
		$('#ChangeLang').click(function(e) {
			e.preventDefault();
			$.tracker.byStr('createnewwiki/namewiki/changelanguage');
			$('#NameWiki .language-default').hide();
			$('#NameWiki .language-choice').show();
		});
		$('#CreateNewWiki nav .back').bind('click', function() {
			var id = $(this).closest('.step').attr('id');
			$.tracker.byStr('createnewwiki/' + id.toLowerCase() + '/back');
			if (id === 'DescWiki') {
				WikiBuilder.transition('DescWiki', false, '-');
				WikiBuilder.handleRegister();
			} else {
				WikiBuilder.transition(id, false, '-');
			}
		});
		
		// Login/Signup event handlers
		$('#Auth .signup-msg a').click(function() {
			WikiBuilder.handleRegister();
		});
		$('#Auth .login-msg a').click(function() {
			WikiBuilder.handleLogin();
		});
		$('#Auth nav input.login').click(function(e) {
			$.tracker.byStr('createnewwiki/auth/login');
			AjaxLogin.form.submit();
		});
		
		// Description event handlers
		$('#DescWiki nav .next').click(function() {
			$.tracker.byStr('createnewwiki/descwiki/next');
			var val = WikiBuilder.wikiCategory.find('option:selected').val();
			if(val) {
				// call create wiki ajax
				WikiBuilder.saveState({
					wikiDescription: ($('#Description').val() == WikiBuilderCfg.descriptionplaceholder ? '' : $('#Description').val())
				}, function() {
					WikiBuilder.createWiki();
					WikiBuilder.transition('DescWiki', true, '+');
				});
			} else {
				WikiBuilder.descWikiSubmitError.show().html(WikiBuilderCfg['desc-wiki-submit-error']).delay(3000).fadeOut();
			}
		});
		$('#Description').placeholder();
		
		// Theme event handlers
		$('#ThemeWiki nav .next').click(function() {
			$.tracker.byStr('createnewwiki/themewiki/next');
			WikiBuilder.saveState(ThemeDesigner.settings, function(){
				if(WikiBuilderCfg.skipwikiaplus) {
					WikiBuilder.gotoMainPage();
				} else {
					WikiBuilder.transition('ThemeWiki', true, '+');
				}
			});
		});
		
		// Upgrade event handlers
		$('#UpgradeWiki nav .next').click(function() {
			$.tracker.byStr('createnewwiki/upgradewiki/next');
			WikiBuilder.gotoMainPage();
		});
		$('#UpgradeWiki .upgrade').click(function() {
			$.tracker.byStr('createnewwiki/upgradewiki/upgrade');
			WikiBuilder.upgradeToWikiaPlus();
		});
		
		// Set current step on page load
		if(WikiBuilderCfg['currentstep']) {
			var pane = $('#' + WikiBuilderCfg['currentstep']);
			WikiBuilder.wb.width(pane.width());
			WikiBuilder.steps.hide();
			pane.show();
		} else {
			$.tracker.byStr('createnewwiki/view');
		}
	},
	
	handleRegister: function() {
		AjaxLogin.showRegister();
		if(!WikiBuilder.registerInit) {
			$.getScript(window.wgScript + '?action=ajax&rs=getRegisterJS&uselang=' + window.wgUserLanguage + '&cb=' + wgMWrevId + '-' + wgStyleVersion, 
				function() {
					$('#Auth nav input.signup').click(function(){
						$.tracker.byStr('createnewwiki/auth/signup');
						UserRegistration.submitForm2('normal');
					});
			});
		}
		$('#Auth, #CreateNewWiki').width(700);
		WikiBuilder.signupEntities.hide();
		WikiBuilder.loginEntities.show();
	},
	
	handleLogin: function() {
		AjaxLogin.showLogin();
		AjaxLogin.init($('#AjaxLoginLoginForm form:first'));
		$('#Auth, #CreateNewWiki').width(600);
		WikiBuilder.signupEntities.show();
		WikiBuilder.loginEntities.hide();
	},
	
	checkWikiName: function(e) {
		var name = WikiBuilder.wikiName.val();
		var lang = WikiBuilder.wikiLanguage.val();
		if(name) {
			$.post(wgScript, {
				action: 'ajax',
				rs: 'moduleProxy',
				moduleName: 'CreateNewWiki',
				actionName: 'CheckWikiName',
				outputType: 'data',
				name: name,
				lang: lang
			}, function(res) {
				if(res) {
					var json = $.parseJSON(res);
					var response = res['response'];
					if(response) {
						WikiBuilder.wikiNameError.html(response);
					} else {
						WikiBuilder.wikiNameError.html('');
					}
				}
			});
		} else {
			WikiBuilder.showIcon(WikiBuilder.wikiNameStatus, '');
			WikiBuilder.wikiNameError.html('');
		}
	},
	
	checkDomain: function(e) {
		var wd = WikiBuilder.wikiDomain.val();
		var lang = WikiBuilder.wikiLanguage.val();
		if(wd) {
			wd = wd.toLowerCase();
			WikiBuilder.wikiDomain.val(wd);
			WikiBuilder.showIcon(WikiBuilder.wikiDomainStatus, 'spinner');
			$.post(wgScript, {
				action: 'ajax',
				rs: 'moduleProxy',
				moduleName: 'CreateNewWiki',
				actionName: 'CheckDomain',
				outputType: 'data',
				name: wd,
				lang: lang,
				type: ''
			}, function(res) {
				if(res) {
					var json = $.parseJSON(res);
					var response = res['response'];
					if(response) {
						WikiBuilder.wikiDomainError.html(response);
						WikiBuilder.showIcon(WikiBuilder.wikiDomainStatus, '');
					} else {
						WikiBuilder.wikiDomainError.html('');
						WikiBuilder.showIcon(WikiBuilder.wikiDomainStatus, 'ok');
					}
				}
			});
		} else {
			WikiBuilder.wikiDomainError.html('');
			WikiBuilder.showIcon(WikiBuilder.wikiDomainStatus, '');
		}
		
	},
	
	showIcon: function (el, art) {
		if(art) {
			var markup = '<img src="';
			if(art == 'spinner') {
				markup += '/skins/common/images/ajax.gif';
			} else if (art == 'ok') {
				markup += '/extensions/wikia/CreateNewWiki/images/check.png';
			}
			markup += '">';
			$(el).html(markup);
		} else {
			$(el).html('');
		}
	},
	
	/*
	transition: function(from, next, dot) {
		var f = $('#' + from);
		var t = (next ? f.next() : f.prev());
		
		f.hide(0, function() {
			WikiBuilder.wb.width(t.width());
			if (dot) {
				if (dot === '+') {
					$('#StepsIndicator .step.active').last().next().addClass('active');
				} else if (dot === '-') {
					$('#StepsIndicator .step.active').last().removeClass('active');
				}
			}
			t.show();
		});
	},
	*/
	
	transition: function (from, next, dot) {
		var f = $('#' + from);
		var t = (next ? f.next() : f.prev());
		var wb = WikiBuilder.wb;
		var fh = f.height();
		var fw = f.width();
		var op = t.css('position');
		t.css('position', 'absolute');
		var th = t.height();
		var tw = t.width();
		t.css('position', op);
		wb.height(fh).width(fw);
		wb.animate({height: th, width: tw}, function(){
			t.animate({'opacity':'show'},{queue:false, duration: 250});
			if (dot) {
				if (dot === '+') {
					$('#StepsIndicator .step.active').last().next().addClass('active');
				} else if (dot === '-') {
					$('#StepsIndicator .step.active').last().removeClass('active');
				}
			}
			wb.height('auto');
			if(next) {
				$.tracker.byStr('createnewwiki/' + from.toLowerCase() + '/transition');
			}
		});
		f.animate({'opacity':'hide'},{queue:false, duration: 250});
		
	},
	
	saveState: function (data, callback) {
		var c = $.parseJSON($.cookies.get('createnewwiki'));
		if (!c) {
			c = {};
		}
		for(var key in data) {
			c[key] = data[key];
		}
		$.cookies.set('createnewwiki', JSON.stringify(c), {hoursToLive: 0, domain: wgCookieDomain, path: wgCookiePath});
		if(callback) {
			callback();
		}
	},
	
	upgradeToWikiaPlus: function() {
		$.post(wgScript,
			{
				action: 'ajax',
				rs: 'moduleProxy',
				moduleName: 'CreateNewWiki',
				actionName: 'UpgradeToPlus',
				outputType: 'data',
				cityId: WikiBuilder.cityId
			},
			function(res) {
				if (res.status == 'ok') {
					location.href = res.data.url;
				} else {
					$.showModal(res.caption, res.content);
				}
			}
		);
	}, 
	
	gotoMainPage: function() {
		WikiBuilder.nextButtons.attr('disabled', true);
		if(WikiBuilder.createStatus && WikiBuilder.createStatus == 'ok' && WikiBuilder.finishCreateUrl) {
			$.tracker.byStr('createnewwiki/complete');
			location.href = WikiBuilder.finishCreateUrl;
		} else if(WikiBuilder.createStatus && WikiBuilder.createStatus == 'backenderror') {
			$.showModal(WikiBuilder.createStatusMessage, WikiBuilder.createStatusMessage);
		} else if (WikiBuilder.retryGoto < 300) {
			if(!WikiBuilder.finishSpinner.data('spinning')) {
				WikiBuilder.finishSpinner.data('spinning', 'true');
				WikiBuilder.showIcon(WikiBuilder.finishSpinner, 'spinner');
			}
			WikiBuilder.retryGoto++;
			setTimeout(WikiBuilder.gotoMainPage, 200);
		}
	},
	
	createWiki: function() {
		$.postJSON(wgScript,
			{
				action: 'ajax',
				rs: 'moduleProxy',
				moduleName: 'CreateNewWiki',
				actionName: 'CreateWiki',
				outputType: 'data',
				data: {
					wikiName: WikiBuilder.wikiName.val(),
					wikiDomain: WikiBuilder.wikiDomain.val(),
					wikiLanguage: WikiBuilder.wikiLanguage.find('option:selected').val(),
					wikiCategory: WikiBuilder.wikiCategory.find('option:selected').val()
				}
			},
			function(res) {
				WikiBuilder.createStatus = res.status;
				WikiBuilder.createStatusMessage = res.statusMsg;
				WikiBuilder.cityId = res.cityId;
				WikiBuilder.finishCreateUrl = (res.finishCreateUrl.indexOf('.com/wiki/') < 0 ? res.finishCreateUrl.replace('.com/','.com/wiki/') : res.finishCreateUrl);
				$('#UpgradeWiki .wiki-name').html(res.siteName);
			}
		);
	}
}

var isAutoCreateWiki = true;

// global fix this spelling later...
function realoadAutoCreateForm() {
	if('undefined' != typeof AjaxLogin.form) {
		AjaxLogin.blockLoginForm(false);
	}
	WikiBuilder.transition('Auth', true, '+');
}

// ThemeDesigner.js overwrites
ThemeDesigner.init = function() {
	$('#ThemeTab li label').remove();
};
ThemeDesigner.set = function(setting, newValue) {
	var t = themes[newValue];
	ThemeDesigner.settings = t;
	var sass = '/__sass/skins/oasis/css/oasis.scss/' + wgStyleVersion + '/';
	var params = '';
	params += 'color-body=' + escape(t['color-body']);
	params += '&color-page=' + escape(t['color-page']);
	params += '&color-buttons=' + escape(t['color-buttons']);
	params += '&color-links=' + escape(t['color-links']);
	params += "&color-header=" + escape(t["color-header"]);
	params += '&background-image=' + encodeURIComponent(t['background-image']);
	params += '&background-align=' + escape(t['background-align']);
	params += '&background-tiled=' + escape(t['background-tiled']);
	$('.ThemeDesignerSASS').addClass('remove');
	$.get(sass+params, function(data) {
		$('<style class="ThemeDesignerSASS">' + data + '</style>').appendTo('head');
		$('.ThemeDesignerSASS.remove').remove();
	});
};
ThemeDesigner.save = function() {

};

function sendToConnectOnLogin() {
	$.tracker.byStr('createnewwiki/auth/transition');
	$.tracker.byStr('createnewwiki/auth/facebook');
	wgPageQuery += encodeURIComponent('&fbreturn=1');
	sendToConnectOnLoginForSpecificForm("");
}