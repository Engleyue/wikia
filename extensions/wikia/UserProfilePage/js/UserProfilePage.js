$(function() {
	UserProfilePage._init();
});

var UserProfilePage = {
	_topWikisWrapper: null,
	_topPagesWrapper: null,
	_hiddenWikisWrapper: null,
	_hiddenTopPagesWrapper: null,
	_aboutSection: null,
	
	_init: function() {
		UserProfilePage._topWikisWrapper = $('#profile-top-wikis-body');
		UserProfilePage._topPagesWrapper = $('#profile-top-pages-body');
		UserProfilePage._aboutSection = $('#profile-content').find('.user-about-section');
		UserProfilePage._hiddenWikisWrapper = $('#profile-top-wikis-hidden');
		UserProfilePage._hiddenTopPagesWrapper = $('#profile-top-pages-hidden');
		UserProfilePage.attachEvents();
		UserProfilePage.enrichAboutSection();
	},
	
	doAction: function(name, type, value) {
		var wrapper = ( type === 'wiki' ) ? UserProfilePage._topWikisWrapper : UserProfilePage._topPagesWrapper;
		UserProfilePage.blockInput(wrapper);

		$.getJSON(
			wgScript,
			{
				'action': 'ajax',
				'rs': 'UserProfilePageHelper::doAction',
				'name': name,
				'type': type,
				'value': value
			},
			function(response) {
				UserProfilePage.unblockInput(wrapper);

				if(response.result === true) {
					if( response.type === 'page' ) {
						UserProfilePage._topPagesWrapper.replaceWith(response.html);
						UserProfilePage._topPagesWrapper = $('#profile-top-pages-body');
						UserProfilePage._hiddenTopPagesWrapper = $('#profile-top-pages-hidden');
					}

					if( response.type === 'wiki' ) {
						UserProfilePage._topWikisWrapper.replaceWith( response.html );
						UserProfilePage._topWikisWrapper = $('#profile-top-wikis-body');
						UserProfilePage._hiddenWikisWrapper = $('#profile-top-wikis-hidden');
					}
				}
			}
		);
	},

	attachEvents: function() {
		//handle click outside hidden elements popup menu
		UserProfilePage._topWikisWrapper.find('a.more').live( 'click', function() {
			if(!UserProfilePage._hiddenWikisWrapper.hasClass('user-profile-box')){
				UserProfilePage._hiddenWikisWrapper.addClass('user-profile-box');

				$('body').unbind('click.UserProfilePage_boxClose').bind('click.boxClose', function(event) {
					if (!$(event.target).closest('#profile-top-wikis-hidden').length) {
						UserProfilePage._hiddenWikisWrapper.removeClass('user-profile-box');
					};
				});
			}
		});

		//handle click outside hidden elements popup menu
		UserProfilePage._topPagesWrapper.find('a.more').live( 'click', function() {
			if(!UserProfilePage._hiddenTopPagesWrapper.hasClass('user-profile-box')){
				UserProfilePage._hiddenTopPagesWrapper.addClass('user-profile-box');

				$('body').unbind('click.UserProfilePage_boxClose').bind('click.boxClose', function(event) {
					if (!$(event.target).closest('#profile-top-pages-hidden').length) {
						UserProfilePage._hiddenTopPagesWrapper.removeClass('user-profile-box');
					};
				});
			}
		});

		UserProfilePage._topWikisWrapper.find('.HideButton').live('click', function(){
			UserProfilePage.doAction('hide', 'wiki', $(this).attr('data-id' ));
		});

		UserProfilePage._topWikisWrapper.find('.UnhideButton').live('click', function(){
			UserProfilePage._hiddenWikisWrapper.removeClass('user-profile-box');
			UserProfilePage.doAction('unhide', 'wiki', $(this).attr('data-id' ));
		});

		UserProfilePage._topPagesWrapper.find('.HideButton').live('click', function(){
			UserProfilePage.doAction('hide', 'page', $(this).attr('data-id' ));
		});

		UserProfilePage._topPagesWrapper.find('.UnhideButton').live('click', function(){
			UserProfilePage._hiddenTopPagesWrapper.removeClass('user-profile-box');
			UserProfilePage.doAction('unhide', 'page', $(this).attr('data-id' ));
		});
		$('#WikiaPage').click(UserProfilePage.trackClick);
	},

	enrichAboutSection: function(){
		var section = UserProfilePage._aboutSection;
		
		section.find('.answer,.question').each(function(){
			var elm = $(this);
			elm.parent().prepend($('<img src="' + section.attr('data-' + ((elm.hasClass('question')) ? 'generic' : 'user') + '-avatar') + '">'));
		});
	},

	unblockInput: function(context){
		context.find('.profile-loading-screen').remove();
	},

	blockInput: function(context){
		UserProfilePage.unblockInput(context);
		context.prepend('<div class="profile-loading-screen"></div>');
	},

	track: function(fakeUrl) {
		$.tracker.byStr('profile/' + fakeUrl);
	},

	trackClick: function(ev) {
		var node = $(ev.target);
		if (node.is('img')) {
			node = node.parent();
		}
		if (node.hasParent('#WikiaRail')) {
			if (node.hasParent('#profile-top-wikis-body')) {
				if (node.hasParent('.wordmark')) {
					UserProfilePage.track('top_wikis/wiki');
				} else if (node.is('.HideButton')) {
					UserProfilePage.track('top_wikis/hide');
				}
			} else if (node.is('.WikiaActivityModule ul a')) {
				UserProfilePage.track('recent_activity_link');
			} else if (node.is('.WikiaActivityModule .more')) {
				UserProfilePage.track('recent_activity_more');
			} else if (node.hasParent('.top-page-item')) {
				UserProfilePage.track('top_pages');
			}
		} else if (node.hasParent('#WikiaMainContent .tabs-container')) {
			if(node.parent().attr('data-id') == "talk") {
				UserProfilePage.track('talk_tab');
			} else if(node.parent().attr('data-id') == "profile") {
				UserProfilePage.track('profile_tab');
			} else if(node.parent().attr('data-id') == "contribs") {
				UserProfilePage.track('contribs_tab');
			} else if(node.parent().attr('data-id') == "blog") {
				UserProfilePage.track('blog_tab');
			}
		}
	}
};