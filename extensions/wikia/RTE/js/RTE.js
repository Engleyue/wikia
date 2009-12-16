window.RTE = {
	// configuration
	config: {
		'alignableElements':  ['p', 'div', 'td' ,'th'],
		'baseFloatZIndex': 500,
		'bodyId': 'bodyContent',
		'coreStyles_bold': {element: 'b', overrides: 'strong'},
		'coreStyles_italic': {element: 'i', overrides: 'em'},
		'customConfig': '',
		'dialog_backgroundCoverColor': '#000',
		'disableObjectResizing': true,
		'entities': false,
		'format_tags': 'p;h2;h3;h4;h5;pre',
		'height': 400,
		'language': window.wgUserLanguage,
		'removePlugins': 'about,elementspath,filebrowser,flash,forms,horizontalrule,image,justify,link,maximize,newpage,pagebreak,save,scayt,wsc',
		'resize_enabled': false,
		'skin': 'wikia',
		'startupFocus': true,
		'theme': 'wikia',
		'toolbar': 'Wikia',
		'toolbarCanCollapse': false
	},

	// refernece to current CK instance
	instance: false,

	// editor instance ID
	instanceId: window.RTEInstanceId,

	// is instance filly loaded?
	loaded: false,

	// time of CK load
	loadTime: false,

	// list of our RTE custom plugins (stored in js/plugins) to be loaded on editor init
	plugins: [
		'comment',
		'dialog',
		'dragdrop',
		'edit-buttons',
		'entities',
		'first-run-notice',
		'justify',
		'link',
		'linksuggest',
		'media',
		'paste',
		'placeholder',
		'signature',
		'source',
		'template',
		'temporary-save',
		'toolbar',
		'tools',
		'track',
		'widescreen'
	],

	// use firebug / opera console to log events / dump objects
	log: function(msg) {
		$().log(msg, 'RTE');
	},

	// send AJAX request
	ajax: function(method, params, callback) {
		if (typeof params != 'object') {
			params = {};
		}
		params.method = method;

		jQuery.post(window.wgScript + '?action=ajax&rs=RTEAjax', params, function(data) {
			RTE.log(data);

			if (typeof callback == 'function') {
				callback(data);
			}
		}, 'json');
	},

	// track events
	// TODO: use GA event tracking
	// @see http://code.google.com/intl/pl-PL/apis/analytics/docs/tracking/eventTrackerGuide.html
	track: function(action, label, value) {
		// get method attributes
		var args = ['ckeditor']; for (i=0; i < arguments.length; i++) args.push(arguments[i]);

		// pageTracker._trackEvent.apply(window, args);
		//RTE.log('track - ' + args.join('/'));

		WET.byStr(args.join('/'));
	},

	// start editor in mode provided
	init : function(mode) {
		// cache buster used by CK when loading CSS/JS
		CKEDITOR.timestamp = window.wgStyleVersion;

		// allow <img> (used for placeholders) to be placed inside <pre>
		CKEDITOR.dtd.pre.img = 1;

		// allow <center> to be placed inside <p>
		CKEDITOR.dtd.p.center = 1;

		// set startup mode
		RTE.config.startupMode = mode;

		// register and load RTE plugins
		RTE.loadPlugins();

		// add and position wrapper for extra RTE stuff
		$('<div id="RTEStuff" />').appendTo('body');
		RTE.repositionRTEStuff();
		$(window).bind('resize', RTE.repositionRTEStuff);

		// base colors: use color / background-color from .color1 CSS class
		var colorPicker = $('<div>').addClass('color1').appendTo('#RTEStuff').hide();
		RTE.config.baseBackgroundColor = colorPicker.css('backgroundColor');
		RTE.config.baseColor = colorPicker.css('color');

		// make textarea wysiwygable
		CKEDITOR.replace('wpTextbox1', RTE.config);

		// set editor instance
		RTE.instance = CKEDITOR.instances.wpTextbox1;

		// load CSS files
		RTE.loadCss();

		// register event handlers
		//
		CKEDITOR.on('instanceReady', RTE.onEditorReady);

		RTE.instance.on('beforeModeUnload', function() {
			RTE.onBeforeModeSwitch(RTE.instance.mode);
		});

		RTE.instance.on('wysiwygModeReady', RTE.onWysiwygModeReady);

		// regenerate placeholders after each redo/undo
		RTE.instance.on('afterUndo', function() {
			RTE.instance.fire('wysiwygModeReady');
		});
		RTE.instance.on('afterRedo', function() {
			RTE.instance.fire('wysiwygModeReady');
		});

		// event fired when Widescreen button in pressed
		RTE.instance.on('widescreen', RTE.onWidescreen);

		// CK is loading...
		RTE.loading(true);
	},

	// load extra CSS files
	loadCss: function() {
		var css = [
			window.stylepath + '/monobook/main.css',
			CKEDITOR.basePath + '../css/RTEcontent.css',
			window.RTEMWCommonCss
		];
		for (var n=0; n<css.length; n++) {
			var cb = ( (css[n].indexOf('?') > -1) ? '' : ('?' + CKEDITOR.timestamp) );
			RTE.instance.addCss('@import url(' + css[n] + cb + ');');
		}

		// disable object resizing in IE
		if (CKEDITOR.env.ie && RTE.config.disableObjectResizing) {
			// IMPORTANT! use local path
			RTE.instance.addCss('img {behavior:url(' + RTE.constants.localPath + '/css/behaviors/disablehandles.htc)}');
		}
	},

	// register and load custom RTE plugins
	loadPlugins: function() {
		var extraPlugins= [];

		for (var p=0; p < RTE.plugins.length; p++) {
			var plugin = RTE.plugins[p];

			extraPlugins.push('rte-' + plugin);

			// register plugin
			CKEDITOR.plugins.addExternal('rte-' + plugin, CKEDITOR.basePath + '../js/plugins/' + plugin + '/');
		}

		// load custom RTE plugins
		RTE.config.extraPlugins = extraPlugins.join(',');
	},

	// final setup
	onEditorReady: function() {
		// remove HTML indentation
		RTE.instance.dataProcessor.writer.indentationChars = '';
		RTE.instance.dataProcessor.writer.lineBreakChars = '';

		// set class for body indicating current editor mode
		$('body').addClass('rte_' + RTE.instance.mode);

		// on submit set value of RTEMode hidden field
		$('#editform').bind('submit', function() {
			$('#RTEMode').attr('value', RTE.instance.mode);
		});
		// do the same for clicks on preview button (event used by AjaxLogin on edit page)
		$('#wpPreview').bind('click', function() {
			$('#RTEMode').attr('value', RTE.instance.mode);
		});

		// reposition #RTEStuff
		RTE.repositionRTEStuff();

		// ok, we're done!
		RTE.loaded = true;
		RTE.loading(false);

		// calculate load time
		RTE.loadTime = ( (new Date()).getTime() - window.wgRTEStart.getTime() ) / 1000;

		RTE.log('CKeditor (' +
			(window.RTEDevMode ? 'in development mode' : CKEDITOR.revision + ' build ' + CKEDITOR.version) +
			') is ready in "' + RTE.instance.mode + '" mode (loaded in ' + RTE.loadTime + ' s)');

		// editor resizing
		if (typeof window.EditEnhancements == 'function') {
			EditEnhancements();
		}

		// fire custom event for "track" plugin
		RTE.instance.fire('RTEready');
	},

	// extra setup of <body> wrapping editing area in wysiwyg mode
	onWysiwygModeReady: function() {
		RTE.log('onWysiwygModeReady');

		var body = RTE.getEditor();

		// set ID, so CSS rules from MW can be applied
		body.attr('id', RTE.instance.config.bodyId);
	},

	// reposition of #RTEStuff div when Widescreen button is pressed
	onWidescreen: function() {
		RTE.repositionRTEStuff();
	},

	// reposition #RTEStuff div
	repositionRTEStuff: function() {
		var editorPosition = $('#editform').offset();

		$('#RTEStuff').css({
			'left': parseInt(editorPosition.left) + 'px',
			'top': parseInt(editorPosition.top + $('#cke_top_wpTextbox1').height()) + 'px'
		});
	},

	// get jQuery object wrapping body of editor' iframe
	getEditor: function() {
		return jQuery(RTE.instance.document.$.body);
	},

	// set loading state of an editor (show progress icon)
	loading: function(loading) {
		if (loading) {
			$('body').addClass('RTEloading');
		}
		else {
			$('body').removeClass('RTEloading');
		}
	},

	// handle mode switching (prepare data)
	onBeforeModeSwitch: function(mode) {
		RTE.log('switching from "' + mode +'" mode');

		// get HTML / wikitext
		var content = RTE.instance.getData();
		//RTE.log(content);

		// show loading indicator
		RTE.loading(true);

		switch (mode) {
			case 'wysiwyg':
				RTE.ajax('html2wiki', {html: content, title: window.wgPageName}, function(data) {
					RTE.instance.setData(data.wikitext);
					RTE.loading(false);

					// body CSS class change and tracking
					$('body').addClass('rte_source').removeClass('rte_wysiwyg');
					RTE.track('switchMode', 'wysiwyg2source');
				});
				break;

			case 'source':
				RTE.ajax('wiki2html', {wikitext: content, title: window.wgPageName}, function(data) {
					if (data.edgecase) {
						RTE.log('edgecase found!');
						RTE.tools.alert(data.edgecase.info.title, data.edgecase.info.content);

						// stay in source mode
						RTE.instance.forceSetMode('source', content);
						RTE.loading(false);

						// tracking
						RTE.track('switchMode', 'edgecase', data.edgecase.type);
						return;
					}

					RTE.instance.setData(data.html);

					setTimeout(function() {
						RTE.loading(false);

						// body CSS class change and tracking
						$('body').addClass('rte_wysiwyg').removeClass('rte_source');
						RTE.track('switchMode', 'source2wysiwyg');
					}, 150);
				});
				break;
		}
	},

	// constants (taken from global JS variables added by RTE backend)
	constants: {
		localPath: window.RTELocalPath,
		urlProtocols: window.RTEUrlProtocols,
		validTitleChars: window.RTEValidTitleChars
	},

	// messages to be used in JS code
	messages: window.RTEMessages
};

