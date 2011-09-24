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
	nameAjax: false,
	domainAjax: false,
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
		WikiBuilder.descWikiNext = $('#DescWiki nav .next');
		
		// Name Wiki event handlers
		$('#NameWiki input.next').click(function() {
			$.tracker.byStr('createnewwiki/namewiki/next');
			if (!WikiBuilder.wikiDomain.val() || !WikiBuilder.wikiName.val() || $('#NameWiki .wiki-name-error').html() || $('#NameWiki .wiki-domain-error').html() || WikiBuilder.nameAjax || WikiBuilder.domainAjax) {
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
			WikiBuilder.domainAjax = true;
			if(WikiBuilder.wdtimer) {
				clearTimeout(WikiBuilder.wdtimer);
			}
			WikiBuilder.wdtimer = setTimeout(WikiBuilder.checkDomain, 500);
		});
		WikiBuilder.wikiName.keyup(function() {
			WikiBuilder.nameAjax = true;
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
		WikiBuilder.descWikiNext.click(function() {
			WikiBuilder.descWikiNext.attr('disabled', true);
			$.tracker.byStr('createnewwiki/descwiki/next');
			var val = WikiBuilder.wikiCategory.find('option:selected').val();
			if(val) {
				$.post(wgScript,
					{
						action: 'ajax',
						rs: 'moduleProxy',
						moduleName: 'CreateNewWiki',
						actionName: 'Phalanx',
						outputType: 'data',
						text: $('#Description').val()
					},
					function(res) {
						// check phalanx result
						if (res.msgHeader) {
							$.showModal(res.msgHeader, res.msgBody);
							WikiBuilder.descWikiNext.attr('disabled', false);
						} else {
							// call create wiki ajax
							WikiBuilder.saveState({
								wikiDescription: ($('#Description').val() == WikiBuilderCfg.descriptionplaceholder ? '' : $('#Description').val())
							}, function() {
								WikiBuilder.createWiki();
								WikiBuilder.transition('DescWiki', true, '+');
							});
						}
				});
			} else {
				WikiBuilder.descWikiSubmitError.show().html(WikiBuilderCfg['desc-wiki-submit-error']).delay(3000).fadeOut();
				WikiBuilder.descWikiNext.attr('disabled', false);
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

		WikiBuilder.wikiName.focus();
	},
	
	requestKeys: function() {
		WikiBuilder.keys = WikiBuilderCfg['cnw-keys'];
	},
	
	solveKeys: function() {
		var v = 0;
		for(i = 0; i < WikiBuilder.keys.length; i++) {
			v *= (i % 5) + 1;
			v += WikiBuilder.keys[i];
		}
		WikiBuilder.answer = v;
	}, 

	handleRegister: function() {
		AjaxLogin.showRegister();
		$('#wpRemember').attr('checked', 'true').hide().siblings().hide();
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
			WikiBuilder.nameAjax = true;
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
					var response = res['res'];
					if(response) {
						WikiBuilder.wikiNameError.html(response);
					} else {
						WikiBuilder.wikiNameError.html('');
					}
					WikiBuilder.nameAjax = false;
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
			WikiBuilder.domainAjax = true;
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
					var response = res['res'];
					if(response) {
						WikiBuilder.wikiDomainError.html(response);
						WikiBuilder.showIcon(WikiBuilder.wikiDomainStatus, '');
					} else {
						WikiBuilder.wikiDomainError.html('');
						WikiBuilder.showIcon(WikiBuilder.wikiDomainStatus, 'ok');
					}
					WikiBuilder.domainAjax = false;
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
//		wb.height(fh).width(fw);
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
		var c = JSON.parse($.cookies.get('createnewwiki'));
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
		)
		.error(function() {
			WikiBuilder.generateAjaxErrorMsg();
		});
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
		WikiBuilder.requestKeys();
		WikiBuilder.solveKeys();
		$.postJSON(wgScript,
			{
				action: 'ajax',
				rs: 'moduleProxy',
				moduleName: 'CreateNewWiki',
				actionName: 'CreateWiki',
				outputType: 'data',
				data: {
					wName: WikiBuilder.wikiName.val(),
					wDomain: WikiBuilder.wikiDomain.val(),
					wLanguage: WikiBuilder.wikiLanguage.find('option:selected').val(),
					wCategory: WikiBuilder.wikiCategory.find('option:selected').val(),
					wAnswer: Math.floor(WikiBuilder.answer)
				}
			},
			function(res) {
				WikiBuilder.createStatus = res.status;
				WikiBuilder.createStatusMessage = res.statusMsg;
				if(WikiBuilder.createStatus && WikiBuilder.createStatus == 'ok') {
					WikiBuilder.cityId = res.cityId;
					WikiBuilder.finishCreateUrl = (res.finishCreateUrl.indexOf('.com/wiki/') < 0 ? res.finishCreateUrl.replace('.com/','.com/wiki/') : res.finishCreateUrl);
					$('#UpgradeWiki .wiki-name').html(res.siteName);
				} else {
					$('#ThemeWiki .next-controls input').attr('disabled', 'true');
					$.showModal(res.statusHeader, WikiBuilder.createStatusMessage);
				}
			}
		)
		.error(function() {
			WikiBuilder.generateAjaxErrorMsg();
		});
	},
	
	generateAjaxErrorMsg: function() {
		$.showModal(WikiBuilderCfg['cnw-error-general-heading'], WikiBuilderCfg['cnw-error-general']);
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
	ThemeDesigner.settings = themes[newValue];
	var sassUrl = $.getSassCommonURL('/skins/oasis/css/oasis.scss', ThemeDesigner.settings);
	$.getCSS(sassUrl, function(link) {
		$(ThemeDesigner.link).remove();
		ThemeDesigner.link = link;
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

$(function() {
	wgAjaxPath = wgScriptPath + wgScript;
	WikiBuilder.init();
	$('#AjaxLoginButtons').hide();
	$('#AjaxLoginLoginForm').show();
	
	ThemeDesigner.slideByDefaultWidth = 608;
	ThemeDesigner.slideByItems = 4;
	ThemeDesigner.themeTabInit();
});