$(function() {
	var wall = new Wall();
});

//var global_hide = 0;

var Wall = $.createClass(Object, {
	constructor: function() {
		this.settings = {}
		this.settings.new_title = {min: 30, minFocus:30, minContent: 30, limit: 300, limitEmpty: 30, extraSpace: 15};
		this.settings.new_body = {minFocus:100, minContent: 100, limit: 9999, limitEmpty: 70, extraSpace: 30};
		this.settings.edit_title = {min: 30, minFocus:30, minContent: 30, limit: 300, limitEmpty: 30, extraSpace: 0};
		this.settings.edit_body = this.settings.new_body;
		this.settings.reply = {minFocus:100, minContent: 100, limit: 9999, limitEmpty: 30, extraSpace: 30};
		
		// Submit new wall post
		$('.wall-require-login').live('click', this.proxy(this.onAfterAjaxLogin));
		$('.wall-reply-require-login').live('click', this.proxy(this.onAfterAjaxLogin));
		$('#WallMessageSubmit').bind('click', this.proxy(this.postNewMessage));
		$('#WallMessagePreview').bind('click', this.proxy(this.previewNewMessage));
		$('#WallMessagePreviewCancel').bind('click', this.proxy(this.cancelPreviewNewMessage));
		
		// New wall post change
		$('#WallMessageTitle, #WallMessageBody')
			.keydown(this.proxy(this.postNewMessage_ChangeText_pre))
			.keyup(this.proxy(this.postNewMessage_ChangeText_pre))
			.change(this.proxy(this.postNewMessage_ChangeText_pre))
			.focus(this.proxy(this.postNewMessage_focus))
			.blur(this.proxy(this.postNewMessage_blur));
			
		$('#WallMessageTitle')
			.keydown(function(e) { if(e.which == 13) {$('#WallMessageBody').focus(); return false; }})
			.autoResize(this.settings.new_title);
		$('#WallMessageBody').autoResize(this.settings.new_body);

		// Reply focus, blur, and reply events
		$('.new-reply textarea')
			.bind('keydown keyup change', this.proxy(this.reply_ChangeText))
			.live('focus', this.proxy(this.replyFocus))
			.live('blur', this.proxy(this.replyBlur))
			.autoResize(this.settings.reply);
		$('.replyButton').live('click', this.proxy(this.replyToMessage));
		$('.replyPreview').live('click', this.proxy(this.replyToMessagePreview));
		$('.replyPreviewCancel').live('click', this.proxy(this.replyToMessagePreviewCancel));

		// Delete
		$('#Wall .delete-message').live('click', this.proxy(this.confirmDelete));

		// Edit
		$('#Wall .edit-message').live('click', this.proxy(this.editMessage));
		$('#Wall .cancel-edit').live('click', this.proxy(this.cancelEdit));
		$('#Wall .save-edit').live('click', this.proxy(this.saveEdit));
		$('#Wall .preview-edit').live('click', this.proxy(this.previewEdit));
		$('#Wall .cancel-preview-edit').live('click', this.proxy(this.cancelPreviewEdit));
		
		// Pagination
		$('.Pagination a').live('click', this.proxy(this.switchPage));

		$('.load-more a').live('click', this.proxy(this.loadMore));
		
		// Make timestamps dynamic
		$('.timeago').timeago();
		
		$('#Wall .follow.wikia-button')
			.live('click', this.proxy(this.switchWatch))
			.live('mouseenter', this.proxy(this.hoverFollow))
			.live('mouseleave', this.proxy(this.unhoverFollow));
		
		// If any textarea has content make sure Reply / Post button is visible
		$(document).ready(this.iniciateTextareas);
		
		
		if(wgTitle.indexOf('/') > 0) {
			var titlePart = wgTitle.split('/');
			this.username = titlePart[0]; 
		} else {
			this.username = wgTitle;
		}
		
		// fix firefox bug (when textarea is disabled and you refresh a page
		// it's still disabled on new page loaded
		$('textarea').removeAttr('disabled');
		
		$("#Wall textarea").live('keydown', this.proxy(this.focusButton) );
		
		$().log(this.username, "Wall username");
	},

	proxy: function(func) {
		return $.proxy(func, this);
	},
	
	
	//hack for safari tab index
	focusButton: function(e) {
		var element = $(e.target);
		var button = element.closest('.SpeechBubble').find('button');
		if(e.keyCode == 9) {
			if(element.attr('id') != 'WallMessageTitle') {
				button.focus();
				e.preventDefault();
			}			
		}
	},
	
	iniciateTextareas: function() {
		setTimeout( function() { // make sure all textareas are inicialized already
			//$('.new-message textarea.body').trigger('focus');
			$('.new-reply textarea').each( function() {
				if( $(this).is(':focus') ) $(this).trigger('focus');
			});
			var title = $('#WallMessageTitle');
			if( title.is(':focus') ) title.trigger('focus');
			var body = $('#WallMessageBody');
			if( body.is(':focus') ) body.trigger('focus');
		}, 50);
	},

	switchWatch: function(e) {
		var element = $(e.target);
		var isWatched = parseInt(element.attr('data-iswatched'));
		var commentId = element.closest('li').attr('data-id');
		
		element.animate({'opacity':0.5},'slow');
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'switchWatch',
			format: 'json',
			data: {
				isWatched: isWatched,
				commentId: commentId
			},
			callback: this.proxy(function(data) {
				if(data.status) {
					element.attr('data-iswatched', isWatched ? 0:1);
					if(isWatched) {
						element.animate({'opacity':0.7},'slow', function() { element.css('opacity','');} );
						$(e.target).text($.msg('wall-message-follow')).addClass('secondary');
					} else {
						element.animate({'opacity':0.7},'slow', function() { element.css('opacity','');} );
						$(e.target).text($.msg('wall-message-following')).removeClass('secondary');
					}
				}}
			)
		});
	},
	
	switchPage: function(e) {
		e.preventDefault();
		var page = $(e.target).closest('li').attr('data-page');
		e.preventDefault();
		this._switchPage(page, 0.5);
	},
	
	_switchPage: function(page, fadeopacity) {
		$('#Wall .comments').animate({'opacity':fadeopacity},'slow');

		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'getCommentsPage',
			format: 'json',
			data: {
				page: page,
				username: this.username
			},
			callback: function(data) {
				var newhtml = $(data.html);
				$('#Wall .comments').html($('.comments',newhtml).html()).animate({'opacity':1},'slow');
				$('#Wall .Pagination').html($('.Pagination',newhtml).html());
				
				var destination = $('#Wall').offset().top;
				if($.browser.msie) {
					$("html:not(:animated),body:not(:animated)").css({ scrollTop: destination-20});
				} else {
					$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination-20}, 500 );
				}

				setTimeout(function() {
					$('#Wall').find('textarea,input').placeholder();
					$('.timeago').timeago();
					$('.new-reply textarea').bind('keydown keyup change', this.proxy(this.reply_ChangeText))
				}, 100);

			}
		});
	},
	
	hoverFollow: function(e) {
		if( $(e.target).html() == $.msg('wall-message-following') ) {
			$(e.target).html($.msg('wall-message-unfollow'));
		}
	},
	
	unhoverFollow: function(e) {
		if( $(e.target).html() == $.msg('wall-message-unfollow') ) {
			$(e.target).html($.msg('wall-message-following'));
		}
	},	

	loadMore: function(e) {
		//$(e.target).closest('li').hide();
		$(e.target).closest('ul').find('li.SpeechBubble').show();
		$(e.target).closest('.load-more').remove();
		e.preventDefault();
	},

	/*
	 * Message functions
	 */
	postNewMessage: function(href) {
		var topic = !$('#WallMessageTitle').hasClass('placeholder') && $('#WallMessageTitle').val().length > 0;

		if(!topic && $('#WallMessageSubmit').html() != $.msg('wall-button-to-submit-comment-no-topic')) {
			$('#WallMessageSubmit').html($.msg('wall-button-to-submit-comment-no-topic'));
			$('.new-message .no-title-warning').fadeIn();
			$('.new-message input').addClass('no-title');
			return;
		}
		
		if( $('#WallMessageSubmit').hasClass('wall-require-login') ) {
		//do nothing -- ajax combo box will take care of it starting from now
			return;
		}
		
		this.disableNewMessage();
		
		// are we on 1st page? (Pagination)
		if( $('#Wall .Pagination .first').length == 0 || $('#Wall .Pagination .first').hasClass('selected') ) {
			// we are all good - no need to force pagination
		} else {
			// let's force pagination - to post new msg on the top of 1st page
			var destination = $('#Wall').offset().top;
			if($.browser.msie) {
				$("html:not(:animated),body:not(:animated)").css({ scrollTop: destination-20});
			} else {
				$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination-20}, 500 );
			}
			this._switchPage( 1, 0 ); // switch to page 1, fade to 0 opacity on animate
		}
		
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'postNewMessage',
			data: {
				body: $('#WallMessageBody').val(),
				messagetitle: topic ? $('#WallMessageTitle').val() : '',
				username: this.username
			},
			callback: this.proxy(function(data) {
				this.cancelPreviewNewMessage();
				$('#WallMessageBody').val("").trigger('blur');
				$('#WallMessageTitle').val("").trigger('blur');
				var newmsg = $(data['message']);
				$('#Wall .comments').prepend(newmsg);
				if(!$.browser.msie) { // IE is too slow for that (even IE8)
					newmsg.hide()
						.css('opacity',0)
						.slideDown('slow')
						.animate({'opacity':1},'slow');
				}
				$('.timeago',newmsg).timeago();
				$('.new-reply textarea', newmsg).bind('keydown keyup change', this.proxy(this.reply_ChangeText))
				$('textarea', newmsg).autoResize(this.settings.reply).placeholder();
				
				this.enableNewMessage();
				$('.new-message .speech-bubble-message').css({'padding-bottom':10});
				$('#WallMessageSubmit').hide();
				
				if( typeof(href) == 'string' ) {
					window.location.href = href;
				}
			})

		});
	},

	previewNewMessage: function() {
		var topic = !$('#WallMessageTitle').hasClass('placeholder') && $('#WallMessageTitle').val().length > 0;

		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'previewMessage',
			data: {
				body: $('#WallMessageBody').val(),
				messagetitle: topic ? $('#WallMessageTitle').val() : '',
				username: this.username
			},
			callback: this.proxy(function(data) {
				$('#WallMessageBody').hide();
				$('#WallMessageTitle').hide();
				
				var newmsg  = $('.new-message .speech-bubble-message');
				newmsg.addClass('preview-bubble');
				var preview = $('<div class="preview"></div>' );
				preview.append( '<div class="preview-title">'+data['title']+'</div>' );
				preview.append( '<div class="edited-by"><a>'+data['displayname']+'</a><a class="subtle">'+data['displayname2']+'</a></div>' );
				preview.append( '<div class="preview-body">' +data['body']+'</div>' );
				$('.new-message .preview').remove();

				preview.prependTo(newmsg);
				
				$('#WallMessagePreviewCancel').show();
				$('#WallMessagePreview').hide();
			})

		});
	},	

	cancelPreviewNewMessage: function() {
		var newmsg  = $('.new-message .speech-bubble-message');
		newmsg.removeClass('preview-bubble');

		$('#WallMessageBody').show();
		$('#WallMessageTitle').show();
		
		$('.new-message .speech-bubble-message .preview').remove();
		
		$('#WallMessagePreviewCancel').hide();
		$('#WallMessagePreview').show();
	},	
	
	onAfterAjaxLogin: function(e) {
		var event = e;
		if( typeof(showComboAjaxForPlaceHolder) == 'function' && showComboAjaxForPlaceHolder('', false, this.proxy(function() {
			AjaxLogin.doSuccess = this.proxy(function() {
				var eventTarget = $(event.target),
					wallReply = false,
					wallPost = false;
				
				if( eventTarget.hasClass('wall-require-login') ) {
					if( eventTarget.hasClass('replyButton') ) {
						wallReply = true;
					} else {
						wallPost = true;
					}
					
					eventTarget.removeClass('wall-require-login');
				}
				
				var href = eventTarget.attr('data');
				if( wallPost && href ) {
					this.postNewMessage(href);
				} else if(wallReply && href) {
					this.replyToMessage(event, href);
				}
			});
		}), false, true) ) {
			event.preventDefault();
		}
	},

	postNewMessage_ChangeText_pre: function(e) {
		var trg = $(e.target);
		if(trg.hasClass('title')) {
			topic_str = trg.val();
			topic_len = topic_str.length;
			if(topic_len >= 200) trg.val( topic_str.slice(0,200) );
		}
		setTimeout( this.proxy(this.postNewMessage_ChangeText), 50 );
	},
	
	postNewMessage_ChangeText: function() {
		// check if both topic and content are filled
		var topic_str = $('#WallMessageTitle').val();
		var topic_len = topic_str.length;
		var topic = !$('#WallMessageTitle').hasClass('placeholder') && topic_len > 0;
		var content = !$('#WallMessageBody').hasClass('placeholder');
		content =  content && $('#WallMessageBody').val().length > 0;
		if(content) {
			$('#WallMessageSubmit').removeAttr('disabled');
			$('#WallMessagePreview').removeAttr('disabled');
		} else {
			$('#WallMessageSubmit').attr('disabled','disabled');
			$('#WallMessagePreview').attr('disabled','disabled');
		}
		if(topic && $('#WallMessageSubmit').html() == $.msg('wall-button-to-submit-comment-no-topic')) {
			$('#WallMessageSubmit').html($.msg('wall-button-to-submit-comment'));
			$('.new-message .no-title-warning').fadeOut('fast');
			$('#WallMessageTitle').removeClass('no-title');
		}
	},

	postNewMessage_focus: function(e) {
		$('#WallMessageSubmit').show();
		$('#WallMessagePreview').show();
		$('.new-message .speech-bubble-message').css({'padding-bottom':45});
		//if($(e.target).hasClass('title'))
		//	$(e.target).css('line-height','170%');
	},

	postNewMessage_blur: function() {
		//topic = !$('.new-message textarea.title').hasClass('placeholder') && $('.new-message textarea.title').val().length > 0;
		var content = !$('#WallMessageBody').hasClass('placeholder');
		content = content && $('#WallMessageBody').val().length > 0;
		if(!content) {
			$('#WallMessageSubmit').attr('disabled', 'disabled').hide();
			$('#WallMessagePreview').attr('disabled', 'disabled').hide();
			$('.new-message .speech-bubble-message').css({'padding-bottom':10});
		}
		/*if(!title) {
			$('.new-message textarea.title:focus').css('line-height','normal');
		}*/
	},

	disableNewMessage: function() {
		$('#WallMessageSubmit, #WallMessagePreview, .new-message textarea').attr('disabled', 'disabled');
		$('.new-message .loadingAjax').show();
		$('.new-message .speech-bubble-message').addClass('loading');
	},

	enableNewMessage: function() {
		$('#WallMessageSubmit, #WallMessagePreview, .new-message textarea').removeAttr('disabled');
		$('#WallMessageSubmit, #WallMessagePreview').fadeOut('fast');
		$('#WallMessageSubmit').html($.msg('wall-button-to-submit-comment'));
		$('.new-message .no-title-warning').fadeOut('fast');
		$('#WallMessageTitle').removeClass('no-title');
		$('.new-message .loadingAjax').hide();
		$('.new-message .speech-bubble-message').removeClass('loading');
	},
	
	previewEdit: function(e) {
		e.preventDefault();
		
		var el = $(e.target).closest('.message');
		var isreply = el.attr('is-reply');
		el = $('.speech-bubble-message',el).first();
		
		$().log(isreply);
		
		var topic = null;
		if($('textarea.title',el).length>0)
			topic = !$('textarea.title',el).not('textarea[tabindex=-1]').hasClass('placeholder') && $('textarea.title',el).not('textarea[tabindex=-1]').val().length > 0;
		
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'previewMessage',
			data: {
				body: $('textarea.body',el).not('textarea[tabindex=-1]').val(),
				messagetitle: topic ? $('textarea.title',el).not('textarea[tabindex=-1]').val() : '',
				username: this.username
			},
			callback: this.proxy(function(data) {
				$('.msg-title, .edited-by, .msg-body',el).hide();
				el.addClass('preview-bubble');
				
				var preview = $('<div class="preview"></div>' );
				if(!isreply)
					preview.append( '<div class="preview-title">' +data['title']+'</div>' );
				preview.append( '<div class="edited-by"><a>'+data['displayname']+'</a><a class="subtle">'+data['displayname2']+'</a></div>' );
				preview.append( '<div class="preview-body">' +data['body']+'</div>' );
				$('.preview',el).remove();
				preview.prependTo(el);
				
				$('.preview-edit',el.parent()).hide();
				$('.cancel-preview-edit',el.parent()).show();
			})

		});
	},
	
	cancelPreviewEdit: function(e) {
		e.preventDefault();

		var el = $(e.target).closest('.message');
		el = $('.speech-bubble-message',el).first();
		el.removeClass('preview-bubble');
		
		$('.preview',el).remove();
		$('.msg-title, .edited-by, .msg-body').show();
		$('.preview-edit',el.parent()).show();
		$('.cancel-preview-edit',el.parent()).hide();
	},

	editMessage: function(e) {
		e.preventDefault();
		
		var buttons = $('.buttons');
		buttons.hide();

		var msg = $(e.target).closest('li.message');
		var id = msg.attr('data-id');
		var isreply = msg.attr('is-reply');
		
		 
		var data = {
			'msgid': id,
			'isreply': isreply,
			'username': this.username
		};
		
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'editMessage',
			format: 'json',
			data: data,
			callback: this.proxy(function(data) {
				$().log(data);
				var bubble = $('.speech-bubble-message',msg).first();
				
				var beforeedit = bubble.html();
				
				var editbuttons = $('<div class="edit-buttons"></div>');
				$('<a class="wikia-button save-edit">'+$.msg('wall-button-save-changes')+'</a>').appendTo(editbuttons);
				$('<a class="wikia-button preview-edit secondary">'+$.msg('wall-button-to-preview-comment')+'</a>').appendTo(editbuttons);
				$('<a class="wikia-button cancel-preview-edit secondary" style="display: none;">'+$.msg('wall-button-to-cancel-preview')+'</a>').appendTo(editbuttons);
				$('<a class="wikia-button cancel-edit secondary">'+$.msg('wall-button-cancel-changes')+'</a>').appendTo(editbuttons);
				
				
				$('.msg-title',msg).first().html('<textarea class="title">'+$('.msg-title a',msg).html()+'</textarea>');
				$('.msg-body',msg).first().html('<textarea class="body">'+data.wikitext+'</textarea>');
				$('.follow',msg).hide();
				$('textarea.title',msg).first()
					.keydown(function(e) { if(e.which == 13) { return false; }})
					.autoResize(this.settings.edit_title).trigger('change');
				$('textarea.body',msg).first().focus().autoResize(this.settings.edit_body).trigger('change');
				
				bubble.append(editbuttons);
				bubble.append( $('<div class="before-edit"></div>').html(beforeedit) );
			})

		});

	},

	cancelEdit: function(e) {
		e.preventDefault();
		
		this.cancelPreviewEdit(e);

		var msg = $(e.target).closest('.message');
		
		var beforeedit = $('.before-edit',msg).html();
		
		/* restore html to state from before edit */
		$('.speech-bubble-message',msg).first().html(beforeedit);
		//$('.buttons',msg).first().show();
		$('.buttons').show();

	},
	
	saveEdit: function(e) {
		var msg = $(e.target).closest('li.message');
		var id = msg.attr('data-id');
		var isreply = msg.attr('is-reply');
		var newtitle = $('.msg-title textarea.title',msg).val();
		var newbody = $('.msg-body textarea.body',msg).val();
		
		var data = {
			'msgid': id,
			'newtitle': newtitle,
			'newbody': newbody,
			'isreply': isreply,
			'username': this.username
		};
		
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'editMessageSave',
			format: 'json',
			data: data,
			callback: this.proxy(function(data) {
				this.cancelPreviewEdit(e);
				var beforeedit = $('.before-edit',msg).html();
				var bubble = $('.speech-bubble-message',msg).first();
								
				$('.speech-bubble-message',msg).first().html(beforeedit);

				$('.msg-title',msg).first().html(data.title);
				$('.msg-body',msg).first().html(data.body);
								
				//$('.buttons',msg).first().show();
				$('.buttons').show();
			})

		});
	},

	/*
	 * Reply functions
	 */
	
	replyToMessagePreview: function(e) {
		var el = $(e.target).closest('.new-reply');
		el = $('.speech-bubble-message',el);

		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'previewMessage',
			data: {
				body: $('textarea.content', el).val(),
				messagetitle: '',
				username: this.username
			},
			callback: this.proxy(function(data) {
				$('textarea.content').hide();
				el.addClass('preview-bubble');
				
				var preview = $('<div class="preview"></div>' );
				preview.append( '<div class="edited-by"><a>'+data['displayname']+'</a><a class="subtle">'+data['displayname2']+'</a></div>' );
				preview.append( '<div class="preview-body">' +data['body']+'</div>' );
				$('.preview',el).remove();
				preview.prependTo(el);
				
				$('.replyPreviewCancel',el.parent()).show();
				$('.replyPreview',el.parent()).hide();
			})

		});
	},	
	
	replyToMessagePreviewCancel: function(e) {
		var el = $(e.target).closest('.new-reply');
		$('.speech-bubble-message',el).removeClass('preview-bubble');
		$('textarea.content',el).show();
		
		$('.speech-bubble-message .preview',el).remove();
		
		$('.replyPreview',el).show();
		$('.replyPreviewCancel',el).hide();
	},	
	
	
	replyToMessage: function(e, href) {
		if( $(e.target).hasClass('wall-require-login') ) {
		//do nothing -- ajax combo box will take care of it starting from now
			return;
		}
		
		var main = $(e.target).closest('.comments > .SpeechBubble');
		var newreply = $(e.target).closest('.SpeechBubble');
		this.disableReply(newreply);
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'replyToMessage',
			data: {
				body: newreply.find('textarea').val(),
				parent: main.attr('data-id'),
				username: this.username
			},
			callback: this.proxy(function(data) {
				this.replyToMessagePreviewCancel(e);
				this.enableReply($(e.target).closest('.SpeechBubble'));
				//this.replyShrink($(e.target).closest('.SpeechBubble'), true);
				main.find('textarea').val("").trigger('blur');
				var newmsg = $($(data['message'])).insertBefore(main.find('ul li.new-reply')).hide().fadeIn('slow');
				//$('<div class="highlight"></div>').appendTo(newmsg);//.fadeTo(0,0.05).fadeTo(1000, 0.05).fadeOut(3000);
				$('.timeago',newmsg).timeago();
				//$('.SpeechBubble[data-id='+main.attr('data-id')+']:after',newmsg.parent()).css('opacity',1).animate({opacity:'0'},2000);
				main.find('ul li.load-more .count').html(main.find('ul li.message').length);
				$('.speech-bubble-message', newreply).css({'margin-left':'0px'});
				$('.speech-bubble-avatar', newreply).hide();
				
				if( typeof(href) == 'string' ) {
					window.location.href = href;
				}
				$('.follow', $(e.target).closest('.SpeechBubble.message')).text($.msg('wall-message-following')).removeClass('secondary');

			})

		});
	},

	disableReply: function(e) {
		$('textarea', e).attr('disabled', 'disabled');
		$('.replyButton', e).attr('disabled', 'disabled');
		$('.loadingAjax', e).show();
		$('.speech-bubble-message', e).addClass('loading');
	},

	enableReply: function(e) {
		$('textarea', e).removeAttr('disabled');
		$('.replyButton', e).removeClass('loading').removeAttr('disabled');
		$('.loadingAjax', e).hide();
		$('.speech-bubble-message', e).removeClass('loading');
	},

	replyFocus: function(e) {
		var el = $(e.target).closest('.SpeechBubble');
		$('.replyButton', el).show();
		$('.replyPreview',el).show();
		$(el).css({ 'margin-bottom': '40px'});
		$('.speech-bubble-message', el).stop().css({'margin-left':'40px'});
		$('.speech-bubble-avatar', el).show();
		$('textarea',el).css('line-height','150%');
	},

	reply_ChangeText: function(e) {
		var target = $(e.target);
		var content = !target.hasClass('placeholder') && target.val().length > 0;

		if(content && !target.hasClass('content') ) {
			target.closest('.SpeechBubble').find('.replyButton').removeAttr('disabled');
			target.addClass('content');
			var el = target.closest('.SpeechBubble');
			$('button', el ).removeAttr('disabled');
		} else if(!content && target.hasClass('content')) {
			target.closest('.SpeechBubble').find('.replyButton').attr('disabled', 'disabled');
			target.removeClass('content');
			var el = target.closest('.SpeechBubble');
			$('button', el ).attr('disabled','disabled');
		}
	},

	replyBlur: function(e) {
		var content = !$(e.target).hasClass('placeholder') && $(e.target).val().length > 0;

		if(!content) {
			var el = $(e.target).closest('.SpeechBubble');
			$('button', el ).hide();
			$(el).css({ 'margin-bottom': '0px'});
			/*if(global_hide==0) setTimeout(function() { $('.speech-bubble-message', el).css({'margin-left':'0px'});}, 150);
			if(global_hide==1) setTimeout(function() { $('.speech-bubble-message', el).animate({'margin-left':'0px'},100);}, 150);
			if(global_hide==2) $('.speech-bubble-message', el).animate({'margin-left':'0px'},150);
			if(global_hide==3) {
				$('.speech-bubble-message', el).css({'margin-left':'0px'});
				$('textarea', el).stop().css({'height':'30px'});
				$('.speech-bubble-avatar', el).stop().hide();
				setTimeout(function() { $('textarea', el).stop().css({'height':'30px'});}, 0);
			}*/
			$('.speech-bubble-message', el).animate({'margin-left':'0px'},150);
			$('.speech-bubble-avatar', el)
				.css('position','absolute')
				.fadeOut(150);
			$('textarea',el).css('line-height','normal');
		} 
	},

	confirmDelete: function(e) {
		e.preventDefault();

		var isreply = $(e.target).closest('.SpeechBubble').attr('is-reply');
		var msg;
		if(isreply) {
			msg = $.msg('wall-delete-confirm');
		} else {
			msg = $.msg('wall-delete-confirm-thread');
		}
		$.confirm({
			title: $.msg('wall-delete-title'),
			content: msg,
			cancelMsg: $.msg('wall-delete-confirm-cancel'),
			okMsg: $.msg('wall-delete-confirm-ok'),
			onOk: function() {
				var msg = $(e.target).closest('li.message');
				var id = msg.attr('data-id');
				$.nirvana.sendRequest({
					controller: 'WallExternalController',
					method: 'removeMessage',
					format: 'json',
					data: {
						msgid: id,
						username: this.username
					},
					callback: function(data) {
						if( data.status ) msg.fadeOut('fast', function() { msg.remove(); });
					}

				});
			}

		});
		e.preventDefault();
	}

});


function ChangeStyle(name,val) {
	/*if(val) {
		$('#Wall').addClass(name);
		$('a.'+name+'_0').removeClass('selected');
		$('a.'+name+'_1').addClass('selected');
	} else {
		$('#Wall').removeClass(name);
		$('a.'+name+'_1').removeClass('selected');
		$('a.'+name+'_0').addClass('selected');
	}*/
	$('a.'+name).removeClass('selected');
	$('a.'+name+'_'+val).addClass('selected');
	global_hide = val;
}
