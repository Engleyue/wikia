var Crunchyroll = {

	lockTable: [],

	init: function() {
		$('#WikiaArticle').delegate('.wikia-paginator a.paginator-page', 'click', Crunchyroll.paginatorClick);
	},

	log: function(msg) {
		$().log(msg, 'Crunchyroll');
	},

	hideThrobber: function(){
		$('#toplists-loading-screen').remove();
	},

	showThrobber: function(elem){
		Crunchyroll.hideThrobber();
		elem.prepend('<div id="toplists-loading-screen"></div>');
	},

	paginatorClick : function(){

		var clickedObj = $(this);
		var pageSection = $('#WikiaArticle');

		if ( Crunchyroll.lockTable[ name ] == clickedObj.attr('data-page') ){
		 	return false;
		}

		Crunchyroll.lockTable[ 'crunchyroll' ] = clickedObj.attr('data-page');

		// tracking;
		if ( clickedObj.hasClass('paginator-next') ){
			Crunchyroll.track('pagination/next');
		} else if ( clickedObj.hasClass('paginator-prev') ) {
			Crunchyroll.track('pagination/prev');
		} else {
			Crunchyroll.track('/crunchyrollpagination/' + clickedObj.attr('data-page'));
		}

		Crunchyroll.log('begin: paginatorClick');
		Crunchyroll.showThrobber(pageSection.find('div.crunchyroll-holder'));
		var UrlVars = $.getUrlVars();
		var data = {
			action: 'ajax',
			articleId: wgArticleId,
			method: 'axGetPage',
			rs: 'CrunchyrollAjax',
			page: clickedObj.attr('data-page'),
			serie: $('#cr-serie-id').attr('data-serieid')
		};

		$.get(wgScript, data,
		function(axData){
			var goBack = clickedObj.attr( 'data-back' );
			var room1 = pageSection.find( 'div.crunchyroll-room1' );
			var room2 = pageSection.find( 'div.crunchyroll-room2' );
			pageSection.find( 'div.wikia-paginator' ).html( axData.paginator );
			Crunchyroll.hideThrobber();

			if ( typeof goBack !== "undefined" && goBack ){
				room2.html( room1.html() );
				room1.css( 'margin-left', ( -1 * room1.width() ) );
				room1.html( axData.page );
				room1.animate( { 'margin-left' : 0 }, 920);
				room1.queue( function () {
					room2.html('');
					$.dequeue( this );
				});

			} else {
				room2.html(axData.page);
				room1.animate( { 'margin-left' : (-1 * room1.width() ) }, 920);
				room1.queue(function () {
					room1.html( axData.page );
					room1.css( 'margin-left', 0 );
					room2.html('');
					$.dequeue( this );
				});
			}
		});

		return false;
	},

	track: function(fakeUrl) {
		$.tracker.byStr('crunchyroll/' + fakeUrl);
	}
};

//on content ready
wgAfterContentAndJS.push(Crunchyroll.init);