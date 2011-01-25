/* requires jquery */
/* requires AdEngine.js */
/* requires Liftium.js */
/* requires extensions/wikia/Geo/geo.js */
/* requires extensions/wikia/QuantcastSegments/qcs.js */
/* requires extensions/wikia/AdEngine/ghost */

///// BEGIN AdDriver
var AdDriver = {
	geoData: Geo.getGeoData(),
	minNumDARTCall: 3,
	cookieNameNumAllCall: 'adDriverNumAllCall',
	cookieNameNumDARTCall: 'adDriverNumDARTCall',
	cookieNameLastDARTCallNoAd: 'adDriverLastDARTCallNoAd',

	init: function() {
		window.adDriverLastDARTCallNoAds = new Array();
		window.adDriverAdCallComplete = new Array();
	},

	log: function(msg) {
		$().log('AdDriver: ' + msg);
	}
}

AdDriver.isNoAdWiki = function() {
	switch (wgDB) {
		case 'diabetesindogs':
		case 'help':
		case 'lahomeless':
		case 'wikicities':
			return true;
	}

	return false;
}

AdDriver.getAdProviderForSpecialCase = function(slotname) {
	switch (wgDB) {
		case 'geekfeminism':
		case 'kinkontap':
		case 'lostpedia':
		case 'sexpositive':
		case 'wswiki':
		case 'valuewiki':
			return 'Liftium';
			break;
		case 'glee':
		case 'lyricwiki':
			switch (slotname) {
				case 'CORP_TOP_RIGHT_BOXAD':
				case 'HOME_TOP_RIGHT_BOXAD':
				case 'TEST_HOME_TOP_RIGHT_BOXAD':
				case 'TEST_TOP_RIGHT_BOXAD':
				case 'TOP_RIGHT_BOXAD':
					return 'Liftium';
					break;
				default:
			}
			break;
		case 'howto':
			switch (slotname) {
				case 'CORP_TOP_LEADERBOARD':
				case 'HOME_TOP_LEADERBOARD':
				case 'TOP_LEADERBOARD':
					return 'NO-AD';
					break;	
				default:
					return 'Liftium';
			}
			break;
		case 'cookbook_import':
			//switch (slotname) {
				//case 'LEFT_SKYSCRAPER_2':
				//case 'LEFT_SKYSCRAPER_3':
				//case 'TOP_RIGHT_BOXAD':
					//return 'NO-AD';
					//break;	
				//default:
					//return 'Liftium';
			//}
			break;
		default:
	}

	switch (skin) {
		case 'answers':
			switch (slotname) {
				case 'CORP_TOP_LEADERBOARD':
				case 'HOME_TOP_LEADERBOARD':
				case 'TOP_LEADERBOARD':
					return 'NO-AD';
					break;
				case 'INCONTENT_BOXAD_1':
					switch (wgDB) {
						case 'answers':
							return 'NO-AD';
							break;
						default:
					}
					break;
				default:
			}
			break;
		default:
	}

	return '';
}

AdDriver.isHighValue = function(slotname) {
	switch (slotname) {
		case 'CORP_TOP_LEADERBOARD':
		case 'HOME_TOP_LEADERBOARD':
		case 'TOP_LEADERBOARD':
		case 'CORP_TOP_RIGHT_BOXAD':
		case 'HOME_TOP_RIGHT_BOXAD':
		case 'TEST_HOME_TOP_RIGHT_BOXAD':
		case 'TEST_TOP_RIGHT_BOXAD':
		case 'TOP_RIGHT_BOXAD':
		case 'HOME_TOP_RIGHT_BUTTON':
		case 'TOP_RIGHT_BUTTON':
		case 'HOME_INVISIBLE_TOP':
		case 'INVISIBLE_TOP':	// skin
		case 'INVISIBLE_1':		// footer
		case 'EXIT_STITIAL_BOXAD_1':
			// continue processing after switch
			break;
		default:
			return false;
	}

	if (typeof AdDriver.geoData != 'undefined' && AdDriver.geoData) {
		switch (AdDriver.geoData['country']) {
			case 'CA':
			case 'DE':
			case 'ES':
			case 'FR':
			case 'IT':
			case 'UK':
			case 'US':
				// continue processing after switch
				break;
			default:
				return false;
		}
	}
	else {
		return false;
	}

	return true;
}

