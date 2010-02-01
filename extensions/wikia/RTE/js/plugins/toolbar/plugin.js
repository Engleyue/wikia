CKEDITOR.plugins.add('rte-toolbar',
{
	editorContainer: false,
	updateToolbarLock: false,

	init: function(editor) {
		var self = this;

		editor.on( 'themeSpace', function( event ) {
			if ( event.data.space == 'top')
			{
				var config = editor.config['toolbar_' + editor.config.toolbar];
				var messages = RTE.instance.lang.bucket;

				var output = [ '<table id="cke_toolbar"><tr>' ];

				for(var b = 0; b < config.length; b++)
				{
					var bucket = config[b];
					output.push( '<td><span class="headline color1">' + messages[bucket.msg] + '</span>' );
					output.push( '<div class="bucket_buttons color1"><div class="color1">' );
					for(var g = 0; g < bucket.groups.length; g++)
					{
						output.push( '<span class="cke_buttons_group">' );

						var items = bucket.groups[g];
						var itemsCount = items.length;

						for(var i = 0; i < itemsCount; i++) {
							var itemName = items[i];
							var item = editor.ui.create(itemName);
							if(item)
							{
								// add extra CSS classes for button wrapping <span>
								item.wrapperClassName = '';

								// first / last item
								if (i == 0) {
									item.wrapperClassName += 'cke_button_first ';
								}

								if (i == itemsCount - 1) {
									item.wrapperClassName += 'cke_button_last ';
								}

								// state classes: cke_on/cke_off/cke_disabled
								var itemObj = item.render(editor, output);
							}
						}

						output.push( '</span>' );
					}
					output.push( '</div></div><span class="tagline color1"></span></td>' );
				}

				output.push( '</tr></table>' );

				// add placeholder for source mode toolbar
				output.push('<div id="mw-toolbar"></div>');

				// add toolbar covering div - will be used during loading state
				output.push('<div id="cke_toolbar_cover" class="color1" />');

				event.data.html += output.join( '' );
			}
		});

		editor.on('instanceReady', function() {
			var toolbar = $('#cke_toolbar');

			// setup toolbar
			RTE.tools.getThemeColors();

			toolbar.find('.color1').css({
				backgroundColor: RTE.config.baseBackgroundColor,
				color: RTE.config.baseColor
			});

			// mark row wrapping toolbar
			toolbar.parent().parent().attr('id', 'cke_toolbar_row');

			// do calculation on each window resize / widescreen mode toggle
			$(window).resize(function() {
				self.updateToolbar.apply(self);
			});
			editor.on('widescreen', function () {
				self.updateToolbar.apply(self);
			});

			// and on init
			self.updateToolbar();

			// setup bucket show/hide animation
			toolbar.find('td').hover(function() {
				self.showBucket.apply(this);
			}, function() {
				self.hideBucket.apply(this);

				setTimeout(function() {
					self.updateToolbar.apply(self);
				}, 10);
			});
		});

		editor.on('instanceReady', function() {
			// try to set toolbar colors (fix for preview in Chrome)
			var toolbarWrapper = $('#cke_top_wpTextbox1');

			RTE.tools.getThemeColors();

			toolbarWrapper.css({
				backgroundColor: RTE.config.baseBackgroundColor,
				color: RTE.config.baseColor
			});


			var toolbar = $('#cke_toolbar');

			// render new MW toolbar inside CK
			var MWtoolbar = $('#mw-toolbar');

			// add buttons
			for (var i = 0; i < mwEditButtons.length; i++) {
				mwInsertEditButton(MWtoolbar[0], mwEditButtons[i]);
			}

			for (var i = 0; i < mwCustomEditButtons.length; i++) {
				mwInsertEditButton(MWtoolbar[0], mwCustomEditButtons[i]);
			}

			// toolbar is ready!
			editor.fire('toolbarReady', toolbar);

			// remove MW toolbar (rendered by MW core)
			$('#toolbar').remove();

			// reference to editor container (wrapping element for iframe / textarea)
			self.editorContainer = $(RTE.instance.container.$).find('.cke_contents');
		});

		// override insertTags function, so it works in CK source mode

		// apply tagOpen/tagClose to selection in textarea,
		// use sampleText instead of selection if there is none
		window.insertTags = function(tagOpen, tagClose, sampleText) {
			var txtarea = self.editorContainer.children('textarea')[0];

			var selText, isSample = false;

			if (document.selection  && document.selection.createRange) { // IE/Opera

				//save window scroll position
				if (document.documentElement && document.documentElement.scrollTop)
					var winScroll = document.documentElement.scrollTop
				else if (document.body)
					var winScroll = document.body.scrollTop;
				//get current selection
				txtarea.focus();
				var range = document.selection.createRange();
				selText = range.text;
				//insert tags
				checkSelectedText();
				range.text = tagOpen + selText + tagClose;
				//mark sample text as selected
				if (isSample && range.moveStart) {
					if (window.opera)
						tagClose = tagClose.replace(/\n/g,'');
					range.moveStart('character', - tagClose.length - selText.length);
					range.moveEnd('character', - tagClose.length);
				}
				range.select();
				//restore window scroll position
				if (document.documentElement && document.documentElement.scrollTop)
					document.documentElement.scrollTop = winScroll
				else if (document.body)
					document.body.scrollTop = winScroll;

			} else if (txtarea.selectionStart || txtarea.selectionStart == '0') { // Mozilla

				//save textarea scroll position
				var textScroll = txtarea.scrollTop;
				//get current selection
				txtarea.focus();
				var startPos = txtarea.selectionStart;
				var endPos = txtarea.selectionEnd;
				selText = txtarea.value.substring(startPos, endPos);
				//insert tags
				checkSelectedText();
				txtarea.value = txtarea.value.substring(0, startPos)
					+ tagOpen + selText + tagClose
					+ txtarea.value.substring(endPos, txtarea.value.length);
				//set new selection
				if (isSample) {
					txtarea.selectionStart = startPos + tagOpen.length;
					txtarea.selectionEnd = startPos + tagOpen.length + selText.length;
				} else {
					txtarea.selectionStart = startPos + tagOpen.length + selText.length + tagClose.length;
					txtarea.selectionEnd = txtarea.selectionStart;
				}
				//restore textarea scroll position
				txtarea.scrollTop = textScroll;
			}

			function checkSelectedText(){
				if (!selText) {
					selText = sampleText;
					isSample = true;
				} else if (selText.charAt(selText.length - 1) == ' ') { //exclude ending space char
					selText = selText.substring(0, selText.length - 1);
					tagClose += ' '
				}
			}
		};
	},

	updateToolbar: function() {
		var self = this;
		var lockTimeout = 250;

		// check lock
		if (this.updateToolbarLock) {
			// try to run it later
			setTimeout(function() {
				self.updateToolbar.apply(self);
			}, lockTimeout + 50);
			return;
		}

		this.updateToolbarLock = true;

		//check each bucket for hidden buttons
		var anyhidden = false;
		$('#cke_toolbar').find('td').each(function() {
			var hidden = 0;
			var cell = $(this);

			 // check if we need to run this check at all
			var bucket = cell.children('.bucket_buttons').children('div');

			if (parseInt(bucket.height()) > 60) {
				var groups = bucket.children('.cke_buttons_group');

				//where is the first row of buttons?
				var baseline = Math.floor(bucket.offset().top);

				// starting from the last buttons group
				// loop until first shown group is found
				groups.reverse().each(function() {
					// this group is visible - break the loop
					var nodeTop = Math.floor($(this).offset().top);
					if(nodeTop < baseline + 30) {
						return false;
					}

					hidden += $(this).children().length;
					anyhidden = true;
				});
			}

			//are there any hidden buttons?
			if (hidden > 0) {
				cell.find(".tagline").html(hidden + ' ' + RTE.messages.more);
				cell.addClass('more');
			} else {
				cell.find(".tagline").html("");
				cell.removeClass('more');
			}
		});
		if (anyhidden) {
			$('#cke_toolbar').addClass('more');
		} else {
			$('#cke_toolbar').removeClass('more');
		}

		// resize #toolbarWrapper and store height of each bucket (when expanded)
		var wrapperHeight = 200;

		$('#cke_toolbar').find('td').each(function() {
			var cell = $(this);

			// calculate height of each bucket (when collapsed)
			var bucketHeight = 0;
			cell.children().each(function() {
				bucketHeight += $(this).height();
			});

			// choose the smallest one
			wrapperHeight = Math.min(wrapperHeight, bucketHeight);

			// store height of expanded bucket
			var bucket = cell.children('.bucket_buttons');
			bucket.attr('_height_expanded', bucket.children('div').height());
		});

		// set height of table row with toolbar
		if (CKEDITOR.env.ie) {
			// set height of <tr>
			var toolbarWrapper = $('#cke_top_wpTextbox1').parent();
		}
		else {
			// set height of <td>
			var toolbarWrapper = $('#cke_top_wpTextbox1');
		}

		toolbarWrapper.height(wrapperHeight);

		// toolbar height might have changed - reposition #RTEStuff
		RTE.repositionRTEStuff();

		// resize toolbar cover
		$('#cke_toolbar_cover').height(wrapperHeight);

		// remove lock after 500 ms, so next updateToolbar() can be executed
		setTimeout(function() {
			self.updateToolbarLock = false;
		}, lockTimeout);
	},

	showBucket: function() {
		var wrapper = $(this).find('.bucket_buttons');
		var buttons = wrapper.children('div');

		buttons.css('height', 'auto');

		// mark expanded bucket (<td>)
		wrapper.parent().addClass('bucket_expanded');
	},

	hideBucket: function() {
		var wrapper = $(this).find('.bucket_buttons');
		var buttons = wrapper.children('div');

		// unmark expanded bucket (<td>)
		wrapper.parent().removeClass('bucket_expanded');

		// cleanup
		buttons.css('height', 'auto');
	}
});
