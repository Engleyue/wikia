//see http://jamazon.co.uk/web/2008/07/21/jquerygetscript-does-not-cache
$.ajaxSetup({cache: true});

// replace stock function for getting rid of response-speed related issues
jQuery.getScript = function(url, callback) {
	jQuery.ajax({
		async: false,
		type: "GET",
		url: url,
		data: null,
		success: callback,
		dataType: 'script'
	});
}

jQuery.fn.log = function (msg, group) {
	if (typeof console != 'undefined') {
		if (group) {
			// nice formatting of objects with group prefix
			console.log((typeof msg != 'object' ? '%s: %s' : '%s: %o'), group, msg);
		}
		else {
			console.log(msg);
		}
	}
	else if (typeof opera != 'undefined') {
		opera.postError((group ? (group + ': ') : '') + msg);
	}
	return this;
};

jQuery.fn.exists = function() {
	return this.length > 0;
}

// show modal dialog with content fetched via AJAX request
jQuery.fn.getModal = function(url, id, options) {
	// get modal plugin
	$.loadModalJS(function() {
		$().log('getModal: plugin loaded');

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
	});
}

// show modal popup with static title and content provided
jQuery.showModal = function(title, content, options) {
	options = (typeof options != 'object') ? {} : options;

	$.loadModalJS(function() {
		$().log('showModal: plugin loaded');

		if (skin == 'oasis') {
			var header = $('<h1>').html(title);
			var dialog = $('<div>').html(content).prepend(header).appendTo('body');
		}
		else {
			var dialog = $('<div class="modalContent">').html(content).attr('title', title).appendTo('#positioned_elements');
		}

		// fire callbackBefore if provided
		if (typeof options.callbackBefore == 'function') {
			options.callbackBefore();
		}

		dialog.makeModal(options);

		// fire callback if provided
		if (typeof options.callback == 'function') {
			options.callback();
		}
	});
}

// show modal version of confirm()
jQuery['confirm'] = function(options) {
	// init options
	options = (typeof options != 'object') ? {} : options;
	options.id = 'WikiaConfirm';

	$.loadModalJS(function() {
		$().log('confirm: plugin loaded');

		var html = '<p>' + (options.content || '') + '</p>' +
			'<div class="neutral modalToolbar">' +
			'<a id="WikiaConfirmCancel" class="wikia-button secondary">' + (options.cancelMsg || 'Cancel') + '</a>' +
			'<a id="WikiaConfirmOk" class="wikia-button">' + (options.okMsg || 'Ok') + '</a>' +
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
	});
}

/* example of usage
$.showCustomModal('title', '<b>content</b>',
	{buttons: [
		{id:'ok', default:true, message:'OK', handler:function(){alert('ok');}},
		{id:'cancel', message:'Cancel', handler:function(){alert('cancel');}}
	]}
);
*/
// show modal popup with title, content and set ot buttons
jQuery.showCustomModal = function(title, content, options) {
	options = (typeof options != 'object') ? {} : options;

	$.loadModalJS(function() {
		$().log('showCustomModal: plugin loaded');

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

		dialog.makeModal(options);

		// fire callback if provided
		if (typeof options.callback == 'function') {
			options.callback();
		}
	});
}

//load jquery.wikia.modal if necessary and run callback function
jQuery.loadModalJS = function(callback) {
	//check if we already loaded jquery.wikia.modal.js
	if (typeof $().makeModal != 'function') {
		$().log('modal plugin loading...');
		$.getScript(stylepath + '/common/jquery/jquery.wikia.modal.js?' + wgStyleVersion, callback);
	} else {
		callback();
	}
}

// send POST request and parse returned JSON
jQuery.postJSON = function(u, d, callback) {
	return jQuery.post(u, d, callback, "json");
}

// load YUI if not yet loaded
$.loadYUI = function(callback) {
	if (typeof YAHOO == 'undefined') {
		if ( (typeof isYUIloading != 'undefined') && isYUIloading ) {
			$().log('is loading add call back to line', 'YUI');
			loadYUICallBackFIFO.push(callback);
			return true;
		}

		isYUIloading = true;
		loadYUICallBackFIFO = new Array();
		loadYUICallBackFIFO.push(callback)

		$().log('loading on-demand', 'YUI');

		var YUIloadingCallBack = function(){
			$().log('loaded', 'YUI');

			for (var i = 0; i < loadYUICallBackFIFO.length; i++ ){
				loadYUICallBackFIFO[i].caller = $.loadYUI;
				loadYUICallBackFIFO[i]();
			}
			loadYUICallBackFIFO = null;
			isYUIloading = false;
		};
		$().log('rq start', 'YUI');

		//getScript() is not async
		jQuery.ajax({
			async: false,
			type: "GET",
			url: wgYUIPackageURL,
			data: null,
			success: YUIloadingCallBack,
			dataType: 'script'
		});

	} else {
		$().log('already loaded', 'YUI');
		callback();
	}
}