AdDriver.getNumDARTCall = function(slotname) {
	var num = 0;

	num = AdDriver.getNumCall(AdDriver.cookieNameNumDARTCall, slotname);
	AdDriver.log(slotname + ' has ' + num + ' DART calls');

	return num;
}

AdDriver.getNumAllCall = function(slotname) {
	var num = 0;

	num = AdDriver.getNumCall(AdDriver.cookieNameNumAllCall, slotname);

	return num;
}

AdDriver.getNumCall = function(cookieName, slotname) {
	var num = 0;

	try {
		var numCallCookie = $.cookies.get(cookieName);
		if (typeof(numCallCookie) != 'undefined' && numCallCookie) {
			var slotnameObjs = $.parseJSON(numCallCookie);
			for (var i = 0; i < slotnameObjs.length; i++) {
				if (slotnameObjs[i].slotname == slotname) {
					if (parseInt(slotnameObjs[i].ts) + window.wgAdDriverCookieLifetime*3600000 > window.wgNow.getTime()) {	// wgAdDriverCookieLifetime in hours, convert to msec
						num = parseInt(slotnameObjs[i].num);
						break;
					}
				}
			}
		}
	}
	catch (e) {
		AdDriver.log(e.message);
	}

	return num;
}

AdDriver.incrementNumDARTCall = function(slotname) {
	return AdDriver.incrementNumCall(AdDriver.cookieNameNumDARTCall, slotname);
}

AdDriver.incrementNumAllCall = function(slotname) {
	return AdDriver.incrementNumCall(AdDriver.cookieNameNumAllCall, slotname);
}

AdDriver.incrementNumCall = function(cookieName, slotname) {

	var newSlotnameObjs = new Array();
	var num = 0;
	var timestamp = window.wgNow.getTime();
	var slotnameInCookie = false;

	try {
		var numCallCookie = $.cookies.get(cookieName);
		if (typeof(numCallCookie) != 'undefined' && numCallCookie) {
			// find slotname and increment count
			var slotnameObjs = $.parseJSON(numCallCookie);
			for (var i = 0; i < slotnameObjs.length; i++) {
				if (slotnameObjs[i].slotname == slotname) {
					slotnameInCookie = true;
					if (parseInt(slotnameObjs[i].ts) + window.wgAdDriverCookieLifetime*3600000 > window.wgNow.getTime()) {	// wgAdDriverCookieLifetime in hours, convert to msec
						num = parseInt(slotnameObjs[i].num);
						timestamp = parseInt(slotnameObjs[i].ts);
					}
					newSlotnameObjs.push( {slotname : slotname, num : ++num, ts : timestamp} );
				}
				else {
					newSlotnameObjs.push(slotnameObjs[i]);
				}
			}
		}
	}
	catch (e) {
		AdDriver.log(e.message);
	}

	if (!slotnameInCookie) {
		newSlotnameObjs.push( {slotname : slotname, num : ++num, ts : timestamp} );
	}

	var cookieOptions = {hoursToLive: window.wgAdDriverCookieLifetime, path: wgCookiePath};	// do not set cookie domain
	$.cookies.set(cookieName, JSON.stringify(newSlotnameObjs), cookieOptions);

	return num;
}

AdDriver.isLastDARTCallNoAd = function(slotname) {
	var value = false;

	try {
		var lastDARTCallNoAdCookie = $.cookies.get(AdDriver.cookieNameLastDARTCallNoAd);
		if (typeof(lastDARTCallNoAdCookie) != 'undefined' && lastDARTCallNoAdCookie) {
			var slotnameTimestamps = $.parseJSON(lastDARTCallNoAdCookie);
			for (var i = 0; i < slotnameTimestamps.length; i++) {
				if (slotnameTimestamps[i].slotname == slotname) {
					if (parseInt(slotnameTimestamps[i].ts) + window.wgAdDriverCookieLifetime*3600000 > window.wgNow.getTime()) {	// wgAdDriverCookieLifetime in hours, convert to msec
						value = true;
					}
					break;
				}
			}
		}
	}
	catch (e) {
		AdDriver.log(e.message);
	}

	AdDriver.log(slotname + ' last DART call had no ad? ' + value);

	return value;
}

