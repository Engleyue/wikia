CKEDITOR.plugins.add('rte-paste',
{
	htmlBeforePaste: '',

	getHtml: function() {
		return RTE.getInstance().document.getBody().getHtml();
	},

	track: function(ev) {
		RTE.track('visualMode', 'paste', ev);
	},

	init: function(editor) {
		var self = this;

		// fire on each mode switch, setup event handler for wysiwyg mode only
		editor.on('dataReady', function(ev) {
			// only care when in wysiwyg mode
			if (editor.mode != 'wysiwyg') {
				return;
			}

			// prevent pasting using Ctrl+V shortcut (BugId:7605)
			editor.document.getBody().on('beforepasteonkey', function(ev) {
				if (editor.config.forcePasteInDialog) {
					RTE.log('"direct" paste prevented');

					// i.e. preventDefault()
					ev.cancel();

					// show paste dialog
					editor.fire('pasteDialog');
				}
			});
		});

		// @see clipboard CK core plugin
		if (!editor.config.forcePasteAsPlainText) {
			editor.on('beforePaste', function(ev) {
				// store HTML before paste
				self.htmlBeforePaste = self.getHtml();

				// handle pasted HTML (mainly for tracking stuff)
				setTimeout(function() {
					self.handlePaste(editor);
				}, 250);
			});
		}

		// track pasting from "Paste as Plain text" dialog
		editor.on('paste', function(ev) {
			if (typeof ev.data.text != 'undefined') {
				self.track('plainText');
			}
		});

		// remove CSS added by WebKit when pasting the content (BugId:9841)
		// @see http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Data_Processor#HTML_Parser_Filters
		if (CKEDITOR.env.webkit) {
			editor.dataProcessor.htmlFilter.addRules({
				elements: {
					// remove meta tag with characters encoding info
					meta: function(element) {
						return false;
					}
				},

				attributes: {
					// remove style attributes added by WebKit browsers
					style: function(value, element) {
						if (value.indexOf('border-top-width: 0px; border-right-width: 0px;') === 0) {
							return false;
						}
					}
				}
			});
		}
	},

	// get pasted HTML
	handlePaste: function(editor) {
		RTE.log('paste detected');

		var afterPasteScheduled = false;

		// get HTML after paste
		var newHTML = this.getHtml();

		// regenerate pasted placeholder / image
		editor.fire('wysiwygModeReady');

		// we have HTML before and after the paste -> generate 'diff'
		var diff = this.diff(this.htmlBeforePaste, newHTML);
		if (typeof diff != 'object' || typeof diff.pasted != 'string') {
			return;
		}

		var pasted = diff.pasted;

		// try to get instance data (city ID and RTE instance ID)
		var matches = pasted.match(/data-rte-instance="([a-z0-9-]+)"/);
		if (matches) {
			var instanceId = matches[1];
			if (instanceId == RTE.instanceId) {
				// pasted content from the same editor instance
				this.track('inside');
			}
			else {
				// check paste "source" city ID
				var cityId = parseInt( instanceId.split('-').shift() );
				if ( cityId != parseInt(wgCityId) ) {
					// pasted from different wiki
					this.track('anotherWiki');
				}
				else {
					// pasted content from different editor instance
					this.track('outside');
				}
			}
		}
		else {
			this.track('plainText');
		}

		// double single line breaks (<br />) - RT #38978
		if (typeof diff.pasted == 'string') {
			if ((/<br>/).test(diff.pasted)) {
				RTE.log('paste: detected line breaks in pasted content');

				// let's replace single linebreaks with HTML <br />
				var html = diff['new'].replace(/([^>])<br>([^<])/g, '$1<br data-rte-washtml="1" />$2');
				editor.setData(html,function(){
					editor.fire('afterPaste');
				});
				afterPasteScheduled = true;
			}
		}

		// regenerate content after paste
		if (!afterPasteScheduled) {
			setTimeout(function() {
				editor.fire('afterPaste');
			}, 250);
		}
	},

	// performs quick diff between two strings (original and one with pasted content)
	// and returns pasted content
	// example: RTE.paste.diff('<br/><b>', '<br/><foo><b>') => <foo>
	// example: RTE.paste.diff('<br/><span><b>', '<br/><foo><b>') => <foo>
	diff: function(o, n) {
		// speed-up
		if (o == n) {
			return false;
		}

		var lenDiff = o.length - n.length;
		var idx = {start: 0, end: n.length - 1};

		// search for prefix and suffix common for old and new string
		while (o.charAt(idx.start) == n.charAt(idx.start)) {
			if (idx.start >= o.length) {
				return false;
			}
			idx.start++;
		}

		while (o.charAt(idx.end+lenDiff) == n.charAt(idx.end)) {
			if (idx.end <= idx.start) {
				return false;
			}
			idx.end--;
		}

		// get unchanged parts at the beginning and at the end of diff
		var prefix = n.substring(0, idx.start+1);
		var suffix = n.substring(idx.end, n.length);

		// fix HTML by finding closing > and opening < in suffix and prefix respectively
		if (/<[^>]*$/.test(prefix)) {
			// go to last < in prefix
			idx.start = prefix.lastIndexOf('<');
		}

		if (/^[^<]*>/.test(suffix)) {
			// go to first > in suffix
			idx.end += suffix.indexOf('>') + 1;
		}

		// get changed fragment
		var pasted = n.substring(idx.start, idx.end + 1);

		// HTML before and after pasted fragment
		prefix = n.substring(0, idx.start);
		suffix = n.substring(idx.end + 1, n.length);

		return {pasted: pasted, prefix: prefix, suffix: suffix, 'new': n, 'old': o, 'start': idx.start, 'end': idx.end};
	}
});

/**
 * Force paste to happen in dialog (force plain text pasting and prevent pasting via Ctrl+V shortcut) - BugId:7605
 * @type Boolean
 * @default false
 * @example
 * config.forcePasteInDialog = false;
 */
CKEDITOR.config.forcePasteInDialog = false;
