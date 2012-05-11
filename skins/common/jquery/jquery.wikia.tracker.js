/*global WikiaTracker:true*/

/**
 * @brief Internal Wikia tracking set up by Garth Webb
 *
 * @param string event Name of event
 * @param object data Extra parameters to track
 * @param object callbackSuccess callback function on success (optional)
 * @param object callbackError callback function on failure (optional)
 *
 * @author Christian
 */
jQuery.internalTrack = function(event, data, callbackSuccess, callbackError) {
	// Require an event argument
	if (!event) {
		return;
	}

	$().log(event, 'InternalTrack');
	if(data) {
		$().log(data);
	}

	// Set up params object - this should stay in sync with /extensions/wikia/Track/Track.php
	var params = {
		'c': wgCityId,
		'x': wgDBname,
		'a': wgArticleId,
		'lc': wgContentLanguage,
		'n': wgNamespaceNumber,
		'u': window.trackID || window.wgTrackID || 0,
		's': skin,
		'beacon': window.beacon_id || ''
	};

	// Add data object to params object
	$.extend(params, data);

	// Make request
	//$.get('http://a.wikia-beacon.com/__track/special/' + event, params, callback);
	$.ajax({
		cache: false,
		timeout: 3000,
		dataType: "script",
		url: 'http://a.wikia-beacon.com/__track/special/' + event,
		data: params,
		error: callbackError,
		success: callbackSuccess
	});
};

// Now that the code is loaded, if there were any tracking events in the spool from before this file loaded, replay them.
/*if (typeof wikiaTrackingSpool !== 'undefined') {
	wikiaTrackingSpool.forEach(function( eventNameAndData ){
		$().log('Sending previously-spooled tracking event', eventNameAndData);
		$.internalTrack( eventNameAndData[0], eventNameAndData[1] );
	});
}*/

// Port of getTarget and resolveTextNode function (altogether) from YUI Event lib
// @author: Inez
// TODO: Move it to some more general place because it is not realted only to tracking
var getTarget = function (ev) {
    var t = ev.target || ev.srcElement;
    if(t && 3 == t.nodeType) {
        t = t.parentNode;
    }
    return t;
};

/*
@#@
*/

