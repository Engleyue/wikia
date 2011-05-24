ArticleAjaxLoading = {
		
	counter: 0,

	cache: {},
	
	track: function(data) {
		_gaq.push(['_setAccount', 'UA-2871474-1']);
		_gaq.push(data);
	},

	init: function() {
		if(window.aal) { // for users who have not changed preferences for article rendering (like thumbnail size for example)
			if(window.wgIsLogin) { // for logged in users
				if(window.wgNamespaceNumber === 0) { // for main namespace
					if(window.wgAction == 'view') { // for article view 
						if(window.wgArticleId != 0) { // for existing article
							if(window.wgIsMainpage == false) { // not for main page
								var ua = $.browser;
								if((ua.mozilla == true && ua.version.slice(0,3) == '2.0') || (ua.safari == true && ua.webkit == true)) { // for firefox 4 or chrome
									
									if(window.aal == 'G3') {
										
										ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'PageView', 'G3']);
										
									} else if(window.aal == 'G2') {
										
										ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'PageView', 'G2']);
										
									} else if(window.aal == 'G1') {
										
										ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'PageView', 'G1']);

										// backup variables that we have to recover after every ajax request 
										ArticleAjaxLoading.cache.articleComments = $('#article-comments').find('.session').html();
										ArticleAjaxLoading.cache.wgUserName = wgUserName;
										ArticleAjaxLoading.cache.wgOneDotURL = wgOneDotURL;
										ArticleAjaxLoading.cache.wgIsLogin = wgIsLogin;
										ArticleAjaxLoading.cache.wgUserGroups = wgUserGroups;
	
										// handle clicks on links in article content
										$('#WikiaArticle a').live('click', ArticleAjaxLoading.linkClickHandler);										
									}

								}
							}
						}
					}
				}
			}
		}
	},
	
	linkClickHandler: function(e) {
		// Do not change behaviour for middle click, cmd click and ctrl click 
		if(e.which == 2 || e.metaKey) {
			return true
		}
		
		if(ArticleAjaxLoading.counter > 100) {
			ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'CounterExceeded']);
			return true;
		}
		
		if(window.wgNamespaceNumber === 0 && window.wgAction == 'view' && window.wgArticleId != 0) {
			var href = $(this).attr('href');
			
			if(href.indexOf(window.wgArticlePath.replace('$1', '')) === 0  && !/[?&#:]/.test(href) && !/(.htm|.php)/.test(href)) {
				e.stopImmediatePropagation();
				e.preventDefault();
				
				$('body').css('cursor', 'wait');
				
				$.pjax({
					url: href,
					container: '#WikiaMainContent',
				});
			}
		}
	}
};

