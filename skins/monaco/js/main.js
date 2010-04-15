// == meitar @ 2009-12-04 02:57:36 ==
// New global Wikia namespace. Please see
// global Wikia namespace. Please see
//     https://staff.wikia-inc.com/wiki/Coding_conventions#JavaScript
// for details.
// TODO: Is there a better, more fundamental place for this?
var WIKIA = {};

//macbre: moved here from onejstorule.js
var $G = function(id) {
	return document.getElementById(id);
};

// prevent JS errors when AJAX request for magic words is not sent
var magicWords = {};

//Attach DOM-Ready handlers
$(function() {
	$("#ca-watch").click(function(e){showComboAjaxForPlaceHolder(false,"");});
	$("#headerButtonHub").bind("click.headerMenu", openHubMenu);
	$("#headerButtonUser").bind("click.headerMenu", openUserMenu);
	$('.ajaxLogin').click(openLogin);
	$(document).ajaxSend(startAjax).ajaxComplete(stopAjax);
	setupVoting();

	if(!((typeof wgIsLogin == 'undefined') || (wgIsLogin) || (typeof wgComboAjaxLogin == 'undefined') || (!wgComboAjaxLogin) )) {

		$(".wikiaPlaceholder .wikia-button").removeAttr("onclick");
		$(".wikiaPlaceholder .wikia-button").click(function(e){
			if( e.target.nodeName == "SPAN" ){
				showComboAjaxForPlaceHolder($(e.target.parentNode).attr('id'),true);
			}
			else
			{
				showComboAjaxForPlaceHolder($(e.target).attr('id'),true);
			}
			return false;
		});

		var editpromptable = $("#ca-viewsource").add("#te-editanon");

		// add .editsection on wikis with anon editing disabled
		if ( (typeof wgDisableAnonymousEditig !== 'undefined') && (wgDisableAnonymousEditig) ) {
			editpromptable = editpromptable.add(".editsection");
		}

		editpromptable.click(function(e){
			showComboAjaxForPlaceHolder(false, "", function(){
				AjaxLogin.doSuccess = function() {
					var target = $(e.target);
					if( target.is('a') ){
						window.location.href = target.attr('href');
					} else {
						window.location.href = target.parent().attr('href');
					}
				}
			});
			return false;
		});

		 $(".wikiaComboAjaxLogin").click(function(e){
			showComboAjaxForPlaceHolder(false, "", function(){
				AjaxLogin.doSuccess = function() {
					CreatePage.openDialog(e, null);
				}
			});
			return false;
		});
	}
});

// macbre: page loading times (onDOMready / window onLoad)
$(function() {
	if (typeof wgNow != 'undefined') {
		var loadTime = (new Date()).getTime() - wgNow.getTime();
		$().log('DOM ready after ' + loadTime + ' ms', 'Monaco');
	}
});

$(window).bind('load', function() {
	if (typeof wgNow != 'undefined') {
		var loadTime = (new Date()).getTime() - wgNow.getTime();
		$().log('window onload after ' + loadTime + ' ms', 'Monaco');
	}
});

//Ajax Wait Indicator
function startAjax() {
	$("body").addClass("ajax");
}
function stopAjax() {
	$("body").removeClass("ajax");
}

//Search Field
function monacoSearchField(event) {
	if (event.type == 'focus') {
		if ($("#search_field").val() == $("#search_field").attr("title")) {
			$("#search_field").val('').addClass("field_active");
		}
	} else if (event.type == 'blur') {
		if ($("#search_field").val() == '') {
			$("#search_field").val($("#search_field").attr("title")).removeClass("field_active");
		}
	}
}

//Hub Menu
function openHubMenu(event) {
	event.stopPropagation();

	if ( $('#headerMenuHub').exists() ) {
		$("#headerMenuHub").makeHeaderMenu('headerButtonHub', openHubMenu, {attach_to: "#headerButtonHub", attach_at: "bottom"});
	}
	else {
		$.get(wgScript + '?action=ajax&rs=GetHubMenu&cb=' + wgMWrevId + '-' + wgStyleVersion, function(html) {
			$("#positioned_elements").append(html);
			$("#headerMenuHub").makeHeaderMenu('headerButtonHub', openHubMenu, {attach_to: "#headerButtonHub", attach_at: "bottom"});
		});
	}
}

//User Menu
function openUserMenu(event) {
	event.stopPropagation();

	if ( $('#headerMenuUser').exists() ) {
		$("#headerMenuUser").makeHeaderMenu("headerButtonUser", openUserMenu, {attach_to: "#userData", attach_at: "bottom"});
	}
	else {
		$.get(wgScript + '?action=ajax&rs=GetUserMenu&rsargs[]='+ wgUserName +'&uselang='+ wgUserLanguage + '&cb=' + wgMWrevId + '-' + wgStyleVersion,
			function(html) {
				$("#positioned_elements").append(html);
				$("#headerMenuUser").makeHeaderMenu("headerButtonUser", openUserMenu, {attach_to: "#userData", attach_at: "bottom"});
				$('#cockpit1').click(WidgetFramework.show_cockpit);
				$("#headerMenuUser").click(
					function(e){
						if (e.target.nodeName == "A" ){
							$.tracker.byStr('userMenu/' + $(e.target).attr('id'));
						};
					});
			});
	}
}

