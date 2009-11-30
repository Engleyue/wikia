/*
 *  * Author: Inez Korczynski (korczynski at gmail dot com)
 *
 */

YAHOO.util.Event.addListener(["toggleEditingTips", "editingTips_close"], "click", function(e) {
	YAHOO.util.Event.preventDefault(e);
	if(YAHOO.util.Dom.hasClass(document.body, "editingWide")) {
		isWide = true;
	} else {
		isWide = false;
	}

	if(YAHOO.util.Dom.hasClass(document.body, "editingTips") && YAHOO.util.Dom.hasClass(document.body, "editingWide")) {
		SaveEditingTipsState(true, isWide);

		YAHOO.util.Dom.removeClass(document.body, "editingWide");
		if($G("toggleWideScreen")) {
			$G("toggleWideScreen").innerHTML = editingTipsEnterMsg ;
		}
		if($G("toggleEditingTips")) {
			$G("toggleEditingTips").innerHTML = editingTipsHideMsg;
		}

		WET.byStr('editingTips/toggle/editingTips/on');
	} else if(YAHOO.util.Dom.hasClass(document.body, "editingTips")) {
		SaveEditingTipsState(false, isWide);

		YAHOO.util.Dom.removeClass(document.body, "editingTips");
		if($G("toggleEditingTips")) {
			$G("toggleEditingTips").innerHTML = editingTipsShowMsg ;
		}
		WET.byStr('editingTips/toggle/editingTips/off');
	} else {
		SaveEditingTipsState(true, isWide);

		YAHOO.util.Dom.addClass(document.body, "editingTips");
		if(!showDone) {
			AccordionMenu.openDtById("firstTip");
			showDone = true;
		}
		if($G("toggleEditingTips")) {
			$G("toggleEditingTips").innerHTML = editingTipsHideMsg ;
		}
		WET.byStr('editingTips/toggle/editingTips/on');
	}
});

function SaveEditingTipsState(open,screen) {
	YAHOO.util.Connect.asyncRequest('GET', wgScriptPath + '/index.php?action=ajax&rs=SaveEditingTipsState&open='+open+'&screen='+screen);
}

function ToggleWideScreen(e) {
	if (typeof e != 'undefined') {
		YAHOO.util.Event.preventDefault(e);
	}

	if(YAHOO.util.Dom.hasClass(document.body, "editingTips") && YAHOO.util.Dom.hasClass(document.body, "editingWide")) {
		iEnabled = false;
	} else if(YAHOO.util.Dom.hasClass(document.body, "editingTips")) {
		iEnabled = true;
	} else {
		iEnabled = false;
	}

	if(YAHOO.util.Dom.hasClass(document.body, "editingWide")) {
		YAHOO.util.Dom.removeClass(document.body, "editingWide");
		YAHOO.util.Dom.removeClass(document.body, "editingTips");
		if($G("toggleWideScreen")) {
			$G("toggleWideScreen").innerHTML = editingTipsEnterMsg ;
		}
		//save state
		SaveEditingTipsState(iEnabled, false);
		WET.byStr('editingTips/toggle/widescreen/off');
	} else {
		YAHOO.util.Dom.addClass(document.body, "editingWide");
		YAHOO.util.Dom.addClass(document.body, "editingTips");
		if($G("toggleWideScreen")) {
			$G("toggleWideScreen").innerHTML = editingTipsExitMsg ;
		}
		if($G("toggleEditingTips")) {
			$G("toggleEditingTips").innerHTML = editingTipsShowMsg ;
		}

		//save state
		SaveEditingTipsState(iEnabled, true);
		WET.byStr('editingTips/toggle/widescreen/on');
	}
}

YAHOO.util.Event.addListener("toggleWideScreen", "click", ToggleWideScreen);

// tracking
YAHOO.util.Event.onDOMReady(function() {
	var editingTipsHeaders = $G('editingTips').getElementsByTagName("dt");

	YAHOO.util.Event.addListener(editingTipsHeaders, 'click', function(e) {
		var el = YAHOO.util.Event.getTarget(e);

		if (el.nodeName == 'SPAN') {
			el = el.parentNode;
		}

		if ( YAHOO.util.Dom.hasClass(el, 'a-m-t-expand') || YAHOO.util.Dom.hasClass(el, 'color1') ) {
			return;
		}

		tipId = (el.id == 'firstTip') ? 1 : (el.id.split('-')[1]);

		if (parseInt(tipId)) {
			WET.byStr('editingTips/expand/' + tipId);
		}
	});

	YAHOO.util.Event.addListener('editingTips_close', 'click', function(e) {WET.byStr('editingTips/close')});
});