AdDriver.setLastDARTCallNoAd = function(slotname, value) {
	var newSlotnameTimestamps = new Array();
	var slotnameInCookie = false;

	try {
		var lastDARTCallNoAdCookie = $.cookies.get(AdDriver.cookieNameLastDARTCallNoAd);
		if (typeof(lastDARTCallNoAdCookie) != 'undefined' && lastDARTCallNoAdCookie) {
			var slotnameTimestamps = $.parseJSON(lastDARTCallNoAdCookie);
			// look for slotname. If there is a new value, change the old value. If
			// the new value is null, simply do not include slotname in updated cookie.
			for (var i = 0; i < slotnameTimestamps.length; i++) {
				if (slotnameTimestamps[i].slotname == slotname) {
					slotnameInCookie = true;
					if (value) {
						newSlotnameTimestamps.push( {slotname: slotname, ts: value} );
					}
				}
				else {
					newSlotnameTimestamps.push(slotnameTimestamps[i]);
				}
			}
		}
	}
	catch (e) {
		AdDriver.log(e.message);
	}

	if (value && !slotnameInCookie) {
		newSlotnameTimestamps.push( {slotname: slotname, ts: value} );
	}

	if (newSlotnameTimestamps.length) {
		var cookieOptions = {hoursToLive: window.wgAdDriverCookieLifetime, path: wgCookiePath};	// do not set cookie domain
		$.cookies.set(AdDriver.cookieNameLastDARTCallNoAd, JSON.stringify(newSlotnameTimestamps), cookieOptions);
	}

	return value;
}

AdDriver.adjustSlotDisplay = function(slotname) {
	switch (slotname) {
		case 'CORP_TOP_LEADERBOARD':
		case 'HOME_TOP_LEADERBOARD':
		case 'TOP_LEADERBOARD':
			$('#'+slotname).removeClass('default-height');
			// if jumbo/expanding leaderboard, change padding-top and padding-bottom
			if (($('#'+slotname).height() >= 0 && $('#'+slotname).height() < 90) // expandable leaderboard, minimized state
			|| $('#'+slotname).height() > 95) { // jumbo leaderboard or expandable leaderboard, maximized state
				$('#'+slotname).css('padding-top', 0); 
			}
			return true;
			break;
		case 'CORP_TOP_RIGHT_BOXAD':
		case 'HOME_TOP_RIGHT_BOXAD':
		case 'TEST_HOME_TOP_RIGHT_BOXAD':
		case 'TEST_TOP_RIGHT_BOXAD':
		case 'TOP_RIGHT_BOXAD':
		case 'PREFOOTER_LEFT_BOXAD':
		case 'PREFOOTER_RIGHT_BOXAD':
			$('#'+slotname).removeClass('default-height');
			return true;
			break;
	}

	return false;
}

AdDriver.canCallLiftium = function(slotname) {
	switch (slotname) {	
		case 'HOME_TOP_RIGHT_BUTTON':
		case 'TOP_RIGHT_BUTTON':
		case 'HOME_INVISIBLE_TOP':
		case 'INVISIBLE_TOP':
		case 'INVISIBLE_1':
			return false;
			break;
	}

	return true;
}

AdDriver.getAdProvider = function(slotname, size, dartUrl) {
	var specialCaseAdProvider = AdDriver.getAdProviderForSpecialCase(slotname);
	if (specialCaseAdProvider) {
		return specialCaseAdProvider;
	}

	if (!dartUrl) {
		return 'Liftium';
	}

	if (AdDriver.isHighValue(slotname)) {
		if (AdDriver.getNumDARTCall(slotname) < AdDriver.minNumDARTCall || !AdDriver.isLastDARTCallNoAd(slotname)) {
			return 'DART';
		}
	}

	return 'Liftium';
}

AdDriver.init();
//// END AdDriver