// load jQuery UI library if not yet loaded
$.loadJQueryUI = function(callback) {
	if (typeof jQuery.ui === 'undefined') {
		$().log('loading', 'jQuery UI');

		$.getScript(stylepath + '/common/jquery/jquery-ui-1.7.2.custom.js?' + wgStyleVersion, function() {
			$().log('loaded', 'jQuery UI');
			if(typeof callback === 'function') callback();
		});
	}
	else {
		if(typeof callback === 'function') callback();
	}
}

$.loadJQueryAutocomplete = function(callback) {
	if (typeof $().autocomplete === 'undefined') {
		$().log('loading', 'jQuery Autocomplete');

		$.getScript(stylepath + '/common/jquery/jquery.autocomplete.js?' + wgStyleVersion, function() {
			$().log('loaded', 'jQuery Autocomplete');

			if(typeof callback === 'function') callback();
		});
	}
	else {
		if(typeof callback === 'function') callback();
	}
}

$.loadJQueryAIM = function(callback) {
	if (typeof jQuery.AIM === 'undefined') {
		$().log('loading', 'jQuery AIM');

		$.getScript(stylepath + '/common/jquery/jquery.aim.js?' + wgStyleVersion, function() {
			$().log('loaded', 'jQuery AIM');

			if(typeof callback === 'function') callback();
		});
	}
	else {
		if(typeof callback === 'function') callback();
	}
}

/*
Copyright (c) 2008, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 2.5.2
*/

/**
 * Returns the current height of the viewport.
 * @method getViewportHeight
 * @return {Int} The height of the viewable area of the page (excludes scrollbars).
 */
$.getViewportHeight = function() {
    var height = self.innerHeight; // Safari, Opera
    var mode = document.compatMode;

    if ( (mode || $.browser.msie) && !$.browser.opera ) { // IE, Gecko
	height = (mode == 'CSS1Compat') ?
		document.documentElement.clientHeight : // Standards
		document.body.clientHeight; // Quirks
    }

    return height;
};

/**
 * Returns the current width of the viewport.
 * @method getViewportWidth
 * @return {Int} The width of the viewable area of the page (excludes scrollbars).
 */

$.getViewportWidth = function() {
    var width = self.innerWidth;  // Safari
    var mode = document.compatMode;

    if (mode || $.browser.msie) { // IE, Gecko, Opera
	width = (mode == 'CSS1Compat') ?
		document.documentElement.clientWidth : // Standards
		document.body.clientWidth; // Quirks
    }
    return width;
};

/**
 * Returns the value of param from url.
 * @method getUrlVal
 * @return mixed or "".
 */


$.getUrlVal = function ( name )
{
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null )
		return "";
	else
		return results[1];
}

/**
* Finds the event in the window object, the caller's arguments, or
* in the arguments of another method in the callstack.  This is
* executed automatically for events registered through the event
* manager, so the implementer should not normally need to execute
* this function at all.
* @method getEvent
* @param {Event} e the event parameter from the handler
* @param {HTMLElement} boundEl the element the listener is attached to
* @return {Event} the event
* @static
*/
$.getEvent = function(e, boundEl) {
	var ev = e || window.event;

	if (!ev) {
		var c = this.getEvent.caller;
		while (c) {
			ev = c.arguments[0];
			if (ev && Event == ev.constructor) {
				break;
			}
			c = c.caller;
		}
	}

	return ev;
};

