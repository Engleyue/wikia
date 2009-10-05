// Port of getTarget and resolveTextNode function (altogether) from YUI Event lib
// @author: Inez
// TODO: Move it to some more general place because it is not realted only to tracking
function getTarget(ev) {
	var t = ev.target || ev.srcElement;
	if(t && 3 == t.nodeType) {
		t = t.parentNode;
	}
	return t;
}

/*
Copyright (c) 2009, Wikia Inc.
Author: Inez Korczynski (inez (at) wikia.com)
*/

var initTracker = function() {

	// - Inez
	if(wgID == 2428) {
		$('#realAd0, #realAd1').click(function(e) {
			if(e.target.innerHTML == 'Close ad') {
				if(wgIsMainpage) {
					$.tracker.byStr('CloseAd/MainPage');
				} else {
					$.tracker.byStr('CloseAd/ArticlePage');
				}
			}
		});
	}

	// Request Wiki
	$('#request_wiki').click(function() {
		$.tracker.byStr('RequestWiki/initiate_click');
	});

	// Edit links for sections
	// TODO: WTF is this variable?
	var WysyWigDone = false;
	$('#bodyContent').find('span.editsection').click(function(e) {
		if(e.target.nodeName == 'A') { $.tracker.byStr('articleAction/editSection'); }
	});

	if(wgUserName == null) {
		$('#login, #register, #community_login, #community_register').click($.tracker.byId);

		$('#wpLoginattempt, #wpMailmypassword, #wpCreateaccount, #wpAjaxRegister').click(function(e) {
			$.tracker.byStr('loginActions/' + this.id.substring(2).toLowerCase());
		});
	} else {
		$('#userData, #headerMenuUser').click(function(e) {
			if(e.target.nodeName == 'A') {
				var parentId = e.target.parentNode.id;
				$.tracker.byStr('userMenu/' + (parentId == 'header_username' ? 'userPage/' : '') + e.target.innerHTML);
			}
		});
		$('#headerButtonUser').click(function() { $.tracker.byStr('userMenu/more'); });
	}

	// CategoryList
	$('#headerMenuHub').click(function() { $.tracker.byStr('categoryList/more'); });
	$('#headerMenuHub').click(function(e) {
		if(e.target.nodeName == 'A') {
			if(e.target.id == 'goToHub')  {
				$.tracker.byStr('categoryList/moredotdotdot');
			} else if(e.target.id != '') {
				$.tracker.byStr('categoryList/'+ e.target.id.split('-')[1]  + '/' + e.target.innerHTML);
			}
		}
	});

	// Wikia & Wiki logo
	$('#wikia_logo, #wiki_logo').click($.tracker.byId);

	// User Engagement
	$('#ue_msg').click(function(e) {
		if(e.target.nodeName == 'A') {
			$.tracker.byStr('userengagement/msg_click_' + e.target.id);
		}
	});

	// Article content actions
	$('#page_controls, #page_tabs').click(function(e) {
		if(e.target.nodeName == 'A') {
			$.tracker.byStr('articleAction/' + e.target.id.substring(3).replace(/nstab-/, 'view/'));
		}
	});

	// Article footer
	$('#articleFooterActions, #articleFooterActions2, #articleFooterActions3, #articleFooterActions4').click(function(e) {
		var el = e.target;
		if(el.nodeName == 'IMG') { el = el.parentNode; }
		if(el.nodeName == 'A') { $.tracker.byStr('ArticleFooter/' + el.id.split('_')[1]); }
	});

	// Share it icons
	$('#share').click(function(e) {
		if(e.target.nodeName == 'A') {
			$.tracker.byStr('ArticleFooter/share/' + e.target.id.substring(5,e.target.id.length-2));
		}
	});


	// Footer links
	$('#wikia_footer').click(function(e) {
		if(e.target.nodeName == 'A') {
			$.tracker.byStr(((e.target.parentNode.id == 'wikia_corporate_footer') ? 'wikiaFooter/' : 'footer/') + e.target.innerHTML);
		}
	});

	// Widgets
	$('dl.widget').click(function(e) {
		if(e.target.nodeName == 'A') {
			$.tracker.byStr('widget/' + this.className.split(' ')[1] + '/' + e.target.innerHTML);
		}
	});

	// Search
	$('#searchform').submit(function() {
		$.tracker.byStr('search/submit/enter/' +  escape($('#search_field').val().replace(/ /g, '_')));
	});

	// Search button
	$('#search_button').click(function(e) {
		$.tracker.byStr('search/submit/click/' +  escape($('#search_field').val().replace(/ /g, '_')));
	});

	// Spotlights
	$('#spotlight_footer').find('div').each(function(i) {
		var id = parseInt(this.id.substr(this.id.length-1), 10);
		$('#realAd'+id).click(function() {
			$.tracker.byStr('spotlights/footer' + (i+1));
		});
	});

	// Advertiser Widget
	$('#102_content').find('div').each(function(i) {
		var id = this.id.substr(this.id.length-1);
		$('#realAd'+id).click(function() {
			$.tracker.byStr('spotlights/sidebar1');
		});
	});

	// Navigation - sidebar
	$("#navigation a").live("click", function() {
		var tree = [];
		self = $(this);
		while (self.attr("id") != 'navigation') {
			if (self.hasClass('menu-item')) {
				tree.push($.trim(self.children("a").contents()[0].nodeValue));
			}
			self = self.parent();
		}
		tree.reverse();
		str = 'sidebar/' + tree.join("/");
		$.tracker.byStr(str);
	});

};

/*
@#@
*/

jQuery.tracker = function() {

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

		$('#bodyContent').click(function (e) {
//e.preventDefault();
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

	// CreateAPage extension - Bartek, Inez
	if($('#createpageform').length) {
		$('#wpSave').click(function(e) { $.tracker.byStr('createPage/save'); });
		$('#wpPreview').click(function(e) { $.tracker.byStr('createPage/preview'); });
		$('#wpAdvancedEdit').click(function(e) { $.tracker.byStr('createPage/advancedEdit'); });
	}

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

	initTracker();
};

jQuery.tracker.byStr = function(message) {
	$.tracker.track(message);
};

jQuery.tracker.byId = function(e) {
	$.tracker.track(this.id);
};

jQuery.tracker.track = function(fakeurl) {
	fakeurlArray = fakeurl.split('/');
	if(typeof urchinTracker != 'undefined') {
		_uacct = "UA-2871474-1";
		var username = wgUserName == null ? 'anon' : 'user';
		var fake = '/1_monaco/' + username + '/' + fakeurl;
		$().log('tracker: ' + fake);
		urchinTracker(fake);
		if(wgPrivateTracker) {
			fake = '/1_monaco/' + wgDB + '/' + username + '/' + fakeurl;
			$().log('tracker: ' + fake);
			urchinTracker(fake);
		}
	}
};


// macbre: temporary fix
var WET = {
	byStr: function(str) {
		$.tracker.byStr(str)
	},
	byId: $.tracker.byId
};

$(document).ready($.tracker);