//// BEGIN AdDriverDelayedLoaderItem
var AdDriverDelayedLoaderItem = function (slotname, size, dartUrl) {
	this.clientWidth = 0;
	this.clientHeight = 0;
	this.hasPrefooters = null;

	this.replaceTokensInDARTUrl = function(url) {

		// tile and ord are synchronized only for DART calls made by AdDriver.
		// DART calls made by Liftium will have different tile and ord values.

		// tile
		if (typeof(window.dartTile) == 'undefined') {
			window.dartTile = 1;
		}
		url = url.replace("tile=N;", "tile="+(window.dartTile++)+";");

		// ord
		if (typeof(window.dartOrd) == 'undefined') {
			window.dartOrd = Math.floor(Math.random()*10000000000000000);
		}
		url = url.replace("ord=N?", "ord="+window.dartOrd+"?");

		// screen resolution
		if (!this.clientWidth || !this.clientHeight) {
			this.clientWidth = document.documentElement.clientWidth || document.body.clientWidth;
			this.clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
		}
		if (this.clientWidth > 1024) {
			url = url.replace('dis=N;', 'dis=large;');
		} else {
			url = url.replace('dis=N;', '');
		}

		// prefooter ads?
		if (!this.hasPrefooters) {
			if (AdEngine.isSlotDisplayableOnCurrentPage('PREFOOTER_LEFT_BOXAD')) {
				this.hasPrefooters = 'yes';
			} else {
				this.hasPrefooters = 'no';
			}
		}
		url = url.replace('hasp=N;', 'hasp='+this.hasPrefooters+';');

		// Quantcast Segments
		if (typeof(QuantcastSegments) !== "undefined") {
			var qcsegs = QuantcastSegments.getQcsegAsDARTKeyValues();
			url = url.replace("qcseg=N;", qcsegs);
		}

		// impression count
		// the first time this is called, getNumAllCall() returns zero. To make this the 'first' impression, add one
		url = url.replace('impct=N;', 'impct=' + (parseInt(AdDriver.getNumAllCall(slotname))+1) + ';'); 

		// continent/region
		if (typeof AdDriver.geoData != 'undefined' && AdDriver.geoData) {
			var subdomain = '';
			switch (AdDriver.geoData['continent']) {
				case 'AF':
				case 'EU':
					subdomain = 'ad-emea';
					break;
				case 'AS':
					switch (AdDriver.geoData['country']) {
						// Middle East
						case 'AE':
						case 'CY':
						case 'BH':
						case 'IL':
						case 'IQ':
						case 'IR':
						case 'JO':
						case 'KW':
						case 'LB':
						case 'OM':
						case 'PS':
						case 'QA':
						case 'SA':
						case 'SY':
						case 'TR':
						case 'YE':
							subdomain = 'ad-emea';
							break;
						default:
							subdomain = 'ad-apac';
					}
					break;
				case 'OC':
					subdomain = 'ad-apac';
					break;
				case 'NA':
				case 'SA':
				default:
					subdomain = 'ad';
			}

			url = url.replace('http://ad.doubleclick', 'http://'+subdomain+'.doubleclick');
		}

		return url;
	};

	this.slotname = slotname;
	this.size = size;
	this.dartUrl = this.replaceTokensInDARTUrl(dartUrl);
}
//// END AdDriverDelayedLoaderItem

//// BEGIN AdDriverDelayedLoader
var AdDriverDelayedLoader = {
	adDriverItems: null,
	adNum: 0,
	currentAd: null,
	currentSlot: null,
	started: false,
	init: function() {
		AdDriverDelayedLoader.adDriverItems = new Array();
		AdDriverDelayedLoader.adNum = 0;
		AdDriverDelayedLoader.currentAd = null;
		AdDriverDelayedLoader.started = false;
	}
}

AdDriverDelayedLoader.init();

AdDriverDelayedLoader.appendItem = function(adDriverItem) {
	AdDriverDelayedLoader.adDriverItems.push(adDriverItem);
}

AdDriverDelayedLoader.prependItem = function(adDriverItem) {
	AdDriverDelayedLoader.adDriverItems.unshift(adDriverItem);
}

