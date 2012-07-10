var WallBackendBridge = $.createClass(Observable, {
	constructor: function() {
		WallNewMessageForm.superclass.constructor.apply(this, arguments);
	},

	loadPage: function(page, pagenumber, callback) {
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'getCommentsPage',
			type: 'GET',
			format: 'json',
			data: {
				page: pagenumber,
				pagetitle: page['title'],
				pagenamespace: page['namespace']
			},
			callback: this.proxy(function(data) {
				var html = $(data.html),
					page = html.find('.comments'),
					pagination = html.find('.Pagination');

				if ($.isFunction(callback)) {
					callback(page, pagination);
				}

				this.fire('pageLoaded', page, pagination);
			})
		});
	},

	postNew: function(page, title, body, convertToFormat, callback) {
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'postNewMessage',
			data: {
				body: body,
				messagetitle: title,

				pagetitle: page['title'],
				pagenamespace: page['namespace'],
				convertToFormat: convertToFormat
			},
			callback: this.proxy(function(data) {
				var newmsg = $(data.message);

				if ($.isFunction(callback)) {
					callback(newmsg);
				}

				this.fire('newPosted', newmsg);
			})
		});
	},

	postReply: function(page, body, convertToFormat, parent, callback) {
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'replyToMessage',
			data: {
				body: body,
				parent: parent,

				pagetitle: page['title'],
				pagenamespace: page['namespace'],
				convertToFormat: convertToFormat
			},
			callback: this.proxy(function(data) {
				var newmsg = $(data.message);

				if ($.isFunction(callback)) {
					callback(newmsg);
				}

				this.fire('postReply', newmsg);
			})
		});
	},

	cancelEdit: function(username, id, callback) {
		if ($.isFunction(callback)) {
			callback(newmsg);
		}

		this.fire('editCanceled', newmsg);
	},

	loadEditData: function(page, id, mode, convertToFormat, callback) {
		this.fire('beforeEditDataLoad', id);

		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'editMessage',
			format: 'json',
			data: {
				msgid: id,
				pagetitle: page['title'],
				pagenamespace: page['namespace'],
				convertToFormat: convertToFormat
			},
			callback: this.proxy(function(data) {

				// backend error lets reload the page
				if (data.status == false && data.forcereload == true) {
					var url = window.location.href;

					if (url.indexOf('#') >= 0) {
						url = url.substring(0, url.indexOf('#'));
					}

					window.location.href = url + '?reload=' + Math.floor(Math.random() * 999);
				}

				data.mode = mode;
				data.id = id;

				if ($.isFunction(callback)) {
					callback(data);
				}

				this.fire('editDataLoaded', data);
			})
		});
	},

	saveEdit: function(page, id, title, body, isreply, convertToFormat, callback) {
		$.nirvana.sendRequest({
			controller: 'WallExternalController',
			method: 'editMessageSave',
			format: 'json',
			data: {
				msgid: id,
				newtitle: title,
				newbody: body,
				isreply: isreply,
				pagetitle: page['title'],
				pagenamespace: page['namespace'],
				convertToFormat: convertToFormat
			},
			callback: this.proxy(function(data) {
				if ($.isFunction(callback)) {
					callback(data);
				}

				this.fire('editSaved', data);
			})
		});
	}
});