//
// extend CK config
//

/**
 * ID which will be assigned to <body> of editing area (added by Wikia)
 * @type string
 * @default ''
 * @example
 * config.bodyId = 'content';
*/
CKEDITOR.config.bodyId = '';

/**
 * Base UI background color
 * @type string
 * @default '#ddd'
 * @example
 * config.bodyId = '#36C';
*/
CKEDITOR.config.baseBackgroundColor = '#ddd';

/**
 * Base UI color
 * @type string
 * @default '#000'
 * @example
 * config.bodyId = '#fff';
*/
CKEDITOR.config.baseColor = '#000';

// Wikia toolbar
CKEDITOR.config.toolbar_Wikia =
[
	['Bold','Italic','Underline','Strike', 'Format'],
	['Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight'],
	['BulletedList','NumberedList'],
	['Link','Unlink'],
	['Image','Video'],
	['Table'],
	['Signature'],
	['Template'],
	['Undo','Redo'],
	['Widescreen'],
	['Source']
];

//
// extend CK core objects
//

// override this method, so we can ignore attributes matching _rte_*
CKEDITOR.dom.element.prototype.hasAttributesOriginal = CKEDITOR.dom.element.prototype.hasAttributes;

CKEDITOR.dom.element.prototype.hasAttributes = function() {
	var ret = this.hasAttributesOriginal();

	// check for internal RTE attributes
	if (ret == true) {
		var internalAttribs = ['_rte_washtml', '_rte_line_start', '_rte_empty_lines_before'];

		for (i=0; i<internalAttribs.length; i++) {
			if (this.hasAttribute(internalAttribs[i])) {
				ret = false;
			}
		}
	}

	return ret;
}