AdDriverDelayedLoader.callDART = function() {
	AdDriver.log(AdDriverDelayedLoader.currentAd.slotname + ': calling DART...');
	AdDriver.incrementNumDARTCall(AdDriverDelayedLoader.currentAd.slotname);
	AdDriver.setLastDARTCallNoAd(AdDriverDelayedLoader.currentAd.slotname, null);
	var slot = document.getElementById(AdDriverDelayedLoader.currentAd.slotname);

	ghostwriter(
		slot,
		{
			insertType: "append",
			script: { src: AdDriverDelayedLoader.currentAd.dartUrl },
			done: function() { 

				ghostwriter.flushloadhandlers();

				var nextAdProvider = null;

				if (typeof(window.adDriverLastDARTCallNoAds[AdDriverDelayedLoader.currentAd.slotname]) == 'undefined' || !window.adDriverLastDARTCallNoAds[AdDriverDelayedLoader.currentAd.slotname]) {
					AdDriver.log(AdDriverDelayedLoader.currentAd.slotname + ' was filled by DART');
					AdDriver.adjustSlotDisplay(AdDriverDelayedLoader.currentAd.slotname);
				}
				else {
					AdDriver.log(AdDriverDelayedLoader.currentAd.slotname + ' was not filled by DART');
					AdDriver.setLastDARTCallNoAd(AdDriverDelayedLoader.currentAd.slotname, window.wgNow.getTime());
					if (AdDriver.canCallLiftium(AdDriverDelayedLoader.currentAd.slotname)) {
						nextAdProvider = 'Liftium';
					}
				}

				if (nextAdProvider == 'Liftium') { 
					var liftiumItem = new AdDriverDelayedLoaderItem(AdDriverDelayedLoader.currentAd.slotname, AdDriverDelayedLoader.currentAd.size, ''); 
					AdDriverDelayedLoader.prependItem(liftiumItem); 
				} 
				else {
					// track ad call in Google Analytics, for forecasting.
					// Track only calls that do not fall back to Liftium.
					// (Those calls will be tracked by Liftium.)
					// Based on Liftium.callInjectedIframeAd
					Liftium.trackEvent(Liftium.buildTrackUrl(["slot", AdDriverDelayedLoader.currentAd.size+ "_" + AdDriverDelayedLoader.currentAd.slotname]), "UA-17475676-6");
				}
				AdDriverDelayedLoader.loadNext();
			}
		}
	);
}

AdDriverDelayedLoader.getPlaceHolderIframeScript = function(slotname, size) {
	var dims = size.split('x');
	return "document.write('<div id=\"Liftium_"+size+"_"+(++AdDriverDelayedLoader.adNum)+"\"><iframe width=\""+dims[0]+"\" height=\""+dims[1]+"\" id=\""+escape(slotname)+"_iframe\" noresize=\"true\" scrolling=\"no\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\" style=\"border:none;\" target=\"_blank\"></iframe><div>');";
}

AdDriverDelayedLoader.getLiftiumCallScript = function(slotname, size) {
	var script = '';

	if (slotname.indexOf('INVISIBLE_') > -1) {
		script = 'Liftium.callAd("'+size+'");';
	}
	else {
		script = AdDriverDelayedLoader.getPlaceHolderIframeScript(slotname, size);
		script += 'Liftium.callInjectedIframeAd("'+size+'", document.getElementById("'+escape(slotname)+'_iframe"));';
	}

	return script;
}

AdDriverDelayedLoader.callLiftium = function() {
	var slotname = AdDriverDelayedLoader.currentAd.slotname;

	if (!AdDriver.canCallLiftium(slotname)) {
		AdDriverDelayedLoader.loadNext();
		return;
	}

	AdDriver.log(slotname + ': calling Liftium...');

	var size = AdDriverDelayedLoader.currentAd.size;
	LiftiumOptions.placement = slotname;
	
	try {
		var script = AdDriverDelayedLoader.getLiftiumCallScript(slotname, size);
		var slot = document.getElementById(slotname);
		ghostwriter(
			slot,
			{
				insertType: "append",
				script: { text: script },
				done: function() {
					ghostwriter.flushloadhandlers();
					AdDriver.adjustSlotDisplay(slotname);
					AdDriverDelayedLoader.loadNext();
				}
			}
		);
	}
	catch (e) {
		AdDriver.log(e.message);
		AdDriverDelayedLoader.loadNext();
	}
}