// AjaxLogin
function openLogin(event) {
	showComboAjaxForPlaceHolder(false, "");
	return false;
}

//Combo login WikiaImagePlaceholder

function showComboAjaxForPlaceHolder(element,isPlaceholder,callback) {
	if ( typeof showComboAjaxForPlaceHolder.statusAjaxLogin == 'undefined' ) { // java script static var
		showComboAjaxForPlaceHolder.statusAjaxLogin = false;
	}

	if ( (typeof wgIsLogin == 'undefined') || (wgIsLogin)
		|| (typeof wgComboAjaxLogin == 'undefined') || (!wgComboAjaxLogin) ) {
		return false;
	}

	if ((typeof  AjaxLogin != 'undefined') && AjaxLogin.showComboFromDOM()) {
		// show ajax login dialog if already in DOM
		WET.byStr('signupActions/signup/open');
		return true;
	}

	if (showComboAjaxForPlaceHolder.statusAjaxLogin){
		return true;
	}

/*   	$("#positioned_elements").append('<div id="loadmask" class="blackout"></div>');
   	$(".blackout:last")
   		.height($(document).height())
		.css({zIndex: 9999}).fadeTo("fast", 0.65); */
	showComboAjaxForPlaceHolder.statusAjaxLogin = true;

	// scroll top
	window.scrollTo(0,0);

	$().getModal(window.wgScript + '?action=ajax&rs=GetComboAjaxLogin&uselang=' + window.wgUserLanguage + '&cb=' + wgMWrevId + '-' + wgStyleVersion,  false, {
			callback: function() {
				$.getScript(window.wgScript + '?action=ajax&rs=getRegisterJS&uselang=' + window.wgUserLanguage + '&cb=' + wgMWrevId + '-' + wgStyleVersion, function() {
						//$("#loadmask").remove();
						if (isPlaceholder) AjaxLogin.setPlaceHolder(element);
						AjaxLogin.init( $('#AjaxLoginLoginForm form') );
						AjaxLogin.show();
						showComboAjaxForPlaceHolder.statusAjaxLogin = false;
						if (typeof callback != 'undefined'){
							callback();
						}
					});
				}
	});
	return true;
}
//Open image place holder if pass in get
$(function(){
	if ((typeof wgComboAjaxLogin != 'undefined') && wgComboAjaxLogin ) {
		if (wgIsLogin) {
			if (window.location.href.indexOf("placeholder=") > 0) {
				element = window.location.href.split("placeholder=")[1].split("&")[0];
				if ($("#"+element).parent().parent().hasClass("wikiaPlaceholder")){
					$("#"+element).trigger("click");
				}
			}
		}
	}
});


//Header Menu
$.fn.extend({
	makeHeaderMenu: function(trigger, headerMenuFunction, options) {
		if (!trigger) {
			//adding error logging here
			$(this).remove();
		} else {
			trigger = $("#"+trigger);
		}

		var menu = $(this);
  		var headerMenuTimer;
		var settings = {
    			delay: 500,
    			edge: 10,
    			attach_to: "#wikia_header",
    			attach_at: "bottom"
    		};
		if (options) {
			$.extend(settings, options);
		}

		//make the trigger unclickable for now - will bound again when menu is closed
		trigger.unbind(".headerMenu");

		//calculate left position
		var center = trigger.offset().left + ( trigger.outerWidth() / 2 );
		var menuWidth = menu.outerWidth();
		var targetLeft = center - ( menuWidth / 2 );
		if (targetLeft < settings.edge) {
			targetLeft = settings.edge;
		}

		//calculate top position
		var targetTop = $(settings.attach_to).offset().top;
		if (settings.attach_at == "bottom") {
			targetTop += $(settings.attach_to).outerHeight();
		}

		//show menu, set mouseenter/mouseleave handlers
		menu.css("left", targetLeft).css("top", targetTop).slideDown("fast").mouseleave(function() {
			headerMenuTimer = setTimeout(function() {
				menu.closeHeaderMenu(trigger, headerMenuFunction);
			}, settings.delay);
		}).mouseenter(function() {
			clearTimeout(headerMenuTimer);
		});

		trigger.mouseleave(function() {
			headerMenuTimer = setTimeout(function() {
				menu.closeHeaderMenu(trigger, headerMenuFunction);
			}, settings.delay);
		}).mouseenter(function() {
			clearTimeout(headerMenuTimer);
		});

		//close menu by clicking anywhere
		$(document).bind("click.headerMenu", function() {
			menu.closeHeaderMenu(trigger, headerMenuFunction);
		});

		menu.click(function(event) {
			event.stopPropagation();
		});
  	},
	closeHeaderMenu: function(trigger, headerMenuFunction) {
		$(document).unbind("click.headerMenu");
		trigger.bind("click.headerMenu", headerMenuFunction);
		$(this).slideUp("fast");
	}
});

