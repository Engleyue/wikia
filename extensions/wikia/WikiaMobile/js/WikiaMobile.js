var WikiaMobile = (function() {
	/** @private **/

	var body,
		page,
		article,
		allImages = [],
		handledTables,
		deviceWidth = ($.os.ios) ? 268 : 300,
		deviceHeight = ($.os.ios) ? 416 : 513,
		realWidth = window.innerWidth || window.clientWidth,
		realHeight = window.innerHeight || window.clientHeight,
		//TODO: finalize the following line and update all references to it (also in extensions)
		clickEvent = ('ontap' in window) ? 'tap' : 'click',
		touchEvent = ('ontouchstart' in window) ? 'touchstart' : 'mousedown',
		sizeEvent = ('onorientationchange' in window) ? 'orientationchange' : 'resize',
		tableWrapperHTML = '<div class=bigTable>',
		adSlot,
		shrData,
		pageUrl = wgServer + '/wiki/' + wgPageName,
		shrImgTxt, shrPageTxt, shrMailPageTxt, shrMailImgTxt,
		$1 =/__1__/g, $2 =/__2__/g, $3 =/__3__/g, $4 = /__4__/g,
		shrOpenNum = 0, shrImgName = '',
		fixed = Modernizr.positionfixed,
		d = document,

		querystring = {
			srh: window.location.search,

			toString: function(){
				return querystring.srh;
			},

			getVal: function(name, defVal){
				if(!querystring.cache){
					querystring.cache = {};
					var tmpQuery = querystring.srh.substr(1).split('&');
					for(var i=0; i < tmpQuery.length; i++){
						var tmp = tmpQuery[i].split('=');
						querystring.cache[tmp[0]] = tmp[1];
					}
					tmpQuery = tmp = null;
				}
				return querystring.cache[name] || defVal;
			}
		};

	function getImages(){
		return allImages;
	}

	//slide up the addressbar on webkit mobile browsers for maximum reading area
	//setTimeout is necessary to make it work on ios...
	function hideURLBar(){
		if(window.pageYOffset < 20) {
			setTimeout(function(){
					window.scrollTo(0, 1);
					if(!fixed) moveSlot();
			}, 0);
		}
	}

	function processSections(){
		var firstLevelSections = $('#wkMainCnt > h2');

		//avoid running if there are no sections which are direct children of the article section
		if(firstLevelSections.length > 0){
			var contents = article.childNodes,
				wrapper = article.cloneNode(false),
				root = wrapper,
				x,
				y = contents.length,
				currentSection,
				node,
				nodeName,
				isH2,
				goBck = '<span class=goBck>&uarr; '+$.msg('wikiamobile-hide-section')+'</span>';

			for (x=0; x < y; x++) {
				node = contents[x];
				nodeName = node.nodeName;
				isH2 = (nodeName == 'H2');

				if (nodeName != '#comment' && nodeName != 'SCRIPT') {
					if(node.id == 'WkMainCntFtr' || node.className == 'printfooter' || (node.className && node.className.indexOf('noWrap') > -1)){
						//do not wrap these elements
						currentSection.insertAdjacentHTML('beforeend', goBck);
						root = wrapper;
					}else if (isH2){
						if (currentSection) {
							currentSection.insertAdjacentHTML('beforeend', goBck);
							wrapper.appendChild(currentSection);
						}

						currentSection = d.createElement('section');
						currentSection.className = 'artSec';
						node = node.cloneNode(true);

						node.className += ' collSec';

						wrapper.appendChild(node);
						wrapper.appendChild(currentSection);
						root = currentSection;
						continue;
					}
					root.appendChild(node.cloneNode(true));
				}
			}

			page.replaceChild(wrapper, article);
		}
	}

	function processImages(){
		var number = 0,
		href = '', name = '', nameMatch = /[^\/]*\.\w*$/;

		$('.infobox .image, .wkImgStk, figure').not('.wkImgStk > figure').each(function(){
			var self = $(this),
			className = this.className;
			
			if(className.indexOf('image') > -1){
				href = this.href;
				name = this.attributes['data-image-name'].value.replace('.','-');
				if(name === shrImgName) shrOpenNum = number;
				allImages.push([href, name]);
				self.attr('data-num', number++);
			}else if(className.indexOf('wkImgStk') > -1){
				if(className.indexOf('grp') > -1) {
					var figures = self.find('figure'),
					l = figures.length,
					cap, img;
					
					self.attr('data-num', number);

					this.getElementsByTagName('footer')[0].insertAdjacentHTML('beforeend', l);

					$.each(figures, function(i, fig){
						img = fig.getElementsByClassName('image')[0];
						href = img.href;
						name = img.id;
						allImages.push([
							href, name,
							(cap = fig.getElementsByClassName('thumbcaption')[0])?cap.innerHTML:'',
							i, l
						]);

						if(name === shrImgName) shrOpenNum = number + i;
					});
				} else {
					var l = parseInt(this.attributes['data-img-count'], 10),
					lis = self.find('li');
					
					self.attr('data-num', number);

					$.each(lis, function(i, li){
						href = li.attributes['data-img'].value;
						name = href.match(nameMatch)[0].replace('.','-');
						allImages.push([
							href,
							name,
							li.innerHTML,
							//I need these number to show counter in a modal
							i, l
						]);

						if(name === shrImgName) shrOpenNum = number + i;
					});
				}
				number += l;
			} else {
				img = this.getElementsByClassName('image')[0];
				href = img.href;
				name = img.id;
				if(name === shrImgName) shrOpenNum = number;
				allImages.push([
					href, name,
					(cap = this.getElementsByClassName('thumbcaption')[0])?cap.innerHTML:''
				]);
				self.attr('data-num', number++);
			}
		});

		//if there is only one image in the article hide the prev/next buttons
		//in the image modal
		//TODO: move to a modal API call
		if(allImages.length <= 1) body.addClass('oneImg');
	}

	function processTables(){
		if(typeof handledTables == 'undefined'){
			handledTables = [];

			$('table').not('table table, .infobox, .toc').each(function(){
				var table = $(this),
				rows = table.find('tr'),
				rowsLength = rows.length;

				//find infobox like tables
				if(rowsLength > 2){
					var correctRows = 0,
					cellLength;

					$.each(rows, function(index, row) {
						cellLength = row.cells.length;

						if(cellLength > 2)
							return false;

						if(cellLength == 2)
							correctRows++;

						//sample only the first X rows
						if(index == 9)
							return false;
					});

					if(correctRows > Math.floor(rowsLength/2)) {
						table.addClass('infobox');
						return true;
					}
				}

				//if the table width is bigger than any screen dimension (device can rotate)
				//or taller than the allowed vertical size, then wrap it and/or add it to
				//the list of handled tables for speeding up successive calls
				//NOTE: tables with 100% width have the same width of the screen, check the size of the first row instead
				var firstRowWidth = rows.first().width(),
					tableHeight = table.height();

				table.computedWidth = firstRowWidth;
				table.computedHeight = tableHeight;

				if(firstRowWidth > realWidth || table.height() > deviceWidth){
					//remove scripts to avoid re-parsing
					table.find('script').remove();
					table.wrap(tableWrapperHTML);
					table.wasWrapped = true;
					table.isWrapped = true;
					handledTables.push(table);
				} else if(firstRowWidth > realHeight){
					table.wasWrapped = false;
					table.isWrapped = false;
					handledTables.push(table);
				}
			});

			if(handledTables.length > 0)
				window.addEventListener(sizeEvent, processTables, false);
		}else if(handledTables.length > 0){
			var table, row, isWrapped, isBig, wasWrapped,
				maxWidth = window.innerWidth || window.clientWidth;

			for(var x = 0, y = handledTables.length; x < y; x++){
				table = handledTables[x];
				row = table.find('tr').first();
				isWrapped = table.isWrapped;
				wasWrapped = table.wasWrapped;
				isBig = (table.computedWidth > maxWidth || table.computedHeight > deviceWidth);

				if(!isWrapped && isBig){
					if(!wasWrapped){
						table.wasWrapped = true;
						//remove scripts to avoid re-parsing
						table.find('script').remove();
					}

					table.wrap(tableWrapperHTML);
					table.isWrapped = true;
				}else if(isWrapped && !isBig){
					table.unwrap();
					table.isWrapped = false;
				}
			}
		}
	}

	function getDeviceResolution(){
		return [deviceWidth, deviceHeight];
	}

	function imgModal(number){
		$.openModal({
			imageNumber: number,
			toHide: '.chnImgBtn',
			addClass: 'imgMdl'
		});
	}

	function getClickEvent(){
		return clickEvent;
	}

	function getTouchEvent(){
		return touchEvent;
	}

	function track(ev){
		WikiaTracker.track('/1_mobile/' + ((ev instanceof Array) ? ev.join('/') : ev), 'main.sampled');
	}

	function moveSlot(plus){
		adSlot.style.top = Math.min((window.pageYOffset + window.innerHeight - 50 + (isFinite(plus)?plus:0)), ftr.offsetTop + 150) + 'px';
	}

	function loadShare(cnt){
		var handle = function(html){
			if(cnt.parentNode.id == 'wkShrPag'){
				cnt.innerHTML = html.replace($1, pageUrl).replace($2, shrPageTxt).replace($3, shrMailPageTxt).replace($4, shrPageTxt);

				$(cnt).delegate('li', clickEvent, function(){
					track('share/page/'+this.className.replace('Shr',''));
				});
			}else{
				var imgUrl = pageUrl + '?image=' + allImages[$.getCurrentImg()][1];
				cnt.innerHTML = html.replace($1,imgUrl).replace($2, shrImgTxt).replace($3, shrMailImgTxt).replace($4, shrImgTxt);
			}
		};

		if(!shrData){
			shrData = Wikia.Cache.get('shareButtons' + wgStyleVersion);
			shrPageTxt = $.msg('wikiamobile-sharing-page-text', wgTitle, wgSitename);
			shrMailPageTxt = encodeURIComponent($.msg('wikiamobile-sharing-email-text', shrPageTxt));
			shrImgTxt = $.msg('wikiamobile-sharing-modal-text', $.msg('wikiamobile-sharing-media-image'), wgTitle, wgSitename);
			shrMailImgTxt = encodeURIComponent($.msg('wikiamobile-sharing-email-text', shrImgTxt));
		}

		if(!shrData){
			$.nirvana.sendRequest({
				controller: 'WikiaMobileController',
				method: 'getShareButtons',
				format: 'html',
				type: 'GET',
				data: {
					'cb': wgStyleVersion
				},
				callback: function(result){
					Wikia.Cache.set('shareButtons' + wgStyleVersion, result, 604800/*7 days*/);
					shrData = result;
					handle(shrData);
				}
			});
		}else{
			handle(shrData);
		}
	}
	
	function openLogin(){
		console.log('This will open login soon');
	}

	/*
	 * POPOVER
	 * create or/and open callback has to be provided to create pop over
	 *
	 * options.on - (required) element that opens popover - throws an exception if not provided
	 * options.style - allows you to pass cssText for a content wrapper
	 * options.create - content of popover, either string or function that gets content wrapper as an attribute
	 * options.position - position of popover relative to the button 'bottom', 'top', 'left' , 'right'
	 * options.open - callback on open
	 * options.close - callback on close
	*/
	function popOver(options){
		options = options || {};

		var elm = options.on,
		initialized = false,
		isOpen = false,
		cnt;

		if(elm){
			elm.addEventListener(clickEvent, function(event){
				if(this.className.indexOf("on") > -1){
					close(event);
				}else{
					if(!initialized){
						var position = options.position || 'bottom',
							horizontal = (position == 'bottom' || position == 'top'),
							offset = (horizontal)?this.offsetHeight:this.offsetWidth,
							onCreate = options.create,
							style = options.style || '';

						this.insertAdjacentHTML('afterbegin', '<div class=ppOvr></div>');
						cnt = this.getElementsByClassName('ppOvr')[0];

						if(onCreate){
							changeContent(onCreate);
						}else if(!open){
							throw 'No content or on open callback provided';
						}

						this.className += ' ' + position;
						var pos = (horizontal)?(position == 'top'?'bottom':'top'):(position == 'left'?'right':'left');
						cnt.style[pos] = (offset + 15) + 'px';

						cnt.style.cssText += style;

						initialized = true;
					}

					open(event);
				}
			});
		}else{
			throw 'Non existing element';
		}

		function changeContent(onCreate){
			cnt.innerHTML = '';
			if(typeof onCreate == 'function'){
				$.showLoader($(cnt), {center: true, size: '20px'});
				onCreate(cnt);
			}else{
				cnt.insertAdjacentHTML('afterbegin', onCreate);
			}
		}

		function close(ev){
			if(isOpen){
				elm.className = elm.className.replace(" on", "");
				if(typeof options.close == 'function') {
					 options.close(ev, elm);
				}
				isOpen = false;
			}
		}

		function open(ev){
			if(!isOpen){
				elm.className += " on";
				if(typeof options.open == 'function') {
					options.open(ev, elm);
				}
				isOpen = true;
			}

		}

		return {
			changeContent: changeContent,
			close: close
		}
	}

	function reloadPage(){
		var location = window.location.href,
			delim = '?';

		if(location.indexOf(delim) > 0){
			delim = '&';
		}

		window.location.href = location.split("#")[0] + delim + "cb=" + Math.floor(Math.random()*10000);
	}

	//init
	$(function(){
		body = $(d.body);
		page = d.getElementById('wkPage');
		article = d.getElementById('wkMainCnt');
		ftr = d.getElementById('wkFtr');
		adSlot = d.getElementById('wkAdPlc');

		var navigationSearch = $(d.getElementById('wkNavSrh')),
		searchToggle = $(d.getElementById('wkSrhTgl')),
		searchInput = $(d.getElementById('wkSrhInp')),
		searchForm = $(d.getElementById('wkSrhFrm')),
		wkNav = d.getElementById('wkNav'),
		wkNavMenu = d.getElementById('wkNavMenu'),
		wikiNavHeader = wkNavMenu.getElementsByTagName('h1')[0],
		wikiNavLink = d.getElementById('wkNavLink'),
		navBar = d.getElementById('wkTopNav'),
		lvl1 = d.getElementById('lvl1'),
		//to cache link in wiki nav
		lvl2Link;

		//replace menu from bottom to topBar - the faster the better
		wkNav.replaceChild(wkNavMenu, d.getElementById('wkWikiNav'));

		//analytics
		track('view');

		processSections();//NEEDS to run before table wrapping!!!
		processTables();

		//add class for styling to be applied only if JS is enabled
		//(e.g. collapse sections)
		//must be done AFTER detecting size of elements on the page
		d.body.className += ' js';

		//handle ads
		var close = d.getElementById('wkAdCls'),
			i = 0;
			adExist = function(){
				if(adSlot.childElementCount > 3){
					close.className = "show";
					adSlot.style.height = '50px';
					close.addEventListener(clickEvent, function() {
						track('ad/close');
						adSlot.className += ' anim';
						if(!fixed) {
							moveSlot(70);
						}else{
							adSlot.style.bottom = '-70px';
						}
						setTimeout(function(){d.body.removeChild(adSlot);},800);
						window.removeEventListener('scroll', moveSlot);
					}, false);
					if(fixed){
						adSlot.style.position = 'fixed';
					}else{
						window.addEventListener('scroll', moveSlot, false);
					}
					return true;
				}
			};

		if(!adExist()) {
			var inter = setInterval(function() {
				if(!adExist() && i < 5) {
					i += 1;
				}else{
					d.body.removeChild(adSlot);
					clearInterval(inter);
				}
			}, 1000);
		}
		//end of handling ads

		hideURLBar();

		//get search input
		navigationSearch.remove().appendTo('#wkTopBar');

		//TODO: optimize selectors caching for this file
		body.delegate('.collSec', clickEvent, function(){
			var self = $(this);

			track(['section', self.hasClass('open')?'close':'open']);
			self.toggleClass('open').next().toggleClass('open');
		})
		.delegate('.goBck', clickEvent, function(){
			var top = $(this).parent().removeClass('open').prev().removeClass('open').offset().top;
			window.scrollTo(0, top);
		})
		.delegate('#wkMainCnt a', clickEvent, function(){
			track('link/content');
		})
		.delegate('.infobox .image, figure, .wkImgStk', clickEvent, function(event){
			event.preventDefault();
			event.stopPropagation();

			if(allImages.length == 0) processImages();

			var img = $(this),
				num = img.data('num') || img.parent().data('num');
			if(num) imgModal(num);
		})
		.delegate('.bigTable', clickEvent, function(event){
				event.preventDefault();

				$.openModal({
					addClass: 'wideTable',
					html: this.innerHTML
					});
		});

		searchForm.bind('submit', function(){
			track('search/submit');
		});

		searchToggle.bind(clickEvent, function(event){
			event.preventDefault();
			if(navBar.className.indexOf('srhOpn') > -1){
				track('search/toggle/close');
				if(searchInput.val()){
					searchForm.submit();
				}else{
					navBar.className = '';
					searchInput.val('');
				}
			}else{
				track('search/toggle/open');
				if(navBar.className.indexOf('fllNav') > -1) closeNav();
				navBar.className = 'srhOpn';
				searchInput.focus();
			}
		});

		//preparing the navigation
		wikiNavHeader.className = '';
		$(wkNavMenu).delegate('#lvl1 a', clickEvent, function(){
			track('link/nav/list');
		})
		.delegate('header > a', clickEvent, function(){
			track('link/nav/header');
		})
		.find('ul ul').parent().addClass('cld');

		d.getElementById('wkNavTgl').addEventListener(clickEvent, function(event){
			event.preventDefault();
			if(navBar.className.indexOf('fllNav') > -1){
				closeNav();
			}else{
				track('nav/open');
				page.style.height = 0;
				ftr.style.display = adSlot.style.display = 'none';
				//80px is for lvl1 without header
				navBar.className = 'fllNav';
				navBar.style.height = (lvl1.offsetHeight + 80) + 'px';
				window.location.hash = 'WikiNav';
			}
		});

		function closeNav() {
			if(window.location.hash == '#WikiNav') window.history.back();
			track('nav/close');
			wkNavMenu.className = 'cur1';
			page.style.height = 'auto';
			ftr.style.display = adSlot.style.display = 'block';
			if(!fixed) moveSlot();
			navBar.className = '';
			navBar.style.height = '40px';
		}

		//close WikiNav on back button
		if('onhashchange' in window) {
			window.addEventListener("hashchange", function() {
				if(window.location.hash == "" && navBar.className.indexOf('fullNav') > -1) {
					closeNav();
				}
			}, false);
		}

		$(lvl1).delegate('.cld', clickEvent, function(event) {
			if(event.target.parentNode.className.indexOf('cld') > -1) {
				event.stopPropagation();
				event.preventDefault();

				var self = $(this),
				element = self.children().first(),
				href = element.attr('href'),
				ul = self.find('ul').first().addClass('cur');

				wikiNavHeader.innerText = element.text();

				if(href) {
					wikiNavLink.href = href
					wikiNavLink.style.display = 'block';
				}else{
					wikiNavLink.style.display = 'none';
				}

				//130px is for lvl2/3 with a header
				navBar.style.height = (ul.height() + 130) + 'px';

				if(wkNavMenu.className == 'cur1'){
					wkNavMenu.className = 'cur2';
					lvl2Link = href;
					track('nav/level-2');
				}else{
					wkNavMenu.className = 'cur3';
					track('nav/level-3');
				}
			}
		});

		d.getElementById('wkNavBack').addEventListener(clickEvent, function() {
			var self = $(this),
			current;

			if(wkNavMenu.className == 'cur2') {
				wkNavMenu.className = 'cur1';
				setTimeout(function(){wkNavMenu.querySelector('.lvl2.cur').className = 'lvl2'}, 800);
				navBar.style.height = (lvl1.offsetHeight + 80) + 'px';
				track('nav/level-1');
			} else {
				wkNavMenu.className = 'cur2';
				setTimeout(function(){wkNavMenu.querySelector('.lvl3.cur').className = 'lvl3'}, 800);
				current = wkNavMenu.querySelector('.lvl2.cur');
				wikiNavHeader.innerText = current.previousSibling.text;
				navBar.style.height = (current.offsetHeight + 130) + 'px';
				if(lvl2Link) {
					wikiNavLink.href = lvl2Link;
					wikiNavLink.style.display = 'block';
				} else {
					wikiNavLink.style.display = 'none';
				}
				
				track('nav/level-2');
			}
		});
		//end of preparing the nav

		page.addEventListener(clickEvent, function(event){
			navBar.className = '';
			searchInput.val('');
		});

		$(d.getElementById('wkRelPag')).delegate('a', clickEvent, function(){
			track('link/related-page');
		});

		$(d.getElementById('wkArtCat')).delegate('a', clickEvent, function(){
			track('link/category');
		});

		d.getElementById('wkFllSite').addEventListener(clickEvent, function(event){
			event.preventDefault();
			track('link/fullsite');
			Wikia.CookieCutter.set('mobilefullsite', 'true');
			location.reload();
		});

		//category pages
		if(wgCanonicalNamespace == 'Category'){
			$('.pagMore, .pagLess').bind(clickEvent, function(event) {
				event.preventDefault();
				var self = $(this),
					forward = self.hasClass('pagMore'),
					parent = self.parent(),
					prev = (forward) ? parent.children('.pagLess') : self,
					prevBatch = ~~(prev.attr('data-batch')),
					next = (forward) ? self : parent.children('.pagMore'),
					nextBatch = prevBatch + 2,
					batch = (forward) ? nextBatch : prevBatch,
					add = (forward ? 1 : -1),
					id = parent.attr('id'),
					container = parent.find('ul');

				prev.attr('data-batch', prevBatch + add);
				next.attr('data-batch', nextBatch + add);

				self.toggleClass('active');
				$.showLoader(self);

				$.nirvana.sendRequest({
					controller: 'WikiaMobileController',
					method: 'getCategoryBatch',
					format: 'html',
					data:{
						category: wgTitle,
						batch: batch,
						index: encodeURIComponent(id.substr(-1, 1))
					},
					callback: function(result){
						container.remove();
						next.before(result);

						if(forward) {
							window.scrollTo(0, parent.prev().offset().top);
							track('category/next');
						}else{
							track('category/prev');
						}

						self.toggleClass('active');
						$.hideLoader(self);
	
						(batch > 1) ? prev.addClass('visible') : prev.removeClass('visible');

						(batch < ~~(parent.attr('data-batches'))) ? next.addClass('visible') : next.removeClass('visible');
					}
				});
			});

			d.getElementById('expAll').addEventListener(clickEvent, function(event) {
				var elements = $('.alphaSec .artSec').add('.alphaSec .collSec');
				if($(this).toggleClass('exp').hasClass('exp')){
					elements.addClass('open');
					track('category/expandAll');
				}else{
					elements.removeClass('open');
					track('category/collapseAll');
				}
			});

			$(d.getElementById('wkCatExh')).bind(clickEvent, function(event) {
				track('category/exhibition/click');
			});
		}
		//end category pages

		popOver({
			on: d.getElementById('wkShrPag'),
			create: loadShare,
			open: function(){
				track('share/page/open');
			},
			close: function(){
				track('share/page/close');
			},
			style: 'right:0;'
		});

		//if url contains image=imageName - open modal with this image
		shrImgName = querystring.getVal('image');
		if(shrImgName){
			if(allImages.length == 0) processImages();

			setTimeout(function(){imgModal(shrOpenNum)}, 1000);
		}
	});

	return {
		openLogin: openLogin,
		getImages: getImages,
		getDeviceResolution: getDeviceResolution,
		getClickEvent: getClickEvent,
		getTouchEvent: getTouchEvent,
		track: track,
		moveSlot: moveSlot,
		popOver: popOver,
		loadShare: loadShare,
		reloadPage: reloadPage,
		querystring: querystring
	};
})();