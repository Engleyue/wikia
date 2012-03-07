(function(window, $) {

	// Edit Message
	var MiniEditorEditMessageForm = $.createClass(WallEditMessageForm, {
		oldTitle: {},
		oldBody: {},

		showEditTextArea: function(msg, text) {
			var self = this,
				body = $('.msg-body', msg).first(),
				wikiaEditor = body.data('wikiaEditor'),
				isReply = msg.data('is-reply'),
				animation = isReply ? {} : {
					'padding-top': 10,
					'padding-left': 10,
					'padding-right': 10,
					'padding-bottom': 10
	
				};

			// See: http://stackoverflow.com/questions/4095475/jquery-animate-padding-to-zero
			body.closest('.speech-bubble-message').animate(animation, this.proxy(function() {
				if (!wikiaEditor) {
					body.miniEditor({
						events: {
							editorReady: function(event, wikiaEditor) {
								wikiaEditor.setContent(text);
							}
						}
					});
	
				} else {
					body.miniEditor();
					wikiaEditor.setContent(text);
				}
			}));

			$('.timestamp', msg).hide();
		},

		getNewBodyVal: function(msg) {
			return $('.msg-body', msg).first().data('wikiaEditor').getContent();
		},

		getEditFormat: function(msg) {
			var wikiaEditor = $('.msg-body', msg).first().data('wikiaEditor');

			// Format starts as wikitext, so let's check if we need to convert it to RTEHtml
			// Return the desired message format or empty string if no conversion is necessary.
			return (MiniEditor.ckeditorEnabled && (!wikiaEditor || (wikiaEditor && wikiaEditor.mode != 'source'))) ? 'RTEHtml' : '';
		},

		// if we're in wysiwyg mode, convert message to wikitext to save. 
		// Otherwise, don't convert cuz we already have wikitext. 
		getSaveFormat: function(msg) {
			return $('.msg-body', msg).first().data('wikiaEditor').mode == 'wysiwyg' ? 'wikitext' : '';	
		},

		// Insert old html upon cancelling an edit or source view.
		insertOldHTML: function(id, bubble) {
			$('.msg-title', bubble).html(this.oldTitle[id]);
			$('.msg-body', bubble).html(this.oldBody[id]);

			this.afterClose(bubble);
		},

		// Set current html in case edit or source view is cancelled.
		setOldHTML: function(id, bubble) {
			this.oldTitle[id] = bubble.find('.msg-title').html();
			this.oldBody[id] = bubble.find('.msg-body').html();
		},

		afterCancel: function(body, isSource, target) {
			if (isSource) {
				target.parent().hide();

			} else {
				body.data('wikiaEditor').fire('editorReset');
			}
		},

		resetHTMLAfterEdit: function(id, bubble) {
			$('.msg-body', bubble).first().data('wikiaEditor').fire('editorReset');

			this.afterClose(bubble);
		},

		afterClose: function(bubble) {
			$('.follow', bubble).show();
			bubble.find('.timestamp').show();
			
			var isReply = bubble.parent().data('is-reply');
			if(!isReply) {
				bubble.animate({
					'padding-top': 10,
					'padding-left': 20,
					'padding-right': 20,
					'padding-bottom': 10
				});
			}
		}
	});

	// Exports
	window.MiniEditorEditMessageForm = MiniEditorEditMessageForm;

})(this, jQuery);