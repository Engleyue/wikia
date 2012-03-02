var UserLoginModal = {
	loginAjaxForm: false,

	/**
	 * options (optional):
	 *  callback: callback after login successful login
	 * returns: true if modal is shown, false if it is not
	 */
	show: function(options) {
		if (!window.wgComboAjaxLogin && window.wgEnableUserLoginExt) {
			options = options || {};
			$.get(wgScriptPath + '/wikia.php', {
				controller: 'UserLoginSpecial',
				method: 'modal',
				format: 'html',
				uselang: window.wgUserLanguage
			}, function(res) {
				UserLoginModal.dialog = $(res).makeModal({
					width: 350
				});
				UserLoginModal.loginAjaxForm = new UserLoginAjaxForm(UserLoginModal.dialog, {
					ajaxLogin: true,
					callback: function(res) {
						var callback = options['callback'];
						if(callback && typeof callback === 'function') {
							if(!options['persistModal']) {
								UserLoginModal.dialog.closeModal();
							}
							callback();
						} else {
							UserLoginModal.loginAjaxForm.reloadPage();
						}
					},
					resetpasscallback: function(res) {
						$.post(wgScriptPath + '/wikia.php', {
								controller: 'UserLoginSpecial',
								method: 'changePassword',
								format: 'html',
								username: UserLoginModal.loginAjaxForm.inputs['username'].val(),
								password: UserLoginModal.loginAjaxForm.inputs['password'].val(),
								returnto: UserLoginModal.loginAjaxForm.inputs['returnto'].val(),
								fakeGet: 1
							}, function(html) {
								var content = $('<div style="display:none" />').append(html);
								var heading = content.find('h1');
								UserLoginModal.dialog.find('h1').text(heading.text());
								heading.remove();
								var contentBlock = UserLoginModal.dialog.find('.UserLoginModal');
								contentBlock.slideUp(400, function() {
									contentBlock.html('').html(content);
									content.show();
									contentBlock.slideDown(400);
								});
							}
						);
					}
				});
	
				// setup facebook button
				UserLoginFacebook.setupTooltips();
			});
			$.getResources([$.getSassCommonURL('/extensions/wikia/UserLogin/css/UserLoginModal.scss')]);
			
			return true;
		} else if(window.wgComboAjaxLogin) {
			/* 1st, 2nd, 4th, and 5th vars in this method is not used outside of ajaxlogin itself*/
			showComboAjaxForPlaceHolder(false, false, function() {
				if(options['callback']) {
					AjaxLogin.doSuccess = options['callback'];
				}
			}, false, true);
			
			return true;
		}
		
		return false;
	},
	init: function() {
		// attach event handler
		var editpromptable = $("#ca-viewsource, #te-editanon, .loginToEditProtectedPage, .upphotoslogin");

		// add .editsection on wikis with anon editing disabled
		if ( (typeof wgDisableAnonymousEditing !== 'undefined') && (wgDisableAnonymousEditing) ) {
			editpromptable = editpromptable.add(".editsection");
		}

		editpromptable.click(function(ev) {
			if(UserLogin.isForceLogIn()) {
				ev.preventDefault();
			}
		});

		//Attach DOM-Ready handlers
		$('body').delegate('.ajaxLogin', 'click', function(e) {
			UserLoginModal.show();
			e.preventDefault();
		});
	}
};

$(function() {
	if ((typeof window.wgEnableUserLoginExt != 'undefined') && wgEnableUserLoginExt ) {
		UserLoginModal.init();
	}
});