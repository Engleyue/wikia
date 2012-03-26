/*global WikiaEditor:true */
var UserLogin = {
	rteForceLogin: function() {
		if (!window.wgComboAjaxLogin) {
			//prevent onbeforeunload from being called when user is loging in
			window.onbeforeunload = function() {};
			UserLoginModal.show({
				persistModal: true,
				callback: function() {
					window.WikiaEditor && WikiaEditor.reloadEditor();
				}
			});
		} else {
			showComboAjaxForPlaceHolder("",false, "", false, true);
		}
	},

	isForceLogIn: function() {
		if (wgUserName == null) {
			if (!window.wgComboAjaxLogin) {
				UserLoginModal.show();
				return true;
			}
			else if (showComboAjaxForPlaceHolder("",false, "", false, true)) {
				return true;
			}
		}
		return false;
	}
};