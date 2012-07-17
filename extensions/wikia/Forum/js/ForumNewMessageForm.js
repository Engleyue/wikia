(function(window, $) {

Forum.NewMessageForm = $.createClass(Wall.NewMessageForm, {
	initElements: function() {
		this.wrapper = $('#ForumNewMessage');
		this.buttons = this.wrapper.find('.buttons');
		this.message = this.wrapper.find('.message');
		this.messageNoTitle = this.message.find('.no-title-warning');
		this.messageTitle = this.message.find('.title');
		this.messageBody = this.message.find('.body');
		this.messageBodyContainer = this.message.find('.body-container');
		this.messageSubmit = this.message.find('.submit');
		this.notifyEveryone = this.message.find('.notify-everyone');
		this.loading = this.message.find('.loadingAjax');
		
		this.messageTitle.on('focus', this.proxy(this.messageTitleFocus));
	},
	afterPost: function(newmsg) {

		// TODO: this is a hack. We should just be getting the ID back
		window.location = newmsg.find('.msg-title a').attr('href');
	},
	messageTitleFocus: function(event) {
		if (this.messageBodyContainer.is(':hidden')) {
			this.messageBodyContainer.fadeIn();
		}
	}
});

Wall.settings.classBindings.newMessageForm = Forum.NewMessageForm;

})(window, jQuery);