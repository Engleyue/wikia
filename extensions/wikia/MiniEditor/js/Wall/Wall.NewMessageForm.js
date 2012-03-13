(function(window, $) {

	// New Message
	var MiniEditorNewMessageForm = $.createClass(WallNewMessageForm, {
		initEvents: function() {
			var self = this;

			this.WallMessageBody.focus(function() {
				if(!$(this).data('wikiaEditor')) {
					$(this).unbind('.placeholder');
				}
				$(this).miniEditor({
					events: {
						editorReady: function(event, wikiaEditor) {
							if(!MiniEditor.ckeditorEnabled) {
								wikiaEditor.getEditbox()
									.placeholder()
									.triggerHandler('focus.placeholder');
							}
						}
					}
				});
			});
		},

		getMessageBody: function() {
			return this.WallMessageBody.data('wikiaEditor').getContent();
		},

		// Return desired format for saving message.
		getFormat: function() { 
			return this.WallMessageBody.data('wikiaEditor').mode == 'wysiwyg' ? 'wikitext' : '';
		},

		clearNewMessageBody: function() {
			// empty override
		},

		clearNewMessageTitle: function() {
			this.WallMessageTitle.val('').trigger('blur').find('.no-title-warning').fadeOut('fast');	
		},

		disableNewMessage: function() {
			this.WallMessageTitle.trigger('blur');
			this.WallMessageBody.data('wikiaEditor').fire('editorReset');
		},

		enableNewMessage: function() {
			// Note: this was copied and pasted from parent class. Could be more dry
			this.WallMessageSubmit.html($.msg('wall-button-to-submit-comment'));
			this.WallMessageTitle.removeClass('no-title');
			
			$('.new-message .no-title-warning').fadeOut('fast');
		},
		
		postNewMessage_ChangeText_handleContent: function() {
			// empty override
		}
	});
	
	// Exports
	window.MiniEditorNewMessageForm = MiniEditorNewMessageForm;

})(this, jQuery);