AdDriverDelayedLoader.loadNext = function() {
	if (AdDriverDelayedLoader.adDriverItems.length) {
		AdDriverDelayedLoader.currentAd = AdDriverDelayedLoader.adDriverItems.shift();
		if (AdEngine.isSlotDisplayableOnCurrentPage(AdDriverDelayedLoader.currentAd.slotname)) {
			var adProvider = AdDriver.getAdProvider(AdDriverDelayedLoader.currentAd.slotname, AdDriverDelayedLoader.currentAd.size, AdDriverDelayedLoader.currentAd.dartUrl);

			// increment number of pageviews
			if (adProvider == 'DART' || adProvider == 'Liftium') {
				if (AdDriverDelayedLoader.currentSlot != AdDriverDelayedLoader.currentAd.slotname) {
					AdDriver.incrementNumAllCall(AdDriverDelayedLoader.currentAd.slotname);
					AdDriverDelayedLoader.currentSlot = AdDriverDelayedLoader.currentAd.slotname;
				}
			}

			if (adProvider == 'DART') {
				AdDriverDelayedLoader.callDART();
			}
			else if (adProvider == 'Liftium') {
				AdDriverDelayedLoader.callLiftium();
			}
			else {
				AdDriverDelayedLoader.loadNext();
			}
		}
		else {
			AdDriverDelayedLoader.loadNext();
		}
	}
	
	if (!AdDriverDelayedLoader.adDriverItems.length && typeof Liftium != 'undefined' && Liftium) {
		Liftium.hasMoreCalls = 0;
	}
}

// This functions reorders the queue of slots so that TOP_LEADERBOARD and
// TOP_RIGHT_BOXAD are first. It does not guarantee the order of the rest of
// the slots.
AdDriverDelayedLoader.reorderItems = function() {
	var highPriorityItems = ['TOP_LEADERBOARD', 'TOP_RIGHT_BOXAD'];
	var tmpItems = [];
	for (var i=0; i<AdDriverDelayedLoader.adDriverItems.length; i++) {
		var foundHighPriorityItem = false;
		for (var j=0; j<highPriorityItems.length; j++) {
			if (AdDriverDelayedLoader.adDriverItems[i].slotname.indexOf(highPriorityItems[j]) > -1) {
				// we have a high priority item. make sure TOP_LEADERBOARD is first in the reordered queue
				if (tmpItems.length && tmpItems[0].slotname.indexOf('TOP_LEADERBOARD') > -1) {
					tmpItems.splice(1, 0, AdDriverDelayedLoader.adDriverItems[i]);
				}
				else {
					tmpItems.unshift(AdDriverDelayedLoader.adDriverItems[i]);
				}
				foundHighPriorityItem = true;
				break;
			}
		}
		if (!foundHighPriorityItem) {
			tmpItems.push(AdDriverDelayedLoader.adDriverItems[i]);
		}
	}

	AdDriverDelayedLoader.adDriverItems = tmpItems;
}

AdDriverDelayedLoader.load = function() {
	AdDriverDelayedLoader.started = true;

	if (typeof wgNow != 'undefined' && AdDriverDelayedLoader.adDriverItems.length) {
		var loadTime = (new Date()).getTime() - wgNow.getTime();
		$().log('AdDriver started loading after ' + loadTime + ' ms');
	}

	if (AdDriver.isNoAdWiki() && typeof Liftium != 'undefined' && Liftium) {
		Liftium.hasMoreCalls = 0;
		return;
	}

	AdDriverDelayedLoader.reorderItems();

	AdDriverDelayedLoader.loadNext();
}

AdDriverDelayedLoader.reset = function() {
	AdDriverDelayedLoader.init();
}

AdDriverDelayedLoader.isRunning = function() {
	return AdDriverDelayedLoader.started && AdDriverDelayedLoader.adDriverItems.length;
}
//// END AdDriverDelayedLoader

$(window).bind('load', function() {
	AdDriverDelayedLoader.load();
});
