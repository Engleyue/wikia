$(function() {
	wgAjaxPath = wgScriptPath + wgScript;
	WikiBuilder.init();
	$('#AjaxLoginButtons').hide();
	$('#AjaxLoginLoginForm').show();
	
	ThemeDesigner.slideByDefaultWidth = 608;
	ThemeDesigner.slideByItems = 4;
	ThemeDesigner.themeTabInit();
	//AjaxLogin.showRegister();
	//$.getScript(window.wgScript + '?action=ajax&rs=getRegisterJS&uselang=' + window.wgUserLanguage + '&cb=' + wgMWrevId + '-' + wgStyleVersion);
});

var WikiBuilder = {
	registerInit: false,
	wntimer: false,
	wdtimer: false,
	createStatus: false,
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
			if (!WikiBuilder.wikiDomain.val() || !WikiBuilder.wikiName.val() || $('#NameWiki .error-msg').html()) {
				WikiBuilder.nameWikiSubmitError.show().html(WikiBuilderCfg['name-wiki-submit-error']).delay(3000).fadeOut();
			} else {
				WikiBuilder.saveState({
					wikiName: WikiBuilder.wikiName.val(),
					wikiDomain: WikiBuilder.wikiDomain.val(),
					wikiLang: WikiBuilder.wikiLanguage.find('option:selected').val()
				});
				if ($('#Auth')) {
					AjaxLogin.init($('#AjaxLoginLoginForm form:first'));
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
			if(name) {
				name = name.replace(/\W/, '').replace(/ /g, '');
				WikiBuilder.wikiDomain.val(name.toLowerCase()).trigger('keyup');
			}
			if(WikiBuilder.wntimer) {
				clearTimeout(WikiBuilder.wntimer);
			}
			WikiBuilder.wntimer = setTimeout(WikiBuilder.checkWikiName, 500);
		});
		WikiBuilder.wikiLanguage.click(function () {
			WikiBuilder.wikiName.add(WikiBuilder.wikiDomain).trigger('keyup');
			if($(this).val()){
				WikiBuilder.domainCountry.html();
			}
		});
		$('#CreateNewWiki nav .back').bind('click', function() {
			var id = $(this).closest('.step').attr('id');
			if (id === 'DescWiki') {
				WikiBuilder.transition('DescWiki', false, '-');
				AjaxLogin.showLogin();
				AjaxLogin.init($('#AjaxLoginLoginForm form:first'));
			} else {
				WikiBuilder.transition(id, false, '-');
			}
		});
		
		// Login/Signup event handlers
		$('#Auth .signup-msg a').click(function() {
			AjaxLogin.showRegister();
			if(!WikiBuilder.registerInit) {
				$.getScript(window.wgScript + '?action=ajax&rs=getRegisterJS&uselang=' + window.wgUserLanguage + '&cb=' + wgMWrevId + '-' + wgStyleVersion, 
					function() {
						$('#Auth nav input.signup').click(function(){
							UserRegistration.submitForm2('normal');
						});
				});
			}
			$('#Auth, #CreateNewWiki').width(700);
			WikiBuilder.signupEntities.hide();
			WikiBuilder.loginEntities.show();
		});
		$('#Auth .login-msg a').click(function() {
			AjaxLogin.showLogin();
			AjaxLogin.init($('#AjaxLoginLoginForm form:first'));
			$('#Auth, #CreateNewWiki').width(600);
			WikiBuilder.signupEntities.show();
			WikiBuilder.loginEntities.hide();
		});
		$('#Auth nav input.login').click(function(e) {
			AjaxLogin.form.submit();
		});
		$('#ChangeLang').click(function(e) {
			e.preventDefault();
			$('#NameWiki .language-default').hide();
			$('#NameWiki .language-choice').show();
		});
		
		// Description event handlers
		$('#DescWiki nav .next').click(function() {
			var val = WikiBuilder.wikiCategory.find('option:selected').val();
			if(val) {
				// call create wiki ajax
				WikiBuilder.saveState({
					wikiDescription: $('#Description').val()
				}, function() {
					WikiBuilder.createWiki();
					WikiBuilder.transition('DescWiki', true, '+');
				});
			} else {
				WikiBuilder.descWikiSubmitError.show().html(WikiBuilderCfg['desc-wiki-submit-error']).delay(3000).fadeOut();
			}
		});
		
		// Theme event handlers
		$('#ThemeWiki nav .next').click(function() {
			ThemeDesigner.save();
			if(WikiBuilderCfg.skipwikiaplus) {
				WikiBuilder.gotoMainPage();
			} else {
				WikiBuilder.transition('ThemeWiki', true, '+');
			}
		});
		
		// Upgrade event handlers
		$('#UpgradeWiki nav .next').click(function() {
			WikiBuilder.gotoMainPage();
		});
		$('#UpgradeWiki .upgrade').click(function() {
			WikiBuilder.upgradeToWikiaPlus();
		});
		
		// Set current step on page load
		if(WikiBuilderCfg['currentstep']) {
			var pane = $('#' + WikiBuilderCfg['currentstep']);
			WikiBuilder.wb.width(pane.width());
			WikiBuilder.steps.hide();
			pane.show();
		}
	},
	
	checkWikiName: function(e) {
		var name = WikiBuilder.wikiName.val();
		var lang = WikiBuilder.wikiLanguage.val();
		WikiBuilder.showIcon(WikiBuilder.wikiNameStatus, 'spinner');
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
					WikiBuilder.showIcon(WikiBuilder.wikiNameStatus, '');
					WikiBuilder.wikiNameError.html(response);
				} else {
					WikiBuilder.showIcon(WikiBuilder.wikiNameStatus, 'ok');
					WikiBuilder.wikiNameError.html('');
				}
			}
		});
	},
	
	checkDomain: function(e) {
		var wd = WikiBuilder.wikiDomain.val();
		var lang = WikiBuilder.wikiLanguage.val();
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
		});
		f.animate({'opacity':'hide'},{queue:false, duration: 250});
		
	},
	
	saveState: function (data, callback) {
		$.post(wgScript, {
			action: 'ajax',
			rs: 'moduleProxy',
			moduleName: 'CreateNewWiki',
			actionName: 'SaveState',
			outputType: 'data',
			data: data
		}, function(res) {
			if(callback) {
				callback();
			}
		});
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
		if(WikiBuilder.finishCreateUrl) {
			location.href = WikiBuilder.finishCreateUrl;
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
				WikiBuilder.cityId = res.cityId;
				WikiBuilder.finishCreateUrl = res.finishCreateUrl;
				WikiBuilder.createStatus = res.status;
			}
		);
	}, 
	
	fbLoginCallback: function() {
		alert('foo');
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
ThemeDesigner.init = function() {};
ThemeDesigner.set = function(setting, newValue) {
	var t = themes[newValue];
	var sass = '/__sass/skins/oasis/css/oasis.scss/3337777333333/';
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
	$('<style class="ThemeDesignerSASS">').appendTo('head').load(sass + params, function() {
		$('.ThemeDesignerSASS.remove').remove();
	});
	ThemeDesigner.settings = t;
};
ThemeDesigner.save = function() {
	WikiBuilder.saveState(ThemeDesigner.settings);
};

function sendToConnectOnLogin(){
	wgPageQuery += encodeURIComponent('&fbreturn=1');
	sendToConnectOnLoginForSpecificForm("");
}