// catch requests for language JS files
CKEDITOR.langRegExp = /lang\/(\w+).js/;

// load CK files from _source subdirectory
CKEDITOR.getUrl = function( resource ) {

	// catch requests for /lang/xx.js
	if (CKEDITOR.langRegExp.test(resource)) {
		var matches = resource.match(CKEDITOR.langRegExp);
		var lang = matches[1];

		RTE.log('language "' + lang + '" requested');

		// fetch JSON with language definition from backend
		var url = window.wgServer + wgScript + '?action=ajax&rs=RTEAjax&method=i18n&uselang=' + lang +
			'&cb=' + window.wgMWrevId + '-' + window.wgStyleVersion;

		return url;
	}

	// If this is not a full or absolute path.
	if ( resource.indexOf('://') == -1 && resource.indexOf( '/' ) !== 0 ) {
		// Wikia: add _source subdirectory
		if ( resource.indexOf('_source') == -1 ) {
			resource = '_source/' + resource;
		}

		resource = this.basePath + resource;
	}

	// Add the timestamp, except for directories.
	if ( this.timestamp && resource.charAt( resource.length - 1 ) != '/' ) {
		resource += ( resource.indexOf( '?' ) >= 0 ? '&' : '?' ) + this.timestamp;
	}

	return resource;
}

// forced mode switch (don't send AJAX request, use provided html/wikitext)
CKEDITOR.editor.prototype.forceSetMode = function(mode, data) {
	// following code is based on "editingblock" plugin from CK core
	var modeEditor = this._.modes && this._.modes[ mode || this.mode ];
	var holderElement = this.getThemeSpace('contents');

	modeEditor.load(holderElement, data);
	this.mode = mode;

	// set correct body class
	$('body').removeClass('rte_wysiwyg rte_source').addClass('rte_' + mode);
}

//
// extend jQuery
//

// get meta data from given node
jQuery.fn.getData = function() {
	var json = this.attr('_rte_data');
	if (!json) {
		return {};
	}

	// decode JSON
	json = decodeURIComponent(json);

	var data = $.secureEvalJSON(json) || {};
	return data;
}

// set meta data for given node
jQuery.fn.setData = function(key, value) {
	var data = {};

	// prepare data to be stored
	if (typeof key == 'object') {
		data = key;
	}
	else if (typeof key == 'string') {
		data[key] = value;
	}

	// read current data stored in node and merge with data
	data = jQuery().extend(true, this.getData(), data);

	// encode JSON
	var json = $.toJSON(data);

	this.attr('_rte_data', encodeURIComponent(json));

	// return modified data
	return data;
}

// set type of given placeholder
jQuery.fn.setType = function(type) {
	$(this).attr('class', 'placeholder placeholder-' + type).setData('type', type);
}

// load RTE on DOM ready
jQuery(function() {
	RTE.log('starting...');

	// select initial mode
	var mode = window.RTEInitMode ? window.RTEInitMode : 'wysiwyg';

	RTE.init(mode);
});