jQuery.tracker = function() {
	var isOasis = (window.skin == 'oasis');
	var content = isOasis ? $('#WikiaArticle') : $('#bodyContent');

    // Page view
    if(wgIsArticle) {
        $.tracker.byStr('view');
    }

    // Edit page
    if(wgArticleId != 0 && wgAction == 'edit') {
        $.tracker.byStr('editpage/view');
    }

    // Recent changes tracking
    if(wgCanonicalSpecialPageName == 'Recentchanges') {
        $.tracker.byStr('RecentChanges/view');
        content.click(function (e) {
            var target = getTarget(e);
            if($.nodeName(target, 'a')) {
                if($.nodeName(target.parentNode, 'fieldset')) {
                    switch(target.innerHTML) {
                        case "50":
                        case "100":
                        case "250":
                        case "500":
                            $.tracker.byStr('RecentChanges/show/'+target.innerHTML+'changes');
                            break;
                        case "1":
                        case "3":
                        case "7":
                        case "14":
                        case "30":
                            $.tracker.byStr('RecentChanges/show/'+target.innerHTML+'days');
                            break;
                        default:
                            var option = target.href.substr(target.href.indexOf(wgPageName)+wgPageName.length+1);
                            option = option.substr(0, option.indexOf('=') + 2);
                            option = option.split('=');
                            if(option.length == 2) {
                                $.tracker.byStr('RecentChanges/show/'+(option[1] == 1 ? 'hide' : 'show')+option[0].substr(4));
                            }
                            break;
                    }
                } else {
                    if($(target).hasClass('mw-userlink')) {
                        $.tracker.byStr('RecentChanges/click/username');
                    } else if($.nodeName(target.parentNode, 'span')) {
                        if($(target.parentNode).hasClass('mw-usertoollinks')) {
                            var As = $(target.parentNode).find('a');
                            if(As.length == 3) {
                                if(As[0] == target) {
                                    $.tracker.byStr('RecentChanges/click/usertalk');
                                } else if(As[1] == target) {
                                    $.tracker.byStr('RecentChanges/click/usercontribs');
                                } else if(As[2] == target) {
                                    $.tracker.byStr('RecentChanges/click/userblock');
                                }
                            } else if(As.length == 2) {
                                if(As[0] == target) {
                                    $.tracker.byStr('RecentChanges/click/usertalk');
                                } else if(As[1] == target) {
                                    $.tracker.byStr('RecentChanges/click/userblock');
                                }
                            }
                        } else if($(target.parentNode).hasClass('mw-rollback-link')) {
                            $.tracker.byStr('RecentChanges/click/rollback');
                        }
                    } else if(target.href.indexOf('action=history') > 0) {
                            $.tracker.byStr('RecentChanges/click/history');
                    } else if(target.href.indexOf('diff=') > 0) {
                            $.tracker.byStr('RecentChanges/click/diff');
                    } else if(target.href.indexOf('/delete') > 0) {
                            $.tracker.byStr('RecentChanges/click/deletionlog');
                    } else {
                            $.tracker.byStr('RecentChanges/click/item');
                    }
                }
            } else if($.nodeName(target, 'input')) {
                $.tracker.byStr('RecentChanges/show/namespacego');
            }
        });
    }

    // Links on edit page
    $('#wpMinoredit, #wpWatchthis, #wpSave, #wpPreview, #wpDiff, #wpCancel, #wpEdithelp').click(function (e) {
        $.tracker.byStr('editpage/' + $(this).attr('id').substring(2).toLowerCase());
    });

    // TODO: Verify if it works
    // EditSimilar extension - result & preferences links - Bartek, Inez
    $('#editsimilar_links').click(function(e) {
        if(e.target.nodeName == 'A' && e.target.id != 'editsimilar_preferences') {
            $.tracker.byStr('userengagement/editSimilar_click');
        } else if(e.target.id == 'editsimilar_preferences') {
            $.tracker.byStr('userengagement/editSimilar/editSimilarPrefs');
        }
    });


    // TODO: Verify if it works
    // Special:Userlogin (Macbre)
    if(wgCanonicalSpecialPageName && wgCanonicalSpecialPageName == 'Userlogin') {
        $('#userloginlink').children('a:first').click(function(e) { $.tracker.byStr('loginActions/goToSignup'); });
    }

    // Special:Search - Macbre, Inez
    if(wgCanonicalSpecialPageName && wgCanonicalSpecialPageName == 'Search') {
        var listNames = ['title', 'text'];
        // parse URL to get offset value
        var re = (/\&offset\=(\d+)/).exec(document.location);
        var offset = re ? (parseInt(re[1], 10) + 1) : 1;

        $('#bodyContent').children('.mw-search-results').each(function(i) {
            $(this).find('a').each(function(j) {
                $(this).click(function() {
                    $.tracker.byStr('search/searchResults/' + listNames[i] + 'Match/' + (offset + j));
                });
            });
            if(i == 0) {
                $.tracker.byStr('search/searchResults/view');
            }
        });
    }

    // Create Page
    if($('#createpageform').length) {
        $('#wpSave').click(function(e) { $.tracker.byStr('createPage/save'); });
        $('#wpPreview').click(function(e) { $.tracker.byStr('createPage/preview'); });
        $('#wpAdvancedEdit').click(function(e) { $.tracker.byStr('createPage/advancedEdit'); });
    }
    if(wgCanonicalSpecialPageName && wgCanonicalSpecialPageName == 'CreatePage') {
        $.tracker.byStr('createPage');
    }

	// MW toolbar tracking (RT #47458)
	$('#toolbar').click(function(ev) {
		var button = $(ev.target);

		if (!button.is('img')) {
			return;
		}

		var id = button.attr('id').split('-').pop();

		$.tracker.byStr('editpage/toolbar/' + id);
	});

	// tracking for article content links (done by Nef / updated by Macbre)
	if (wgIsArticle && wgArticleId > 0) {
		// catch all clicks inside article content, but track clicks on links only
		content.click(function(e) {

			var track = function(fakeUrl) {
				var root = isOasis ? 'contentpage/contentlink/' : 'articleActions/contentLink/';
				$.tracker.byStr(root + fakeUrl);
			};

			var link = $(e.target);

			if (link.is('img')) {
				link = link.parent();
			}

			// not a link, leave here
			if (!link.is('a')) {
				return;
			}

			// RT #68550
			if (!isOasis) {
				$.tracker.byStr("articleAction/contentLink-all");
			}

			// Do not track category galleries. FB:955
			if ( link.closest('.category-gallery').length > 0 ){
				return;
			}

			var _href = link.attr("href") || "";

			/* regular wiki link */
			/* DON'T PUT IT AT THE END AND MAKE CATCH-ALL, BE BRAVE (-; */
			if (link.attr("class") == "" && link.attr("title") != "" && !_href.match(/\/index\.php\?title=.*\&action=edit/)) {

				/* catlinks */
				/* nonexistent (red) categories will be traced below as regular red links */
				if (link.parents("div").is("div#catlinks")) {
					track("ignore/categories");
					return;
				}

				/* smw factbox */
				if (link.parents("div").is("div.smwfact")) {
					track("ignore/smwfactbox");
					return;
				}

				track("blueInternal");
				return;
			}

			/* href="#" or href="javascript:..." */
			if (_href == "#" || _href.match(/^javascript:/)) {
				track("ignore/javascript");
				return;
			}
			/* href="#anchor" */
			if (_href.match(/^#/)) {
				track("ignore/anchor");
				return;
			}

			/* section edit link (already tracked as editSection) */
			if (_href.match(/\/index\.php\?title=.*\&action=edit\&section=/)) {
				track("ignore/editSection");
				return;
			}
			/* regular red link */
			/* including categories */
			if (_href.match(/\/index\.php\?title=.*&action=edit&redlink=/) /* && link.hasClass("new") */ ) {
				track("red");
				return;
			}
			/* other edit link (eg. template "e" shortcut) */
			if (_href.match(/\/index\.php\?title=.*\&action=edit/) /* && link.hasClass("new") */ ) {
				track("ignore/edit");
				return;
			}

			/* image */
			if (link.hasClass("image")) {
				track("image");
				return;
			}
			/* bottom right of thumbnails... is this reliable? */
			if (link.hasClass("internal")) {
				track("imageIcon");
				return;
			}

			/* external */
			if (link.hasClass("external") || link.hasClass("extiw") /* && _href.match(/^https?:\/\//) */ ) {
				track("blueExternal");
				return;
			}

			if (!isOasis) {
				track("unknown/" + wgCityId + "-" + wgArticleId + "/" + encodeURIComponent(_href));
			}
		});
	}


	if (typeof window.initTracker == 'function') {
		window.initTracker();
	}
};

jQuery.tracker.byStr = function(message, unsampled) {
	$.tracker.track(message, unsampled);
};

jQuery.tracker.byId = function(e) {
	$.tracker.track(this.id);
};

jQuery.tracker.trackStr = function(str, account) {
	WikiaTracker.track(str, 'main.test');

	if(typeof account != 'undefined') {
		//_gaq.push(['_setAccount', account]);
	}
	//_gaq.push(['_trackPageview', str]);
	$().log('tracker: ' + str);
};

jQuery.tracker.track = function(fakeurl, unsampled) {
	var fakeurlArray = fakeurl.split('/'),
		username = wgUserName == null ? 'anon' : 'user',
		skinname;

	unsampled = unsampled || false;

	switch(skin) {
		case 'answers':
		case 'SkinAnswers':
			skinname = 'ansmco';
			break;

		case 'oasis':
			skinname = 'wikia';
			break;

		default:
			skinname = 'monaco';
			break;
	}

	// override bad skin recognition (RT#47483)
	if( window.wgOldAnswerSkin && ( 'view' == fakeurl ) ) {
		return;
	}

	var str = ['1_' + skinname, username, fakeurl].join('/');

	WikiaTracker.track(str, 'main.sampled');
	/*if (unsampled) {
		WikiaTracker.track(str, 'main.unsampled');
	}*/
	/*if(window.wgPrivateTracker) {
		WikiaTracker.track(wgDBname + '/' + str, 'main.private');
		if (unsampled) {
			WikiaTracker.track(wgDBname + '/' + str, 'main.unsampled');
		}
	}*/
	//WikiaTracker.AB(str);
};


/**
 * DEPRACATED, use WikiaTracker.trackEvent instead
 */
jQuery.tracker.trackEvent = function(category, action, opt_label, opt_value) {
	var gaqArgs = [], logStr = Array.prototype.join.call(arguments, '/');

	for (var i=0; i < arguments.length; i++) {
		gaqArgs.push(arguments[i]);
	}

	WikiaTracker.track(null, 'main.sampled', gaqArgs);
	$().log(logStr, 'tracker [event]');
};

// macbre: temporary fix
var WET = {
	byStr: function(str) {
	       $.tracker.byStr(str)
	},
	byId: $.tracker.byId
};

// macbre: simple click tracking
// usage: $('#foo').trackClick('feature/foo');
jQuery.fn.trackClick = function(fakeUrl) {
	this.click(function(ev) {
		jQuery.tracker.byStr(fakeUrl);
	});
};

$(document).ready($.tracker);