//Navigation
var monacoNavigationInitCalled = false;
function menuInit() {
	if (monacoNavigationInitCalled) {
		return;
	}
	monacoNavigationInitCalled = true;

	function monacoNavigationRender(i, item, append) {
		//if appending a new chain of menus to the top-level nav, reset html var
		if (append) {
			html = '';
		}
		//create a sub-menu
		html += '<div class="sub-menu widget" style="display: none;">';
		if (typeof item != 'object') {
			if (typeof magicWords[item] != 'undefined') {
				$.each(magicWords[item], function() {
					classname = '';
					text = this.text;
					if (this.className) {
						classname = ' class="' + this.className + '"';
						if (this.className == 'Monaco-sidebar_more') {
							text = wgMenuMore;
						} else if (this.className == 'Monaco-sidebar_edit') {
							if (typeof wgMenuEdit != 'undefined') {
								text = wgMenuEdit;
							} else {
								return true;
							}
						}
					}
					html += '<div class="menu-item"><a href="' + this.url + '" rel="nofollow"' + classname +'>' + text +'</a></div>';
				});
			}
		} else {
			$.each(item, function(i, item) {
				//does this item have children?
				var children = '';
				if (menuArray[item].children || menuArray[item].magic) {
					children = '<em>&rsaquo;</em>';
				}
				//render div for this item
				html += '<div class="menu-item"><a href="' + menuArray[item].href + '" rel="nofollow">' + menuArray[item].text + children + '</a>';
					if (menuArray[item].children) {
						monacoNavigationRender(menuArray[item], menuArray[item].children);
					} else if (menuArray[item].magic) {
						monacoNavigationRender(menuArray[item], menuArray[item].magic);
					}
				html += '</div>';
			});
		}
		html += '</div>';
		if (append) {
			$("#menu-item_" + i).append(html);
		}
	}
	$.each(menuArray.mainMenu, function (i, item) {
		monacoNavigationRender(i, item, true);
	});
	$(".sub-menu").each(function() {
		//no more per menu 'edit' links, rt#22739

		//no border on last item in sub-menus
		$(this).children("div:last").css("border", 0);
	});

	// just 1 per menu 'edit' at top level, rt#22739
	if (typeof wgMenuEdit != 'undefined') {
		href = ($("#navigation").hasClass("userMenu")) ? '?title=User:' + wgUserName + '/Monaco-sidebar&action=edit' : '?title=MediaWiki:Monaco-sidebar&action=edit';
		$("#navigation").append('<div id="navigation-edit" class="menu-item"><a href="' + wgScript + href + '" class="Monaco-sidebar_edit">' + wgMenuEdit + '</a></div>');
	}
	monacoNavigationHoverActions();
}

var menutimer;
function monacoNavigationHoverActions() {
	$("#navigation .menu-item").hover(function() {
		$(this).addClass("navigation-hover").children(".sub-menu").show();
		$(this).siblings().removeClass("navigation-hover").find(".sub-menu").hide().end().find(".menu-item").removeClass("navigation-hover");
	});
	$("#navigation").mouseleave(function() {
		$(this).find(".menu-item").removeClass("navigation-hover");
		menutimer = setTimeout(function() {
			$("#navigation").find(".sub-menu").hide();
		}, 500);
	}).mouseover(function() {
		clearTimeout(menutimer);
	});

	// RT #19924
	$('#wpTextbox1').mouseover(function() {
		$('#navigation').find(".menu-item").removeClass("navigation-hover");
		menutimer = setTimeout(function() {
			$("#navigation").find(".sub-menu").hide();
		}, 500);
	});
}

// footer star voting
function setupVoting() {

	// callback for vote and unrate
	var callback = function(data) {
		$('#star-rating').removeClass('star-rating-progress');

		// show current rating
		$('#current-rating').css('width', Math.round(data.item.wkvoteart[0].avgvote * 20) + 'px');
		$('#star-rating a').css('display', data.item.wkvoteart[0].remove ? '' : 'none');
		$('#unrateLink').css('display', data.item.wkvoteart[0].remove ? 'none' : '');

		// purge current page
		$.post(window.location.href, {action: 'purge'});
	};

	$('#star-rating').find('a').click(function(ev) {
		ev.preventDefault();

		var rating = this.id.substr(4,1);
		$('#star-rating').addClass('star-rating-progress');

		$.getJSON(wgScriptPath+'/api.php?action=insert&list=wkvoteart&format=json&wkvote='+rating+'&wkpage='+wgArticleId, callback);

		WET.byStr('ArticleFooter/vote/' + rating);
	});

	$('#unrateLink').click(function(ev) {
		ev.preventDefault();

		$('#star-rating').addClass('star-rating-progress');
		$('#unrateLink').css('display', 'none');

		$.getJSON(wgScriptPath+'/api.php?action=wdelete&list=wkvoteart&format=json&wkpage='+wgArticleId, callback);

		WET.byStr('ArticleFooter/vote/unrate');
	});
}