/* PJAX */
(function($){
	$.fn.pjax = function( container, options ) {
		if ( options )
			options.container = container
		else
			options = $.isPlainObject(container) ? container : {container:container}

	return this.live('click', function(event){
		if ( event.which > 1 || event.metaKey )
			return true

		var defaults = {
				url: this.href,
				container: $(this).attr('data-pjax')
		}
		
		$.pjax($.extend({}, defaults, options))
		
		event.preventDefault()
	})
}

$.pjax = function( options ) {
	var $container = $(options.container),
		success = options.success || $.noop
	
	delete options.success
	
	var defaults = {
		timeout: 15000,
		push: true,
		replace: false,
		data: { mode: 'AAL' },
		type: 'GET',
		dataType: 'json',
		beforeSend: function(xhr){
			$container.trigger('start.pjax')
			xhr.setRequestHeader('X-PJAX', 'true')
		},
		
		error: function(){
			window.location = options.url
		},
		
		complete: function(){
			$container.trigger('end.pjax')
		},
		
		success: function(data){
			var oldTitle = document.title;
			
			try {
				$('script').eq(0).replaceWith(data.globalVariablesScript);
				
				if(wgIsMainpage == true || window.wgNamespaceNumber !== 0) {
					ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'NavigatedToMainPage']);
					return window.location = options.url;
				}
				
				wgUserName = ArticleAjaxLoading.cache.wgUserName;
				wgOneDotURL = ArticleAjaxLoading.cache.wgOneDotURL;
				wgIsLogin = ArticleAjaxLoading.cache.wgIsLogin;
				wgUserGroups = ArticleAjaxLoading.cache.wgUserGroups;
				
				$container.replaceWith(data.body);
				document.title = data.title;
			} catch(err) {
				ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'ErrorInResponse']);
				return window.location = options.url;
			}
			
			ArticleAjaxLoading.track(['_trackEvent', 'ArticleAjaxLoading', 'PageView', 'G1']);
			
			if(window.WikiaPhotoGalleryView) {
				WikiaPhotoGalleryView.init.call(WikiaPhotoGalleryView);
			}
			
			if(window.ImageLightbox) {
				ImageLightbox.init.call(ImageLightbox);
			}
			
			if(window.RelatedPages) {
				RelatedPages.init.call(RelatedPages);
				RelatedPages.attachLazyLoaderEvents();
			}
			
			if(window.ArticleComments) {
				ArticleComments.init();
			}
			
			if(window.WikiaButtons) {
				WikiaButtons.init();
			}
			
			if(window.onFBloaded) {
				window.onFBloaded();
			}

			while(wgAfterContentAndJS.length > 0){
				wgAfterContentAndJS.shift()();
			}
			
			$('#article-comments').find('.session').html(ArticleAjaxLoading.cache.articleComments);
			
			$('#WikiaFooter').find('a').each(function() {
				if($(this).data('name') == 'whatlinkshere') {
					$(this).attr('href', wgArticlePath.replace('$1', 'Special:WhatLinksHere/' + wgPageName));
				}
			});
			
			var state = {
					pjax: options.container,
					timeout: options.timeout
			}
			
			if ( $.isPlainObject(state.pjax) )
				state.pjax = state.pjax.selector
				
			var query = $.param(options.data)
			if ( query != "_pjax=true" )
				state.url = options.url + (/\?/.test(options.url) ? "&" : "?") + query
				
			if ( options.replace ) {
				window.history.replaceState(state, document.title, options.url)
			} else if ( options.push ) {
				if ( !$.pjax.active ) {
					window.history.replaceState($.extend({}, state, {url:null}), oldTitle)
					$.pjax.active = true
				}
				window.history.pushState(state, document.title, options.url)
			}

			success.apply(this, arguments)
			
			$('body').css('cursor', 'auto');
			
			scroll(0,0);
			
			ArticleAjaxLoading.counter++;
		}
	}
	
	options = $.extend(true, {}, defaults, options)
	
	if ( $.isFunction(options.url) ) {
		options.url = options.url()
	}

	var xhr = $.pjax.xhr
	if ( xhr && xhr.readyState < 4) {
		xhr.onreadystatechange = $.noop
		xhr.abort()
	}

	$.pjax.xhr = $.ajax(options)
	$(document).trigger('pjax', $.pjax.xhr, options)
	
	return $.pjax.xhr
}

var popped = ('state' in window.history), initialURL = location.href
$(window).bind('popstate', function(event) {
	var initialPop = !popped && location.href == initialURL
	popped = true
	if ( initialPop ) return

	var state = event.state

	if ( state && state.pjax ) {
		var $container = $(state.pjax+'')
		if ( $container.length )
			$.pjax({
				url: state.url || location.href,
				container: $container,
				push: false,
				timeout: state.timeout
			})
		else
			window.location = location.href
	}
})

if ( $.event.props.indexOf('state') < 0 )
	$.event.props.push('state')

if ( !window.history || !window.history.pushState ) {
	$.pjax = $.noop
	$.fn.pjax = function() { return this }
}

})(jQuery);
/* PJAX */

$(function() {
	ArticleAjaxLoading.init();
});
