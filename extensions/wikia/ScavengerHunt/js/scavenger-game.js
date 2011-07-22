var ScavengerHunt = {
	currentArticleIndex: null,
	gameId: null,
	goodbyeData: null,
	huntData: null,
	progressBarVisible: false,
	spritePrepared: false,
	clueTimeout: null,

	MODAL_WIDTH: 588,
	LOCAL_CACHE_KEY: 'ScavengerHunt',

	//console logging
	log: function(msg) {
		$().log(msg, 'ScavengerHunt');
	},

	//track events
	track: function(fakeUrl) {
		window.jQuery.tracker.byStr('scavengerhunt/' + fakeUrl);
	},

	//global init
	init: function() {
		this.gameId = ScavengerHunt.getCookieHuntId();
		this.log('running gameId: ' + this.gameId);

		//check if there is a need to initialize JS for start page
		if ( window.wgScavengerHuntStart ) {
			this.initStartPage();
		}

		//check if there is a need to initialize JS for game
		if ( this.gameId ) {
			ScavengerHunt.initGame();
		}
	},

	//init starting page
	initStartPage: function() {
		ScavengerHunt.track('start/showButton');
		// check if game is already running

		$.each(
			window.wgScavengerHuntStart,
			function( index ) {
				$( '<div class="scavenger-start-button" />' )
				.css({top: wgScavengerHuntStartPosition[index].X, left: wgScavengerHuntStartPosition[index].Y})
				.append(
					$( '<input type="button" data-indexid="' + index + '" >' )
						.val( wgScavengerHuntStartMsg[index] )
						.click( ScavengerHunt.onStartClick )
					)
				.prependTo( '#WikiaArticle' );
			}
		)
	},

	//init article page
	initGame: function(redirect) {
		this.log('initGame begin');

		//TODO: this is somehow broken. Need double check if data is valid and from current hunt.
//		this.huntData = $.storage.get( this.LOCAL_CACHE_KEY );
		var cacheTimestamp = this.huntData ? this.huntData.cacheTimestamp : 0;
//		ScavengerHunt.setupHuntItemSprite();
//		if ( this.setupProgressBarSprite() ) {
//			this.showProgressBar();
//		}
		var data = {
			cacheTimestamp: cacheTimestamp,
			controller: 'ScavengerHunt',
			currentArticleId: wgArticleId,
			format: 'json',
			cb: Math.round(new Date().getTime()),
			method: 'getHuntData'
		};
		$.getJSON(
			window.wgScriptPath + '/wikia.php',
			data,
			function( json ) {
				ScavengerHunt.log( json );
				ScavengerHunt.currentArticleIndex = json.currentArticleIndex;
				//handle full response
				if ( json.huntData ) {
					ScavengerHunt.huntData = json.huntData;
//					$.storage.set(ScavengerHunt.LOCAL_CACHE_KEY, json.huntData);

					//game has been deleted
					if ( ScavengerHunt.huntData.noGame == true ) {
						ScavengerHunt.resetHuntId();
					} else {
						//game has been completed
						if (ScavengerHunt.huntData.completed) {
							ScavengerHunt.showEntryForm();
						} else {

							ScavengerHunt.track( 'game/hiddenImage/show/currentPage=' + wgPageName );
							//TODO: update progress bar
							ScavengerHunt.spritePrepared = false;
							if (ScavengerHunt.setupProgressBarSprite()) {
								ScavengerHunt.showProgressBar();
								ScavengerHunt.setupHuntItemSprite();
							}
						}
					}
				}
				if ( redirect ) {
					window.location.href = redirect;
				}
			}
		);
		this.log('initGame end');
	},

	//setup hunt item sprite
	setupHuntItemSprite: function() {
		ScavengerHunt.log('setupHuntItemSprite begin');

		var index = ScavengerHunt.currentArticleIndex;
		if ( index != null && index != -1 ) {
			if ( ScavengerHunt.huntData && $.inArray(index, ScavengerHunt.huntData.foundArticlesIndexes) == -1 ) {

				ScavengerHunt.log( index );
				//hunt image
				Sprite.insertSprite({
					parent: '#WikiaPage',
					cssClass: 'scavenger-ingame-image',
					id: 'scavenger-ingame-image',
					zIndex: 1001,
					pos: ScavengerHunt.huntData.articles[index].huntItem.pos,
					TL: ScavengerHunt.huntData.articles[index].huntItem.spriteTL,
					BR: ScavengerHunt.huntData.articles[index].huntItem.spriteBR,
					data: {name: ScavengerHunt.huntData.articles[index].huntItem.name, index: index},
					events: {click: ScavengerHunt.onHuntItemClick}
				});
			}
		}
		ScavengerHunt.log('setupHuntItemSprite end');
	},

	//setup progress bar sprite
	setupProgressBarSprite: function() {
		ScavengerHunt.log('setupProgressBarSprite begin');
		if (!ScavengerHunt.huntData || ScavengerHunt.spritePrepared) {
			ScavengerHunt.log('setupProgressBarSprite exiting... :: ' + (ScavengerHunt.huntData ? 'huntData:1 ::' : 'huntData:0 ::') + (ScavengerHunt.spritePrepared ? 'spritePrepared:1' : 'spritePrepared:0'));
			return false;
		}

		Sprite.clearState();
		Sprite.setURL(ScavengerHunt.huntData.spriteUrl);
		Sprite.preload();

		var zIndex = 1002;

		//progress bar
		var wrapper = $('<div id="scavenger-hunt-progress-bar">').appendTo('#WikiaPage');
		//background
		Sprite.addSprite({
			parent: wrapper,
			cssClass: 'scavenger-progress-image-bg',
			zIndex: zIndex++,
			pos: ScavengerHunt.huntData.progressBarBg.pos,
			TL: ScavengerHunt.huntData.progressBarBg.spriteTL,
			BR: ScavengerHunt.huntData.progressBarBg.spriteBR,
			data: {name: ScavengerHunt.huntData.progressBarBg.name}
		});
		//close button
		Sprite.addSprite({
			parent: wrapper,
			cssClass: 'scavenger-progress-image',
			zIndex: zIndex++,
			pos: ScavengerHunt.huntData.progressBarClose.pos,
			TL: ScavengerHunt.huntData.progressBarClose.spriteTL,
			BR: ScavengerHunt.huntData.progressBarClose.spriteBR,
			data: {name: ScavengerHunt.huntData.progressBarClose.name},
			events: {click: ScavengerHunt.onProgressBarCloseClick}
		});
		//hunt items
		$.each(ScavengerHunt.huntData.articles, function(k, v) {
			var type = $.inArray( k, ScavengerHunt.huntData.foundArticlesIndexes ) != -1 ? 'found' : 'notFound';
			Sprite.addSprite({
				parent: wrapper,
				cssClass: 'scavenger-progress-image',
				zIndex: zIndex++,
				pos: v[type].pos,
				TL: v[type].spriteTL,
				BR: v[type].spriteBR,
				data: {name: v[type].name, type: type, index: k},
				events: {
					click: ScavengerHunt.onProgressBarItemClick,
					mouseover: ScavengerHunt.onProgressBarItemEnter,
					mouseleave: ScavengerHunt.onProgressBarItemLeave
				}
			});
		});
		//clue box
		Sprite.addSprite({
			parent: wrapper,
			cssClass: 'scavenger-progress-image',
			zIndex: zIndex++,
			pos: ScavengerHunt.huntData.hintLabel.pos,
			TL: ScavengerHunt.huntData.hintLabel.spriteTL,
			BR: ScavengerHunt.huntData.hintLabel.spriteBR,
			id: 'scavenger-progress-clue-area',
			data: {name: ScavengerHunt.huntData.hintLabel.name}
		});

		ScavengerHunt.spritePrepared = true;
		ScavengerHunt.log('setupProgressBarSprite end');
		return true;
	},

	//handler start button
	onStartClick: function(e) {
		ScavengerHunt.log('onStartClick begin');
		ScavengerHunt.track('start/clickButton');

		var indexId = $(this).data('indexid');
		var gameId = window.wgScavengerHuntStart[indexId];

		$.showModal(
			window.wgScavengerHuntStartClueTitle[indexId],
			window.wgScavengerHuntStartClueHtml[indexId],
			{
				id: 'scavengerClueModal',
				width: ScavengerHunt.MODAL_WIDTH,
				callback: function() {
					$('#scavengerClueModal a.button').click(function(e) {
						ScavengerHunt.track('start/modalClue/clickButton');

						if ($(this).attr('href')) {
							var redirect = $(this).attr('href');
							e.preventDefault();
							ScavengerHunt.track('start/modalClue/clickButton' + gameId);
							ScavengerHunt.setCookieHuntId( gameId );
							ScavengerHunt.track('start/modalClue/clickButton2');
							//remove old data
//							$.storage.flush( this.LOCAL_CACHE_KEY );
							$.getJSON(
								window.wgScriptPath + '/wikia.php',
								{
									articleTitle: wgPageName,
									controller: 'ScavengerHunt',
									format: 'json',
									method: 'clearGameData'
								},
								function() {
									ScavengerHunt.log( 'game data cleared' );
									ScavengerHunt.initGame(redirect);
								}
							);
						} else {
							$('#scavengerClueModal').closest('.modalWrapper').closeModal();
						}
					});

					if (ScavengerHunt.gameId) {
						ScavengerHunt.track('start/modalClue/otherGameAlreadyInProgress');
						$('#scavengerConflictMessage').show();
					}
				}
			}
		);

		ScavengerHunt.log('onStartClick end');
	},

	showEntryForm: function() {
		if (!ScavengerHunt.huntData.completed) {
			return;
		}
		ScavengerHunt.track('game/modalEntryForm/show');
		//hide progress bar
		$('#scavenger-hunt-progress-bar').remove();
		ScavengerHunt.progressBarVisible = false;	//TODO: is it necessary?

		//show entry form
		$.showModal(
			ScavengerHunt.huntData.completed.title,
			ScavengerHunt.huntData.completed.text,
			{
				id: 'scavengerEntryFormModal',
				width: ScavengerHunt.MODAL_WIDTH,
				callback: function() {
					var w = $('#scavengerEntryFormModal');
					var b = w.find('.scavenger-clue-button input[type=submit]');
					var inputs = w.find('.scavenger-entry-form').find('input, textarea').not('input[type=submit]')
					var inputsChange = function() {
						var ok = true;
						inputs.each(
							function( i, v ) {
								if ( !$(v).val() ) {
									ok = false;
								}
							}
						);
						b.attr('disabled', ok ? false : 'disabled');
					};
					if ( inputs.length > 0 ) {
						inputs.blur(inputsChange).keyup(inputsChange);
						b.attr('disabled','disabled');
					}
					b.click( function(e) {
						ScavengerHunt.track('game/modalEntryForm/clickButton');
						e.preventDefault();
						b.attr('disabled','disabled');
						var cacheTimestamp = ScavengerHunt.huntData ? ScavengerHunt.huntData.cacheTimestamp : 0;
						var formdata = {
							cacheTimestamp: cacheTimestamp,
							controller: 'ScavengerHunt',
							currentArticleId: wgArticleId,
							format: 'json',
							method: 'pushEntry',
							name: w.find('input[name=name]').val(),
							email: w.find('input[name=email]').val(),
							answer: w.find('textarea[name=answer]').val()
						};
						$.postJSON(
							window.wgScriptPath + '/wikia.php',
							formdata,
							function(json) {
								ScavengerHunt.goodbyeData = json.result;
								if (json.result.status) {
									ScavengerHunt.track('game/modalEntryForm/saveOk');
									w.closeModal();
									ScavengerHunt.showGoodbyeForm();
								} else {
									ScavengerHunt.track('game/modalEntryForm/saveError');
									ScavengerHunt.log('entryForm error: ' + json.error);
									b.attr('disabled', '');
								}
							}
						);
					});
				},
				onClose: function() {
					ScavengerHunt.track('game/modalEntryForm/close');
					ScavengerHunt.quitHunt();
				}
			}
		);
	},

	showGoodbyeForm: function() {
		if (!ScavengerHunt.goodbyeData) {
			ScavengerHunt.log('cannot show goodbye popup - no data available');
			return false;
		}
		ScavengerHunt.resetHuntId();
		ScavengerHunt.track('game/modalGoodbye/show');
		$.showModal(
			ScavengerHunt.goodbyeData.goodbyeTitle,
			ScavengerHunt.goodbyeData.goodbyeContent,
			{
				id: 'scavengerGoodbyeModal',
				width: ScavengerHunt.MODAL_WIDTH,
				callback: function() {
					var w = $('#scavengerGoodbyeModal');
					w.find('.scavenger-clue-button a').click(function(e) {
						ScavengerHunt.track('game/modalGoodbye/clickButton');
						e.preventDefault();
						w.closeModal();
					});
					//TODO: Facebook known issue: does not respect href param and shares current page - hence we use our own button until it's fixed
					if (typeof FB == 'object') {
						$('.scavenger-share-button').show();
						$('#fb-share-button').click(function(e) {
							e.preventDefault();
							FB.ui({method:'stream.share', u: $(this).attr('href')});
						});
//						var share = $('.scavenger-share-button');
//						if (share.exists()) {
//							FB.XFBML.parse(share.get(0));
//						}
					}
				}
			}
		);
	},

	//new functions - TODO: review functions above, remove if unused
	showProgressBar: function(force) {
		if (ScavengerHunt.progressBarVisible && !force) {
			ScavengerHunt.log('ProgressBar visible, exiting...');
			return;
		}
		ScavengerHunt.log('showProgressBar invoked with gameId = ' + ScavengerHunt.gameId);
		Sprite.display();
		ScavengerHunt.progressBarVisible = true;
	},

	onProgressBarItemEnter: function(e) {
		var el = $(this);
		var type = el.data('type');
		var index = el.data('index');
		var data = ScavengerHunt.huntData.articles[index];

		ScavengerHunt.log('onProgressBarItemEnter invoked with target = ' + el.data('name'));
		clearTimeout(ScavengerHunt.clueTimeout);
		ScavengerHunt.clueTimeout = null;
		ScavengerHunt.showClueText(data.articleClue);

		if (type == 'notFound') {
			type = 'active'
			Sprite.changeSprite(el, {
				pos: data[type].pos,
				TL: data[type].spriteTL,
				BR: data[type].spriteBR,
				data: {name: data[type].name, type: type, index: index}
			});
		}
	},

	onProgressBarItemLeave: function(e) {
		var el = $(this);
		var type = el.data('type');

		ScavengerHunt.log('onProgressBarItemLeave invoked with target = ' + el.data('name'));
		if (!ScavengerHunt.clueTimeout) {
			ScavengerHunt.clueTimeout = setTimeout(ScavengerHunt.hideClueText, 200);
		}

		if (type == 'active') {
			type = 'notFound'
			var index = el.data('index');
			var data = ScavengerHunt.huntData.articles[index];
			Sprite.changeSprite(el, {
				pos: data[type].pos,
				TL: data[type].spriteTL,
				BR: data[type].spriteBR,
				data: {name: data[type].name, type: type, index: index}
			});
		}
	},

	showClueText: function(html) {
		var clueField = $('#scavenger-progress-clue-area');
		clearTimeout(ScavengerHunt.clueTimeout);
		ScavengerHunt.clueTimeout = null;
		clueField
			.html(html)
			.css({
				'line-height': clueField.height() + 'px',
				'font-size': ScavengerHunt.huntData.clueSize,
				'color': ScavengerHunt.huntData.clueColor,
				'font-weight': ScavengerHunt.huntData.clueFont})
			.bind('mouseover', function(e) {
				clearTimeout(ScavengerHunt.clueTimeout);
				ScavengerHunt.clueTimeout = null;
			})
			.bind('mouseout', function(e) {
				if (!ScavengerHunt.clueTimeout) {
					ScavengerHunt.clueTimeout = setTimeout(ScavengerHunt.hideClueText, 200);
				}
			})
			.show();
	},

	hideClueText: function () {
		ScavengerHunt.log('hideClueText invoked');
		$('#scavenger-progress-clue-area').hide();
	},

	onHuntItemClick: function(e) {
		var clickedElement = $(this);
		ScavengerHunt.log('onHuntItemClick invoked with target = ' + clickedElement.data('name'));
		ScavengerHunt.track('game/hiddenImage/click/currentPage=' + wgPageName);

		//show found item
		var index = ScavengerHunt.currentArticleIndex;
		var progressBarItem = null;
		//find proper progress bar item
		$.each($('#scavenger-hunt-progress-bar').children(), function() {
			if ($(this).data('index') == index) {
				progressBarItem = $(this);
				return false;
			}
		});
		//should not fail here
		if (!progressBarItem) {
			return false;
		}
		//switch it to 'found'
		var type = 'found';
		var elementData = ScavengerHunt.huntData.articles[index];

		// display congrats message and replace # with namber of articles left.
		var clueText =  elementData.congrats;
		var articlesLeft = ScavengerHunt.huntData.articles.length - ScavengerHunt.huntData.foundArticlesIndexes.length - 1;

		ScavengerHunt.showClueText(
			clueText.replace( "#", articlesLeft )
		);

		ScavengerHunt.log( 'showing found item on progress bar' );
		ScavengerHunt.log( progressBarItem );

		//animate element
		clickedElement.animate({opacity: 0}, {
			duration: 600,
			complete: function() {
				clickedElement.remove();

				//inform backend that user has found game item
				var data = {
					articleTitle: wgPageName,
					controller: 'ScavengerHunt',
					format: 'json',
					method: 'itemFound'
				};

				$.getJSON(
					window.wgScriptPath + '/wikia.php',
					data,
					function(json) {
						ScavengerHunt.log(json);

						//handle full response
						if (json.huntData) {
							ScavengerHunt.huntData = json.huntData;
							//$.storage.set(ScavengerHunt.LOCAL_CACHE_KEY, ScavengerHunt.huntData);

							if (ScavengerHunt.huntData.completed) {
								ScavengerHunt.showEntryForm();
							}
						}
					}
				);

				progressBarItem.animate({opacity: 0}, {
					duration: 300,
					complete: function() {
						//show find item on progress bar
						Sprite.changeSprite(progressBarItem, {
							pos: elementData[type].pos,
							TL: elementData[type].spriteTL,
							BR: elementData[type].spriteBR,
							data: {name: elementData[type].name, type: type, index: index}
						});
					}
				})
				.animate({opacity: 1}, {duration: 300});
			}
		});
	},

	onProgressBarCloseClick: function(e) {
		var targetItem = $(this).data('name');
		ScavengerHunt.log('onProgressBarCloseClick invoked with target = ' + targetItem);
		ScavengerHunt.track('progressBar/closeButton/click/currentPage=' + wgPageName + '/targetItem=' + targetItem);

		$.showCustomModal(ScavengerHunt.huntData.closeBox.title, ScavengerHunt.huntData.closeBox.content, {
			buttons: [
				{id: 'quit', message: ScavengerHunt.huntData.closeBox.buttonQuit, handler: function() {
					ScavengerHunt.track('progressBar/closeButton/quit/click');
					$('#scavenger-hunt-progress-bar').remove();
					$('#scavenger-ingame-image').remove();
					$('#scavemger-hunt-quit-dialog').closeModal();
					ScavengerHunt.quitHunt();
				}},
				{id: 'stay', defaultButton:true, message: ScavengerHunt.huntData.closeBox.buttonStay, handler: function() {
					ScavengerHunt.track('progressBar/closeButton/stay/click');
					$('#scavemger-hunt-quit-dialog').closeModal();
				}}
			],
			id: 'scavemger-hunt-quit-dialog',
			showCloseButton: false
		});
	},

	onProgressBarItemClick: function(e) {
		var targetItem = $(this).data('name');
		ScavengerHunt.log('onProgressBarItemClick invoked with target = ' + targetItem);
		ScavengerHunt.track('progressBar/targetItem/click/targetItem=' + targetItem);
		//TODO: using <a> in <div> for sprites is harder - let's stick to JS version for now
		window.location.href = ScavengerHunt.huntData.articles[$(this).data('index')].articleTitle;
	},

	quitHunt: function() {
		ScavengerHunt.resetHuntId();
		$.getJSON(
			window.wgScriptPath + '/wikia.php',
			{
				articleTitle: wgPageName,
				controller: 'ScavengerHunt',
				format: 'json',
				method: 'clearGameData'
			},
			function() {
				ScavengerHunt.log( 'game data cleared' );
			}
		);
	},

	getCookieHuntId: function() {
		return $.cookies.get(
			ScavengerHunt.getCookieKey(),
			{
				domain: wgCookieDomain
			}
		);
	},

	setCookieHuntId: function( gameId ) {
		$.cookies.set(
			ScavengerHunt.getCookieKey(),
			gameId,
			{
				hoursToLive: 24*7,
				domain: wgCookieDomain
			}
		);
	},

	resetHuntId: function() {
		$.cookies.del(
			ScavengerHunt.getCookieKey(),
			{
				domain: wgCookieDomain
			}
		);
		ScavengerHunt.gameId = 0;
	},

	getCookieKey: function() {
		if ( wgUserName == null ) {
			return 'ScavengerHuntInProgress';
		}
		var key = 'ScavengerHuntInProgress' + wgUserName.replace(/ /g, '_');
		this.log('cookie key: ' + key);
		return key;
	}
};

ScavengerHunt.init();