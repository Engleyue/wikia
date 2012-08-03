/**
 * Module used to control topbar on wikiamobile
 * has to be run onload event
 *
 * @author Jakub "Student" Olek
 */

define('topbar', ['querystring', 'loader', 'toc', 'track', 'events'], function (qs, loader, toc, track, events){
	var w = window,
		d = document,
		wkPrfTgl = d.getElementById('wkPrfTgl'),
		navBar = d.getElementById('wkTopNav'),
		wkPrf = d.getElementById('wkPrf'),
		searchInput = d.getElementById('wkSrhInp'),
		searchSug = d.getElementById('wkSrhSug'),
		searchForm = d.getElementById('wkSrhFrm'),
		wkNavMenu = d.getElementById('wkNavMenu'),
		wikiNavHeader = wkNavMenu.getElementsByTagName('h1')[0],
		wikiNavLink = d.getElementById('wkNavLink'),
		clickEvent = events.click,
		lvl2Link,
		minimumSet,
		searchInit = false;

	//replace menu from bottom to topBar - the faster the better
	d.getElementById('wkNav').replaceChild(wkNavMenu, d.getElementById('wkWikiNav'));

	function reset(stopScrolling){
		!stopScrolling && window.scrollTo(0,0);
		toc.close();
		w.location.hash = 'topbar';
		hidePage();
	}

	function openSearch(){
		closeNav();
		reset(true);
		track('search/toggle/open');
		navBar.className = 'srhOpn';
		searchForm.scrollIntoView();
		searchInput.focus();
	}

	function closeSearch(){
		if(navBar.className.indexOf('srhOpn') > -1){
			searchInput.value = '';
			track('search/toggle/close');
			showPage();
			searchSug.innerHTML = '';
		}
	}

	searchForm.addEventListener('submit', function(ev){
		if(searchInput.value === '') {
			ev.preventDefault();
		}else{
			track('search/submit');
		}
	});

	d.getElementById('wkSrhTgl').addEventListener(clickEvent, function(event){
		event.preventDefault();
		if(navBar.className.indexOf('srhOpn') > -1){
			closeDropDown();
		}else{
            initAutocomplete();
			openSearch();
		}
	});
	//end search setup

	//preparing the navigation
	wikiNavHeader.className = '';

	$(wkNavMenu).delegate('#lvl1 a', clickEvent, function(){
		track('link/nav/list');
	})
	.delegate('header > a', clickEvent, function(){
		track('link/nav/header');
	})
	//add chevrons to all elements that have child lists
	.find('ul ul').parent().addClass('cld');

	//close WikiNav on back button
	if('onhashchange' in w) {
		w.addEventListener('hashchange', function(ev) {
			if(w.location.hash == '' && navBar.className){
				closeDropDown();
			}
		}, false);
	}

	//navigation setup
	function openNav(){
		reset();
		track('nav/open');
		navBar.className = 'fllNav';

		if(!minimumSet){
			var uls2 = wkNavMenu.getElementsByClassName('lvl2'),
				uls3,
				max = d.getElementById('lvl1').offsetHeight,
				height,
				i = 0,
				ul2,
				check = function(ul){
					height = ul.offsetHeight;
					max = (height > max) ? height : max;
				},
				j,
				ul3,
				uls3l;

			while(ul2 = uls2[i++]){
				ul2.style.display = 'block';
				check(ul2);

				uls3 = ul2.getElementsByClassName('lvl3');
				for(j = 0, uls3l = uls3.length; j < uls3l; j++){
					ul3 = uls3[j];
					ul3.style.display = 'block';
					check(ul3);
					ul3.style.display = '';
				}

				ul2.style.display = '';
			}

			wkNavMenu.style.minHeight = (max + 50) + 'px';
			minimumSet = true;
		}
	}

	function closeNav(){
		if(navBar.className.indexOf('fllNav') > -1){
			track('nav/close');
			wkNavMenu.className = 'cur1';
			showPage();
		}
	}

	function handleHeaderLink(link){
		if(link) {
			wikiNavLink.href = link;
			wikiNavLink.style.display = 'block';
		} else {
			wikiNavLink.style.display = 'none';
		}
	}

	d.getElementById('wkNavTgl').addEventListener(clickEvent, function(event){
		event.preventDefault();
		if(navBar.className.indexOf('fllNav') > -1){
			closeDropDown();
		}else{
			openNav();
		}
	});

	$(d.getElementById('lvl1')).delegate('.cld', clickEvent, function(event) {
		//there are places where .cld is inside .cld I need to check for that
		//as this would prevent links to be clickable
		if(event.target.parentNode.className.indexOf('cld') > -1) {
			event.preventDefault();

			var element = this.childNodes[0],
				href = element.href;

			this.getElementsByTagName('ul')[0].className += ' cur';

			wikiNavHeader.innerText = element.innerText;

			handleHeaderLink(href);

			if(wkNavMenu.className == 'cur1'){
				lvl2Link = href;
				track('nav/level-2');
				wkNavMenu.className = 'cur2';
			}else{
				track('nav/level-3');
				wkNavMenu.className = 'cur3';
			}
		}
	});

	d.getElementById('wkNavBack').addEventListener(clickEvent, function() {
		var current;

		if(wkNavMenu.className == 'cur2') {
			setTimeout(function(){wkNavMenu.querySelector('.lvl2.cur').className = 'lvl2'}, 610);
			track('nav/level-1');
			wkNavMenu.className = 'cur1';
		} else {
			setTimeout(function(){wkNavMenu.querySelector('.lvl3.cur').className = 'lvl3'}, 610);
			current = wkNavMenu.querySelector('.lvl2.cur');
			wikiNavHeader.innerText = current.previousSibling.text;

			handleHeaderLink(lvl2Link);

			track('nav/level-2');
			wkNavMenu.className = 'cur2';
		}
	});
	//end navigation setup


	//profile/login setup
	if(wkPrfTgl){
		wkPrfTgl.addEventListener(clickEvent, function(event){
			event.preventDefault();
			if(navBar.className.indexOf('prf') > -1){
				closeDropDown();
			}else{
				openProfile();
			}
		});
	}
	//end profile/login setup

    function initAutocomplete(){
        if(!searchInit){
            Wikia.getMultiTypePackage({
                scripts: 'wikiamobile_autocomplete_js',
                ttl: 604800,
                callback: function(res){
                    Wikia.processScript(res.scripts[0]);
                    require('suggest', function(sug){
                        sug({
                            url: wgServer + '/api.php' + '?action=opensearch',
                            input: searchInput,
                            list: searchSug,
                            clear: d.getElementById('wkClear')
                        });
                    })
                }
            });
            searchInit = true;
        }
    }

	//hash - hash to be set to after returnto query
	//used in ie. ArticleComments.wikiamobile.js
	function openProfile(hash){
		reset();

		if(wgUserName){
			track('profile/open');
		}else{
			openLogin(hash);
		}

		navBar.className = 'prf';
	}

	function openLogin(hash){
		if(wkPrf.className.indexOf('loaded') == -1){
			loader.show(wkPrf, {center: true});
			Wikia.getMultiTypePackage({
				templates: [{
					controllerName: 'UserLoginSpecialController',
					methodName: 'index'
				}],
				messages: 'fblogin',
				styles: '/extensions/wikia/UserLogin/css/UserLogin.wikiamobile.scss',
				scripts: 'userlogin_facebook_js_wikiamobile',
				params: {
					useskin: w.skin
				},
				callback: function(res){
					loader.remove(wkPrf);

					Wikia.processStyle(res.styles);
					wkPrf.insertAdjacentHTML('beforeend', res.templates['UserLoginSpecialController_index']);
					Wikia.processScript(res.scripts);

					wkPrf.className += ' loaded';

					var wkLgn = document.getElementById('wkLgn'),
						form = wkLgn.getElementsByTagName('form')[0],
						query = new qs(form.getAttribute('action'));

					query.setVal('returnto', (wgCanonicalSpecialPageName && (wgCanonicalSpecialPageName.match(/Userlogin|Userlogout/)) ? wgMainPageTitle : wgPageName));
					hash && query.setHash(hash);
					form.setAttribute('action', query.toString());
				}
			});
		}
		track('login/open');
	}

	function closeDropDown() {
		closeNav();
		closeProfile();
		closeSearch();
		if(w.location.hash == "#topbar") {
			var pos = w.scrollY;
			w.history.back();
			w.scrollTo(0,pos);
		}
	}

	function closeProfile(){
		if(navBar.className.indexOf('prf') > -1){
			if(wgUserName){
				track('profile/close');
			}else{
				track('login/close');
			}
			showPage();
		}
	}

	function hidePage(){
		if(d.body.className.indexOf('hidden') == -1) {
			d.body.className += ' hidden';
		}
	}

	function showPage(){
		navBar.className = '';
		d.body.className = d.body.className.replace(' hidden', '');
	}

	return {
        initAutocomplete: initAutocomplete,
		openLogin: openLogin,
		openProfile: openProfile,
		openSearch: openSearch,
		closeProfile: closeProfile,
		closeNav: closeNav,
		closeSearch: closeSearch,
		closeDropDown: closeDropDown
	};
});