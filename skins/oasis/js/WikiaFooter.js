$(function() {
	WikiaFooterApp.init();
});

WikiaFooterApp = {

	init: function() {
		//Variables
		var footer = $("#WikiaFooter");
		var toolbar = footer.children(".toolbar");

		// avoid stack overflow in IE (RT #98938)
		if (toolbar.exists()) {
			var windowObj = $(window);
			var originalWidth = toolbar.width();

			//Scroll Detection
			windowObj.resolvePosition = function() {
				var scroll = windowObj.scrollTop() + windowObj.height();
				var line = 0;
				if(footer.offset()){
					line = footer.offset().top + toolbar.outerHeight();
				}

				if (scroll > line && toolbar.hasClass("float")) {
					toolbar.removeClass("float");
					windowObj.centerBar();
				} else if (scroll < line && !toolbar.hasClass("float")) {
					toolbar.addClass("float");
					windowObj.centerBar();
				}
			};

			windowObj.centerBar = function() {
				var w = windowObj.width();
				if(w < originalWidth && toolbar.hasClass('float')) {
					toolbar.css('width', w+10);
					if(!toolbar.hasClass('small')){
						toolbar.addClass('small');
					}
				} else if(toolbar.hasClass('small')) {
					toolbar.css('width', originalWidth);
					toolbar.removeClass('small');
				}
				windowObj.resolvePosition();
			};
			
			if(jQuery.support.positionFixed){
				windowObj.resolvePosition();
				windowObj.centerBar();
				windowObj.scroll(windowObj.resolvePosition);
				windowObj.resize(windowObj.centerBar);
			}
			
			WikiaFooterApp.toolbar = new ToolbarCustomize.Toolbar( footer.find('.tools') );
		}
	}

};

(function(){
	window.ToolbarCustomize = window.ToolbarCustomize || {};
	var TC = window.ToolbarCustomize;
	
	TC.MenuGroup = $.createClass(Observable,{
		
		showTimer: false,
		hideTimer: false,
		
		showTimeout: 300,
		hideTimeout: 350,
		
		showing: false,
		visible: false,
		
		constructor: function() {
			TC.MenuGroup.superclass.constructor.call(this);
			this.showTimer = Timer.create($.proxy(this.show,this),this.showTimeout);
			this.hideTimer = Timer.create($.proxy(this.hide,this),this.hideTimeout);
		},
		
		add: function( el ) {
			var e = $(el);
			e
				.unbind('.menugroup')
				.bind('mouseenter.menugroup',$.proxy(this.delayedShow,this))
				.bind('mouseleave.menugroup',$.proxy(this.delayedHide,this))
				.children('a','img')			
					.unbind('.menugroup')
					.bind('click.menugroup',$.proxy(this.showOnClick,this));
		},
		
		remove: function( el ) {
			$(el)
				.unbind('.menugroup')
				.children('a','img')
					.unbind('.menugroup');
		},
		
		show: function( evt ) {
			this.hideTimer.stop();
			this.showTimer.stop();
			if (!this.showing || this.visible == this.showing) {
				return;
			}
			
			if (this.visible)
				this.hide();
			if (this.showing) {
				this.visible = this.showing;
				this.showing = false;
				this.fire('menushow',this,this.visible,this.visible.children('ul'));
				this.visible.children('ul').show();
			}
		},
		
		delayedShow: function( evt ) {
			this.showing = $(evt.currentTarget);
			if (this.visible) {
				this.show(evt);
			} else {
				this.hideTimer.stop();
				this.showTimer.start();
			}
		},
		
		showOnClick: function( evt ) {
			evt.preventDefault();
			this.showing = $(evt.currentTarget).parent();
			this.show(evt);
		},
		
		hide: function( evt ) {
			this.hideTimer.stop();
			this.showTimer.stop();
			if (this.visible) {
				this.fire('menuhide',this,this.visible,this.visible.children('ul'));
				this.visible.children('ul').hide();
				this.visible = false;
			}
		},
		
		delayedHide: function( evt ) {
			this.hideTimer.stop();
			if (this.visible) {
				this.hideTimer.start();
			} else if (this.showing) {
				this.showing = false;
				this.showTimer.start();
			}
		}
		
	});
	
	TC.Toolbar = $.createClass(Object,{
		
		el: false,
		more: false,

		menuGroup: false,
		
		constructor: function ( el ) {
			TC.Toolbar.superclass.constructor.call(this);
			this.el = el;
			this.menuGroup = new TC.MenuGroup();
			this.menuGroup.bind('menushow',this.onShowMenu,this);
			this.initialize();
		},
		
		initialize: function() {
			this.el.find('.tools-customize').click($.proxy(this.openConfiguration,this));
			this.menuGroup.add(this.el.find('li.menu'));
			this.handleMore();
		},
		
		openConfiguration: function( evt ) {
			evt.preventDefault();
			var conf = new TC.ConfigurationLoader(this);
			conf.show();
		},
		
		createMore: function () {
			var caption = this.el.attr('data-more-caption') || 'more ...';
			return $(
				'<li class="menu disable-more more-menu">'
				+'<span class="arrow-icon-ctr"><span class="arrow-icon arrow-icon-top"></span><span class="arrow-icon arrow-icon-bottom"></span></span>'
				+'<a href="#">'+caption+'</a>'
				+'<ul class="tools-menu"></ul>'
				+'</li>'); 
		},
		
		handleMore: function () {
			var all = this.el.children('li');
			var moreable = all.not('.disable-more');
			var where = all.filter('.menu').last();
			
			var width = 0, mwidth = 0, fwidth = this.el.innerWidth() - 5;
			all.each(function(i,v){width += $(v).outerWidth();});
			moreable.each(function(i,v){mwidth += $(v).outerWidth();});
				
			if (width < fwidth) {
				return;
			}
			
			var li_more = this.createMore();
			
			if (where.exists()) where.before(li_more)
			else this.el.append(li_more);
			var more = li_more.children('ul');
			var moreWidth = li_more.outerWidth() + 5;
			
			var rwidth = fwidth - moreWidth - (width - mwidth);
			moreable.each(function(i,v){
				rwidth -= $(v).outerWidth();
				if (rwidth < 0)
					$(v).prependTo(more);
			});
			this.menuGroup.add(li_more,$.proxy(this.onShowMenu,this));
		},
		
		onShowMenu: function( mgroup, li, ul ) {
			var right = this.el.offset().left + this.el.innerWidth() - li.offset().left - li.outerWidth();
			ul.css('left', (li.offset().left-this.el.offset().left)+'px');
			ul.css('right','auto');
		},
		
		load: function(html) {
			this.el.children('li').not('.loadtime').remove();
			this.el.append($(html));
			this.initialize();
		}
		
	});
	
	TC.ConfigurationLoader = $.createClass(Object,{
		
		constructor: function( toolbar ) {
			this.toolbar = toolbar;
		},
		
		show: function() {
			$.loadLibrary('ToolbarCustomize',
				stylepath + '/oasis/js/ToolbarCustomize.js?' + wgStyleVersion,
				typeof TC.Configuration,
				$.proxy(function(){
					var c = new TC.Configuration(this.toolbar);
					c.show();
				},this)
			);
		}
		
	});
	
	
})();