//see http://jquery-howto.blogspot.com/2009/09/get-url-parameters-values-with-jquery.html
$.extend({
	getUrlVars: function() {
		var vars = [], hash;
		var hashes = window.location.search.slice(window.location.search.indexOf('?') + 1).split('&');
		for (var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	},
	getUrlVar: function(name) {
		return $.getUrlVars()[name];
	}
});

// see http://www.texotela.co.uk/code/jquery/reverse/
jQuery.fn.reverse = function() {
	return this.pushStack(this.get().reverse(), arguments);
};

jQuery.fn.isChrome = function() {
	if ( $.browser.webkit && !$.browser.opera && !$.browser.msie && !$.browser.mozilla ) {
		var userAgent = navigator.userAgent.toLowerCase();
		if ( userAgent.indexOf("chrome") >  -1 ) {
			return true;
		}
	}
	return false;
};

/**
 * Tests whether first element in current collection is a child of node matching selector provided
 *
 * @return boolean
 * @param string a jQuery selector
 *
 * @author Macbre
 */
$.fn.hasParent = function(selector) {
	// use just the first element from current collection
	return this.first().parent().closest(selector).exists();
}

// RT #19369: TabView
$(function() {
	if (typeof window.__FlyTabs == 'undefined') {
		return;
	}

	if(skin == 'oasis') {
		importStylesheetURI(wgExtensionsPath+ '/wikia/TabView/TabView.css?' + wgStyleVersion);
	}

	$.getScript(stylepath + '/common/jquery/jquery.flytabs.js?' + wgStyleVersion, function() {
		$().log(window.__FlyTabs, 'TabView');

		for(t=0; t<window.__FlyTabs.length; t++) {
			var tab = window.__FlyTabs[t];

			$('#flytabs_' + tab.id).flyTabs.config({align: 'none', effect: 'no'});

			for (s=0; s<tab.options.length; s++) {
				$('#flytabs_' + tab.id).flyTabs.addTab(tab.options[s]);
			}
		}
	});
});

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
 * @author Federico "Lox" Lucignano <federico@wikia-inc.com>
 *
 * HTML5 placeholder attribute fallback for HTML5-disabled browsers.
 * A placeholder CSS class should be defined (use the forms mixin in Oasis)
 */

$(function() {
	$('input[placeholder]').placeholder();
});

jQuery.fn.placeholder = function() {
	 //feature detection
	var hasNativeSupport = 'placeholder' in document.createElement('input');

	if(!hasNativeSupport){
		this.each(function() {
			var input = $(this);
			var text = input.attr('placeholder');

			if(input.val() == ''){
				input
					.addClass('placeholder')
					.val(text);
			}

			input.focus(function(){
				if(input.val() == text){
					input.val('');
				}

				input.removeClass('placeholder');
			});

			input.blur(function(){
				if(input.val() == ''){
					input
						.addClass('placeholder')
						.val(text);
				}
			});

			//clear the field is a submit event is fired somewhere around here
			input.closest('form').submit(function(){
				if(input.val() == text){
					input.val('');
				}
			});
		});
	}
}

$.htmlentities = function ( s ) {
	return String(s).replace(/\&/g,'&'+'amp;').replace(/</g,'&'+'lt;')
    	.replace(/>/g,'&'+'gt;').replace(/\'/g,'&'+'apos;').replace(/\"/g,'&'+'quot;');
}

$.extend({
	createClass: function (sc,o) {
//		$().log(sc,'createClass-superclass');
//		$().log(o,'createClass-overrides');
		var constructor = o.constructor;
		if (typeof constructor != 'function' || constructor == Object.prototype.constructor) {
			constructor = function(){sc.apply(this,arguments);};
//			$().log('constructor created','createClass');
		}
		var bc = constructor;
		var f = function() {};
		f.prototype = sc.prototype || {};
		bc.prototype = new f();
		for (var m in o)
			bc.prototype[m] = o[m];
		bc.prototype.constructor = bc;
		bc.superclass = sc.prototype;
		return bc;
	}
});

Observable = $.createClass(Object,{
	constructor: function() {
		Observable.superclass.constructor.apply(this,arguments);
		this.events = {};
	},

	bind: function(e,cb,scope) {
		if (typeof e == 'object') {
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
			scope == scope || this;
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
			scope == scope || this;
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
			return;
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
	}

});

GlobalTriggers = (function(){
	var GlobalTriggersClass = $.createClass(Observable,{

		fired: null,

		constructor: function() {
			GlobalTriggersClass.superclass.constructor.apply(this);
			this.fired = {};
		},

		bind: function(e,cb) {
			GlobalTriggersClass.superclass.bind.apply(this,arguments);
			if (typeof e == 'object') {
				return;
			}

			if (typeof this.fired[e] != 'undefined') {
				var a = this.fired[e].slice(0);
				setTimeout(function(){
					for (i=0;i<a.length;i++) {
						cb.apply(window,a[i]);
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

Timer = $.createClass(Object,{

	callback: null,
	timeout: 1000,
	timer: null,

	constructor: function ( callback, timeout ) {
		this.callback = callback;
		this.timeout = timeout || this.timeout;
	},

	run: function () {
		this.callback.apply(window);
	},

	start: function ( timeout ) {
		this.stop();
		timeout = timeout || this.timeout;
		this.timer = setTimeout(this.callback,timeout);
	},

	stop: function () {
		if (this.timer != null) {
			clearTimeout(this.timer);
			this.timer = null;
		}
	}

});

$.extend(Timer,{

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

/**
 * Fetches given list of JS/CSS files and then fires callback (RT #70163)
 *
 * @author macbre
 */
jQuery.getResources = function(resources, callback) {
	var isJs = /.js(\?(.*))?$/,
		isCss = /.css(\?(.*))?$/,
		remaining = resources.length;

	var complete = function() {
		remaining--;

		// all files have been downloaded
		if (remaining == 0) {
			if (typeof callback == 'function') {
				callback();
			}
		}
	};

	// download files
	for (var n=0; n<resources.length; n++) {
		var resource = resources[n];

		// "loader" function: $.loadYUI, $.loadJQueryUI
		if (typeof resource == 'function') {
			resource.call(jQuery, complete);
		}
		// CSS/JS files
		else if (isJs.test(resource)) {
			$.getScript(resource, complete);
		}
		else if (isCss.test(resource)) {
			$.getCSS(resource, complete);
		}
	}
};
