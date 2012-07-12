(function(window, $) {

$.getSassCommonURL = function(scssFilePath, params) {
	return $.getSassURL(wgCdnRootUrl, scssFilePath, params);
};

$.getSassLocalURL = function(scssFilePath, params) {
	return $.getSassURL(wgServer, scssFilePath, params);
};

$.getSassURL = function(rootURL, scssFilePath, params) {
	return rootURL + wgAssetsManagerQuery.
		replace('%1$s', 'sass').
		replace('%2$s', scssFilePath).
		replace('%3$s', encodeURIComponent($.param(params || window.sassParams))).
		replace('%4$d', wgStyleVersion);
};

$.getSassLocalURL = function(scssFilePath, params) {
	return wgAssetsManagerQuery.
		replace('%1$s', 'sass').
		replace('%2$s', scssFilePath).
		replace('%3$s', encodeURIComponent($.param(params || window.sassParams))).
		replace('%4$d', wgStyleVersion);
};

//see http://jamazon.co.uk/web/2008/07/21/jquerygetscript-does-not-cache
$.ajaxSetup({cache: true});

// replace stock function for getting rid of response-speed related issues in Firefox
// @see http://stackoverflow.com/questions/1130921/is-the-callback-on-jquerys-getscript-unreliable-or-am-i-doing-something-wrong
$.getScript = function(url, callback, failureFn) {
	return $.ajax({
		type: "GET",
		url: url,
		success: function(xhr) {
			if (typeof callback == 'function') {
				try {
					callback();
				}
				catch(e) {
					// TODO: is this fallback still needed? consider using promise pattern
					eval(xhr);
					callback();
					$().log('eval() fallback applied for ' + url, 'getScript');
				}
			}
		},
		error: (typeof failureFn == 'function' ? failureFn : $.noop),
		dataType: 'script'
	});
};

$.fn.log = function (msg, group) {
	if (typeof console != 'undefined') { /* JSlint ignore */
		if (group) {
			// nice formatting of objects with group prefix
			console.log((typeof msg != 'object' ? '%s: %s' : '%s: %o'), group, msg);
		}
		else {
			console.log(msg);
		}
	}
	return this;
};

$.fn.exists = function() {
	return this.length > 0;
};

// show modal dialog with content fetched via AJAX request
$.fn.getModal = function(url, id, options) {
	// get modal plugin

	// where should modal be inserted?
	var insertionPoint = (skin == "oasis") ? "body" : "#positioned_elements";

	// get modal content via AJAX
	$.get(url, function(html) {
		$(insertionPoint).append(html);

		// fire callbackBefore if provided
		if (typeof options == 'object' && typeof options.callbackBefore == 'function') {
			options.callbackBefore();
		}

		// makeModal() if requested
		if (typeof id == 'string') {
			$(id).makeModal(options);
			$().log('getModal: ' + id + ' modal made');
		}

		// fire callback if provided
		if (typeof options == 'object' && typeof options.callback == 'function') {
			options.callback();
		}
	});
};

// show modal popup with static title and content provided
$.showModal = function(title, content, options) {
	options = (typeof options != 'object') ? {} : options;

	var dialog, header, wrapper;

	$().log('showModal: plugin loaded');

	if (skin == 'oasis') {
		header = $('<h1>').html(title);
		dialog = $('<div>').html(content).prepend(header).appendTo('body');
	}
	else {
		dialog = $('<div class="modalContent">').html(content).attr('title', title).appendTo('#positioned_elements');
	}

	// fire callbackBefore if provided
	if (typeof options.callbackBefore == 'function') {
		options.callbackBefore();
	}

    wrapper = dialog.makeModal(options);

	// fire callback if provided
	if (typeof options.callback == 'function') {
		options.callback();
	}

    return wrapper;
};

// show modal version of confirm()
$.confirm = function(options) {
	// init options
	options = (typeof options != 'object') ? {} : options;
	options.id = 'WikiaConfirm';

	var html = '<p>' + (options.content || '') + '</p>' +
		'<div class="neutral modalToolbar">' +
		'<button id="WikiaConfirmCancel" class="wikia-button secondary">' + (options.cancelMsg || 'Cancel') + '</button>' +
		'<button id="WikiaConfirmOk" class="wikia-button">' + (options.okMsg || 'Ok') + '</button>' +
		'</div>';

	var insertionPoint = (skin == "oasis") ? "body" : "#positioned_elements";

	var dialog = $('<div>').
		appendTo(insertionPoint).
		html(html).
		attr('title', options.title || '');

	// fire callbackBefore if provided
	if (typeof options.callbackBefore == 'function') {
		options.callbackBefore();
	}

	// handle clicks on Ok
	$('#WikiaConfirmOk').click(function() {
		 $('#WikiaConfirm').closeModal();

		 // try to call callback when Ok is pressed
		 if (typeof options.onOk == 'function') {
			 options.onOk();
		 }
	});

	// handle clicks on Cancel
	$('#WikiaConfirmCancel').click(function() {
		$('#WikiaConfirm').closeModal();
	});

	dialog.makeModal(options);

	// fire callback if provided
	if (typeof options.callback == 'function') {
		options.callback();
	}
};

/* example of usage
$.showCustomModal('title', '<b>content</b>',
	{buttons: [
		{id:'ok', defaultButton:true, message:'OK', handler:function(){alert('ok');}},
		{id:'cancel', message:'Cancel', handler:function(){alert('cancel');}}
	]}
);
*/
// show modal popup with title, content and set ot buttons
$.showCustomModal = function(title, content, options) {
	options = (typeof options != 'object') ? {} : options;

	var buttons = '';
	if (options.buttons) {
		buttons = $('<div class="neutral modalToolbar"></div>');
		for (var buttonNo = 0; buttonNo < options.buttons.length; buttonNo++) {
			var button = '<a id="' + options.buttons[buttonNo].id + '" class="wikia-button' + (options.buttons[buttonNo].defaultButton ? '' : ' secondary') + '">' + options.buttons[buttonNo].message + '</a>';
			$(button).bind('click', options.buttons[buttonNo].handler).appendTo(buttons);
		}
	}

	var dialog = $('<div>').html(content).attr('title', title).append(buttons);

	var insertionPoint = (skin == "oasis") ? "body" : "#positioned_elements";
	$(insertionPoint).append(dialog);

	// fire callbackBefore if provided
	if (typeof options.callbackBefore == 'function') {
		options.callbackBefore();
	}

	var modal = dialog.makeModal(options);

	// fire callback if provided
	if (typeof options.callback == 'function') {
		options.callback(modal);
	}
};

// send POST request and parse returned JSON
$.postJSON = function(u, d, callback) {
	return $.post(u, d, callback, "json");
};

//see http://jquery-howto.blogspot.com/2009/09/get-url-parameters-values-with-jquery.html
$.extend({
	_urlVars: null,
	getUrlVars: function() {
		if($._urlVars === null){
			var hash,
			hashes = window.location.search.slice(window.location.search.indexOf('?') + 1).split('&');
			$._urlVars = {};
			for (var i = 0, j = hashes.length; i < j; i++) {
				hash = hashes[i].split('=');
				$._urlVars[hash[0]] = hash[1];
			}
		}
		return $._urlVars;
	},
	getUrlVar: function(name) {
		return $.getUrlVars()[name];
	}
});

// see http://www.texotela.co.uk/code/jquery/reverse/
$.fn.reverse = function() {
	return this.pushStack(this.get().reverse(), arguments);
};

$.fn.isChrome = function() {
	if ( $.browser.webkit && !$.browser.opera && !$.browser.msie && !$.browser.mozilla ) {
		var userAgent = navigator.userAgent.toLowerCase();
		if ( userAgent.indexOf("chrome") >  -1 ) {
			return true;
		}
	}
	return false;
};

// https://github.com/Modernizr/Modernizr/issues/84
$.fn.isTouchscreen = function() {
	return ('ontouchstart' in window);
}

/**
 * Tests whether first element in current collection is a child of node matching selector provided
 *
 * @return boolean
 * @param string a $ selector
 *
 * @author Macbre
 */
$.fn.hasParent = function(selector) {
	// use just the first element from current collection
	return this.first().parent().closest(selector).exists();
}

// macbre: page loading times (onDOMready / window onLoad)
$(function() {
	if (typeof wgNow != 'undefined') {
		var loadTime = (new Date()).getTime() - wgNow.getTime();
		$().log('DOM ready after ' + loadTime + ' ms', window.skin);
	}
});

$(window).bind('load', function() {
	if (typeof wgNow != 'undefined') {
		var loadTime = (new Date()).getTime() - wgNow.getTime();
		$().log('window onload after ' + loadTime + ' ms', window.skin);
	}
});

/**
 * @author Marcin Maciejewski <marcin@wikia-inc.com>
 *
 * Plugin for easy creating Ajax Loading visualization.
 * after using it selected elements content will apply proper css class
 * and in the middle of it throbber will be displayed.
 */
$.fn.startThrobbing = function() {
	this.append('<div class="wikiaThrobber"></div>');
};
$.fn.stopThrobbing = function() {
	this.find('.wikiaThrobber').remove();
};

/*
	Generate URL to thumbnail from different URL to thumbnail :)
	New URL has different parameters (fixed width and height)
 */
$.thumbUrl2ThumbUrl = function( url, type, width, height ) {
	if(url.indexOf('/thumb/') > 0) { // URL points to thumbnail
		// remove current resize part of thumbnail
		url = url.replace(/\/[0-9]+px\-/,'/');
	} else { // direct image link
		// convert URL to thumbnail type of URL
		url = url.replace('/images/','/images/thumb/');
		url += '/' + url.split('/').slice(-1)[0];
	}

	// add parameters to the URL
	var urlArray = url.split('/');
	var last = urlArray.slice(-1)[0];
	if(type=='video') {
		urlArray[urlArray.length-1] = width + 'x' + height + '-' + last + '.png';
	} else {
		urlArray[urlArray.length-1] = width + 'x' + height + 'x2-' + last + '.png';
	}
	url = urlArray.join('/');
	return url;
}

$.htmlentities = function ( s ) {
	return String(s).replace(/\&/g,'&'+'amp;').replace(/</g,'&'+'lt;')
    	.replace(/>/g,'&'+'gt;').replace(/\'/g,'&'+'apos;').replace(/\"/g,'&'+'quot;');
};

$.createClass = function (sc,o) {
	var constructor = o.constructor;
	if (typeof constructor != 'function' || constructor == Object.prototype.constructor) {
		constructor = function(){sc.apply(this,arguments);};
	}
	var bc = constructor;
	var f = function() {};
	f.prototype = sc.prototype || {};
	bc.prototype = new f();

	// macbre: support static members
	if (typeof o.statics == 'object') {
		bc = $.extend(bc, o.statics);
		delete o.statics;
	}

	for (var m in o) {
		bc.prototype[m] = o[m];
	}

	bc.prototype.constructor = bc;
	bc.superclass = sc.prototype;

	return bc;
};

$.proxyBind = function (fn,thisObject,baseArgs) {
	return function() {
		var args = baseArgs.slice(0).concat(Array.prototype.call(arguments,0));
		return fn.apply(thisObject,args);
	}
};

var Observable = $.createClass(Object, {
	constructor: function() {
		Observable.superclass.constructor.apply(this,arguments);
		this.events = {};
	},

	bind: function(e,cb,scope) {
		if (typeof e == 'object') {
			scope = cb;
			for (var i in e) {
				if (i !== 'scope') {
					this.bind(i,e[i],e.scope||scope);
				}
			}
		} else if ($.isArray(cb)) {
			for (var i=0;i<cb.length;i++) {
				this.bind(e,cb[i],scope);
			}
		} else {
			scope = scope || this;
			this.events[e] = this.events[e] || [];
			this.events[e].push({
				fn: cb,
				scope: scope
			});
		}
		return true;
	},

	unbind: function(e,cb,scope) {
		if (typeof e == 'object') {
			scope = cb;
			var ret = false;
			for (var i in e) {
				if (i !== 'scope') {
					ret = this.unbind(i,e[i],e.scope||scope) || ret;
				}
			}
			return ret;
		} else if ($.isArray(cb)) {
			var ret = false;
			for (var i=0;i<cb.length;i++) {
				ret = this.unbind(e,cb[i],scope) || ret;
			}
			return ret;
		} else {
			if (!this.events[e]) {
				return false;
			}
			scope = scope || this;
			for (var i in this.events[e]) {
				if (this.events[e][i].fn == cb && this.events[e][i].scope == scope) {
					delete this.events[e][i];
					return true;
				}
			}
			return false;
		}
	},

	on: function(e,cb) {
		this.bind.apply(this,arguments);
	},

	un: function(e,cb) {
		this.unbind.apply(this,arguments);
	},

	relayEvents: function(o,e,te) {
		te = te || e;
		o.bind(e,function() {
			var a = [te].concat(arguments);
			this.fire.apply(this,a);
		},this);
	},

	fire: function(e) {
		var a = Array.prototype.slice.call(arguments,1);
		if (!this.events[e])
			return true;
		var ee = this.events[e];
		for (var i=0;i<ee.length;i++) {
			if (typeof ee[i].fn == 'function') {
				var scope = ee[i].scope || this;
				if (ee[i].fn.apply(scope,a) === false) {
					return false;
				}
			}
		}
		return true;
	},

	proxy: function(func) {
		return $.proxy(func, this);
	},

	debugEvents: function( list ) {
		var fns = list ? list : ['bind','unbind','fire','relayEvents'];
		for (var i=0;i<fns.length;i++) {
			(function(fn){
				if (typeof this['nodebug-'+fn] == 'undefined') {
					var f = this['nodebug-'+fn] = this[fn];
					this[fn] = function() {
						window.console && console.log && console.log(this,fn,arguments);
						return f.apply(this,arguments);
					}
				}
			}).call(this,fns[i]);
		}
	}
});

var GlobalTriggers = (function(){
	var GlobalTriggersClass = $.createClass(Observable,{

		fired: null,

		constructor: function() {
			GlobalTriggersClass.superclass.constructor.apply(this);
			this.fired = {};
		},

		bind: function(e,cb,scope) {
			GlobalTriggersClass.superclass.bind.apply(this,arguments);
			if (typeof e == 'object' || $.isArray(cb)) {
				return;
			}

			if (typeof this.fired[e] != 'undefined') {
				var a = this.fired[e].slice(0);
				setTimeout(function(){
					for (i=0;i<a.length;i++) {
						cb.apply(scope||window,a[i]);
					}
				},10);
			}
		},

		fire: function(e) {
			var a = Array.prototype.slice.call(arguments,1);
			this.fired[e] = this.fired[e] || [];
			this.fired[e].push(a);
			GlobalTriggersClass.superclass.fire.apply(this,arguments);
		}

	});
	return new GlobalTriggersClass();
})();

var Timer = $.createClass(Object,{
	callback: null,
	timeout: 1000,
	timer: null,

	constructor: function ( callback, timeout ) {
		this.callback = callback;
		this.timeout = (typeof timeout == 'number') ? timeout : this.timeout;
	},

	run: function () {
		this.callback.apply(window);
	},

	start: function ( timeout ) {
		this.stop();
		timeout = (typeof timeout == 'number') ? timeout : this.timeout;
		this.timer = setTimeout(this.callback,timeout);
	},

	stop: function () {
		if (this.timer != null) {
			clearTimeout(this.timer);
			this.timer = null;
		}
	}
});

$.extend(Timer, {
	create: function( callback, timeout ) {
		var timer = new Timer(callback,timeout);
		return timer;
	},

	once: function ( callback, timeout ) {
		var timer = Timer.create(callback,timeout);
		timer.start();
		return timer;
	}
});

//Extension to $.support to detect browsers/platforms that don't support
//CSS directive position:fixed
if($.support){
	$.support.fileUpload = $.support.keyboardShortcut = $.support.positionFixed = !( navigator.platform in {'iPad':'', 'iPhone':'', 'iPod':''} || (navigator.userAgent.match(/android/i) != null));
}

$.openPopup = function(url, name, moduleName, width, height) {
	if (wgUserName) {
		window.open(
			url,
			name,
			'width='+width+',height='+height+',menubar=no,status=no,location=no,toolbar=no,scrollbars=no,resizable=yes'
		);
	}
	else {
		showComboAjaxForPlaceHolder(false, "", function() {
			AjaxLogin.doSuccess = function() {
				$('.modalWrapper').children().not('.close').not('.modalContent').not('h1').remove();
				$('.modalContent').load(
					wgServer +
					wgScript +
					'?action=ajax&rs=moduleProxy&moduleName=' + moduleName + '&actionName=AnonLoginSuccess&outputType=html'
				);
			}
		}, false, message); // show the 'login required for this action' message.
	}
}

$(function() {
	//beacon_id cookie
	if ( window.beacon_id ) {
		$.cookies.set( 'wikia_beacon_id', window.beacon_id, { path: wgCookiePath, domain: wgCookieDomain });
	}
	window.wgWikiaDOMReady = true;	// for selenium tests
});

// These functions are deprecated, but we will keep aliases around for old code and user scripts
$.toJSON = JSON.stringify; /* JSlint ignore */
$.evalJSON = $.secureEvalJSON = JSON.parse; /* JSlint ignore */

// Exports
window.GlobalTriggers = GlobalTriggers;
window.Observable = Observable;
window.Timer = Timer;

})(window, jQuery);