var oAutoComp;
var categories;
var fixCategoryRegexp = new RegExp('\\[\\[(?:' + csCategoryNamespaces + '):([^\\]]+)]]', 'i');
var ajaxUrl = wgServer + wgScript + '?action=ajax';
var csType = 'edit';
var csMaxTextLength = 20;
var csDraggingEvent = false;

// macbre: generic tracking for CategorySelect (refs RT #68550)
function csTrack(fakeUrl) {
	if (window.skin == 'oasis') {
		// enterCategory -> enter
		fakeUrl = fakeUrl.replace(/^(.*)Cat(.*)$/g, '$1');
		$.tracker.byStr('action/category/' + fakeUrl);
	}
	else {
		$.tracker.byStr('articleAction/' + fakeUrl);
	}
}

// TODO: PORT AWAY FROM YUI
function initCatSelect() {
	if ( (typeof(initCatSelect.isint) != "undefined") && (initCatSelect.isint) ) {
		return true;
	}
	initCatSelect.isint = true;
	YAHOO.namespace('CategorySelect');
}

function positionSuggestBox() {
	if(csType != 'module') {
		$('#csSuggestContainer').css('top', ($('#csCategoryInput').get(0).offsetTop + $('#csCategoryInput').height() + 5) + 'px');
		$('#csSuggestContainer').css('left', Math.min($('#csCategoryInput').get(0).offsetLeft, ($(window).width() - $('#csItemsContainer').get(0).offsetLeft - $('#csSuggestContainer').width() - 10)) + 'px');
	}
}

function extractSortkey(text) {
	var result = {'name': text, 'sort' : ''};
	var len = text.length;
	var curly = 0;
	var square = 0;
	var pipePos = -1;
	for (var i = 0; i < len && pipePos == -1; i++) {
		switch (text.charAt(i)) {
			case '{':
				curly++;
				break;
			case '}':
				curly--;
				break;
			case '[':
				square++;
				break;
			case ']':
				square--;
				break;
			case '|':
				if (curly == 0 && square == 0) {
					pipePos = i;
				}
		}
	}
	if (pipePos != -1) {
		result.name = text.slice(0, pipePos);
		result.sort = text.slice(pipePos + 1);
	}
	return result;
}

function deleteCategory(e) {
	var catId = e.parentNode.getAttribute('catId');
	e.parentNode.parentNode.removeChild(e.parentNode);
	categories[catId] = null;
	$('#csWikitext').html(generateWikitextForCategories());

	$('#csItemsContainer').trigger('categorySelectDelete');
}

function modifyCategory(e) {
	var catId = e.parentNode.getAttribute('catId');
	defaultSortkey = categories[catId].sortkey != '' ? categories[catId].sortkey : (csDefaultSort != '' ? csDefaultSort : wgTitle);

	var category = categories[catId].category;
	var sortkey = defaultSortkey;
	var dialogContent = '<label for="csInfoboxCategory">' + csInfoboxCategoryText + '</label>' +
		'<br/><input type="text" id="csInfoboxCategory" />' +
		'<br/><label for="csInfoboxSortKey">' + csInfoboxSortkeyText.replace('$1', categories[catId].category).replace('<', '&lt;').replace('>', '&gt;') + '</label>' +
		'<br/><input type="text" id="csInfoboxSortKey" />';
	var dialogOptions = {
		id: 'sortDialog',
		width: 500,
		callbackBefore: function() {
			csTrack('sortSave');
			//fill up initial values
			$('#csInfoboxCategory').val(category);
			$('#csInfoboxSortKey').val(sortkey);
		},
		callback: function() {
			//focus input on displayed dialog
			$('#csInfoboxCategory').focus();
		},
		buttons: [
			{id:'sortDialogSave', defaultButton:true, message:csInfoboxSave, handler:function() {
				var category = $('#csInfoboxCategory').val();
				var sortkey = $('#csInfoboxSortKey').val();
				extractedParams = extractSortkey(category);
				category = extractedParams['name'];

				if (category == '') {
					//TODO: use jQuery dialog
					alert(csEmptyName);
					return;
				}

				$().log(category, "Cat");
				if (categories[catId].category != category) {
					categories[catId].category = category;
					$('#csItemsContainer a,#csItemsContainer li').each(function(i) {
						if ($(this).attr('catId') == catId) {
							$().log(category, "Cat");
							var elementText = category;
							if(csType == 'module') {
								elementText = $('<span>' + category + '</span>').get(0);
							}

							$(this).contents().eq(0).replaceWith(elementText);
							return false;
						}
					});
				}

				if (sortkey !== null) {
					if (sortkey == wgTitle || sortkey == csDefaultSort) {
						sortkey = '';
					}
					sortkey = extractedParams['sort'] + sortkey;
					categories[catId].sortkey = sortkey;
				}

				$('#sortDialog').closeModal();
			}}
		]
	};

	$.showCustomModal(csInfoboxCaption, dialogContent, dialogOptions);

	$('#csItemsContainer').trigger('categorySelectEdit');
}

function replaceAddToInput(e) {
	$('#csAddCategoryButton').hide();
	$('#csCategoryInput').show();
	positionSuggestBox();
	$('#csHintContainer').show();
	$('#csCategoryInput').focus();
}

function addAddCategoryButton() {
	if ($('#csAddCategoryButton').length > 0) {
		$('#csAddCategoryButton').show();
	} else {
		if(csType != 'module') {
			elementA = document.createElement('span');
			elementA.id = 'csAddCategoryButton';
			elementA.className = 'CSitem CSaddCategory'; //setAttribute doesn't work in IE
			elementA.tabindex = '-1';
			elementA.onfocus = 'this.blur()';
			elementA.onclick = function(e) {replaceAddToInput(this); return false;};

			elementImg = document.createElement('img');
			elementImg.src = wgBlankImgUrl;
			elementImg.className = 'sprite-small add';
			elementImg.onclick = function(e) {replaceAddToInput(this); return false;};
			elementA.appendChild(elementImg);

			elementText = document.createTextNode(csAddCategoryButtonText);
			elementA.appendChild(elementText);

			$('#csItemsContainer').get(0).appendChild(elementA);
		}
	}
}

function inputBlur() {
	var input = $('#csCategoryInput');
	if(csType != 'module') {
		input.hide();
		addAddCategoryButton();
	}
	$('#csHintContainer').hide();
}


function inputFocus(e) {
	collapseAutoComplete();
	$(e.target).val("").addClass('focus');
}

function addCategoryBase(category, params, index) {
	if (params === undefined) {
		params = {'namespace': csDefaultNamespace, 'outerTag': '', 'sortkey': ''};
	}

	if (index === undefined) {
		index = categories.length;
	}

	//replace full wikitext that user may provide (eg. [[category:abc]]) to just a name (abc)
	category = category.replace(fixCategoryRegexp, '$1');
	//if user provides "abc|def" explode this into category "abc" and sortkey "def"
	extractedParams = extractSortkey(category);
	category = extractedParams['name'];
	params['sortkey'] = extractedParams['sort'] + params['sortkey'];

	if (category == '') {
		alert(csEmptyName);
		return;
	}

	categories[index] = {'namespace': params['namespace'] ? params['namespace'] : csDefaultNamespace, 'category': category, 'outerTag': params['outerTag'], 'sortkey': params['sortkey']};
	var categoryText = category;
	if(csType == 'module') {

		elementA = document.createElement('li');
		if(categoryText.length > csMaxTextLength) {
			elementA.title = categoryText;
			categoryText = categoryText.substr(0,csMaxTextLength)  + '...';
		}
		elementText = $('<span>' + categoryText + '</span>').get(0);
	} else {
		elementA = document.createElement('a');
		elementText = document.createTextNode(categoryText);
	}

	elementA.className = 'CSitem';	//setAttribute doesn't work in IE
	elementA.tabindex = '-1';
	elementA.onfocus = 'this.blur()';
	elementA.setAttribute('catId', index);


	elementA.appendChild(elementText);

	elementImg = document.createElement('img');
	elementImg.src = wgBlankImgUrl;
	elementImg.className = 'sprite-small edit';
	elementImg.onclick = function(e) {if (csDraggingEvent) return; csTrack('sortCategory'); modifyCategory(this); return false;};
	elementA.appendChild(elementImg);

	elementImg = document.createElement('img');
	elementImg.src = wgBlankImgUrl;
	elementImg.className = 'sprite-small delete';

	elementImg.onclick = function(e) {if (csDraggingEvent) return; csTrack('deleteCategory'); deleteCategory(this); return false;};
	elementA.appendChild(elementImg);

	if(csType == 'module') {
		$("#csItemsContainer").append( elementA );
		$('#csItemsContainerDiv').scrollTop(100*100);
		$(elementA).append($("<span class='listmark' >&middot;</span>"));
	} else {
		$(elementA).insertBefore( $('#csCategoryInput') );
	}

	if ($('#csCategoryInput').css('display') != 'none') {
		collapseAutoComplete();
	}
}


function addCategory(category, params, index) {
	addCategoryBase(category, params, index);
	$('#csCategoryInput').attr('value', '');

	$('#csItemsContainer').trigger('categorySelectAdd');
}


function generateWikitextForCategories() {
	var categoriesStr = '';
	for (var c=0; c < categories.length; c++) {
		if (categories[c] === null) continue;
		catTmp = '\n[[' + categories[c].namespace + ':' + categories[c].category + (categories[c].sortkey == '' ? '' : ('|' + categories[c].sortkey)) + ']]';
		if (categories[c].outerTag != '') {
			catTmp = '<' + categories[c].outerTag + '>' + catTmp + '</' + categories[c].outerTag + '>';
		}
		categoriesStr += catTmp;
	}
	return categoriesStr.replace(/^\n+/, '');
}

function initializeCategories(cats) {
	//move categories metadata from hidden field [JSON encoded] into array
	if (typeof cats == 'undefined') {
		cats = $('#wpCategorySelectWikitext').length == 0 ? '' : $('#wpCategorySelectWikitext').attr('value');
		categories = cats == '' ? [] : eval(cats);
	} else {
		categories = cats;
	}

	//inform PHP what source should it use [this field exists only in 'edit page' mode]
	if ($('#wpCategorySelectSourceType').length > 0) {
		$('#wpCategorySelectSourceType').attr('value', 'json');
	}

	addAddCategoryButton();
	for (var c=0; c < categories.length; c++) {
		addCategoryBase(categories[c].category, {'namespace': categories[c].namespace, 'outerTag': categories[c].outerTag, 'sortkey': categories[c].sortkey}, c);
	}
	var input = $('#csCategoryInput');
}

function initializeDragAndDrop() {
	// sortable is a part of jQuery UI library - ensure it's loaded
	$.loadJQueryUI(function() {
		$('#csItemsContainer').sortable({
			items: '.CSitem:not(.CSaddCategory)',
			revert: 200,
			start: function(event, ui) {
				var srcEl = ui.item;
				var width = ( parseInt($(srcEl).css('width') ) +  3 ) + 'px';
				$(srcEl).css('width', width);
				csDraggingEvent = true;
			},
			stop: function(event, ui) {
				csDraggingEvent = false;
			},
			update: function(event, ui) {
				var srcEl = ui.item;

				var prevSibId = srcEl.prev('a, li').attr('catid');
				if (typeof prevSibId == 'undefined') prevSibId = -1;
				$().log('moving ' + srcEl.attr('catid') + ' before ' + prevSibId);
				moveElement(srcEl.attr('catid'), prevSibId);
			}
		});
	});
}

function toggleCodeView() {
	if ($('#csWikitextContainer').css('display') != 'block') {	//switch to code view
		$.tracker.byStr('editpage/codeviewCategory');

		$('#csWikitext').attr('value', generateWikitextForCategories());
		$('#csItemsContainer').hide();
		$('#csHintContainer').hide();
		$('#csCategoryInput').hide();
		$('#csSwitchView').html(csVisualView);
		$('#csWikitextContainer').show();
		$('#wpCategorySelectWikitext').attr('value', '');	//remove JSON - this will inform PHP to use wikitext instead
		$('#wpCategorySelectSourceType').attr('value', 'wiki');	//inform PHP what source it should use
	} else {	//switch to visual code
		$.tracker.byStr('editpage/visualviewCategory');

		$.post(ajaxUrl, {rs: "CategorySelectAjaxParseCategories", rsargs: $('#csWikitext').attr('value') + ' '}, function(result){
			if (result.error !== undefined) {
				$().log('AJAX result: error');
				alert(result.error);
			} else if (result.categories !== undefined) {
				$().log('AJAX result: OK');
				//delete old categories [HTML items]
				var items = $('#csItemsContainer').find('a');
				for (var i=items.length-1; i>=0; i--) {
					if (items.get(i).getAttribute('catId') !== null) {
						items.get(i).parentNode.removeChild(items.get(i));
					}
				}

				initializeCategories(result['categories']);
				$('#csSwitchView').html(csCodeView);
				$('#csWikitextContainer').hide();
				$('#csItemsContainer').show();
			}
		}, "json");
	}
}

function moveElement(movedId, prevSibId) {
	movedId = parseInt(movedId);
	prevSibId = parseInt(prevSibId);

	movedItem = categories[movedId];
	newCat = [];
	if (movedId < prevSibId) {	//move right
		newCat = newCat.concat(categories.slice(0, movedId),
			categories.slice(movedId+1, prevSibId+1),
			movedItem,
			categories.slice(prevSibId+1));
	} else {	//move left
		if (prevSibId != -1) {
			newCat = newCat.concat(categories.slice(0, prevSibId+1));
		}
		newCat = newCat.concat(movedItem,
			categories.slice(prevSibId+1, movedId),
			categories.slice(movedId+1));
	}
	//reorder catId in HTML elements
	var itemId = 0;
	var items = $('#csItemsContainer').find('a, li');
	for (var catId=0; catId < newCat.length; catId++) {
		if (newCat[catId] === undefined) {
			continue;
		}
		var catitem = $(items[itemId++])
		catitem.attr('catId', catId);
	}
	//save changes into main array
	categories = newCat;

	$('#csItemsContainer').trigger('categorySelectMove');
}

function inputKeyPress(e) {
	if(e.keyCode == 13) {
		csTrack('enterCategory');

		//TODO: stop AJAX call for AutoComplete
		e.preventDefault();
		var category = $('#csCategoryInput').val();

		if (category != '' && (window.oAutoComp && window.oAutoComp._oCurItem == null) /* BugId:5671 */) {
			addCategory(category);
		}

		$('#csWikitext').attr('value', generateWikitextForCategories());
		//hide input and show button when [enter] pressed with empty input
		if (category == '') {
			csTrack('enterCategoryEmpty');
			$('#csCategoryInput').blur();
			inputBlur();
		} 
	}
	if(e.keyCode == 27) {
		csTrack('escapeCategory');
		$('#csCategoryInput').blur();
		inputBlur();
	}
	positionSuggestBox();
}

function submitAutoComplete(comp, resultListItem) {
	addCategory(resultListItem[2][0]);
	replaceAddToInput();
}

function collapseAutoComplete() {
	if ((csType != 'module') && $('#csCategoryInput').css('display') != 'none' && $('#csWikitextContainer').css('display') != 'block') {
		$('#csHintContainer').show();
	}
        var wikiaMainContent = $('#WikiaMainContent');
        $('#WikiaFooter').css('z-index', Math.min(1, wikiaMainContent.css('z-index')));
        wikiaMainContent.css('z-index', 1);
}

function expandAutoComplete(sQuery , aResults) {
	$('#csHintContainer').hide();
        var wikiaFooter = $('#WikiaFooter');
        $('#WikiaMainContent').css('z-index', Math.max(2, wikiaFooter.css('z-index')));
        wikiaFooter.css('z-index', 1);
}

function regularEditorSubmit(e) {
	$('#wpCategorySelectWikitext').attr('value', $.toJSON(categories));
}

function getCategories(sQuery) {
	if(typeof categoryArray != 'object') {
		return;
	}
	var resultsFirst = [];
	var resultsSecond = [];
	sQuery = decodeURIComponent(sQuery);
	sQuery = sQuery.toLowerCase().replace(/_/g, ' ');
	for (var i = 0, len = categoryArray.length; i < len; i++) {
		var index = categoryArray[i].toLowerCase().indexOf(sQuery);
		if (index == 0) {
			resultsFirst.push([categoryArray[i]]);
		} else if (index > 0) {
			resultsSecond.push([categoryArray[i]]);
		}
		if (resultsFirst.length == 10) {
			break;
		}
	}
	return resultsFirst.concat(resultsSecond).slice(0,10);
}

// TODO: PORT AWAY FROM YUI
function initAutoComplete() {
	$.loadYUI(function() {
		// Init datasource
		var oDataSource = new YAHOO.widget.DS_JSFunction(getCategories);
		//oDataSource.queryMatchCase = true;

		// Init AutoComplete object and assign datasource object to it
		oAutoComp = new YAHOO.widget.AutoComplete('csCategoryInput', 'csSuggestContainer', oDataSource);
		oAutoComp.autoHighlight = false;
		oAutoComp.queryDelay = 0;
		oAutoComp.highlightClassName = 'CSsuggestHover';
		//fix for some ff problem
		oAutoComp._selectText = function() {}; 
		oAutoComp.queryMatchContains = true;
		oAutoComp.itemSelectEvent.subscribe(submitAutoComplete);
		oAutoComp.containerCollapseEvent.subscribe(collapseAutoComplete);
		oAutoComp.containerExpandEvent.subscribe(expandAutoComplete);
		//do not show delayed ajax suggestion when user already added the category
		oAutoComp.doBeforeExpandContainer = function (elTextbox, elContainer, sQuery, aResults) {return elTextbox.value != '';};
	});
}

function initHandlers() {
	//handle [enter] for non existing categories
	$('#csCategoryInput').keypress(inputKeyPress);
	$('#csCategoryInput').blur(inputBlur);
	if(csType == 'module') {
		$('#csCategoryInput').focus(inputFocus);
	}

	//TODO: add foucs
	if (typeof formId != 'undefined') {
		$('#'+formId).submit(regularEditorSubmit);
	}
}

//`view article` mode
function showCSpanel() {
	$.loadYUI(function() {
		initCatSelect();
		csType = 'view';
		$.post(ajaxUrl, {rs: 'CategorySelectGenerateHTMLforView'}, function(result){
			//prevent multiple instances when user click very fast
			if ($('#csMainContainer').exists()) {
				return;
			}

			var el = document.createElement('div');
			el.innerHTML = result;
			$('#catlinks').get(0).appendChild(el);
			initHandlers();
			initAutoComplete();
			initializeDragAndDrop();
			initializeCategories();

			// Dynamically load & apply the CSS.
			if (window.skin == 'oasis') {
				var cssPath = $.getSassCommonURL('/extensions/wikia/EditPageReskin/CategorySelect/oasis.scss');
			} else {
				var cssPath = wgExtensionsPath+'/wikia/EditPageReskin/CategorySelect/CategorySelect.css?'+wgStyleVersion;
			}

			importStylesheetURI(cssPath);

			setTimeout(replaceAddToInput, 60);
			setTimeout(positionSuggestBox, 666); //sometimes it can take more time to parse downloaded CSS - be sure to position hint in proper place
			$('#catlinks').removeClass('csLoading');
		}, "html");

		$('#csAddCategorySwitch').hide();
	});
}

function csSave() {
	csTrack('saveCategory');

	if ($('#csCategoryInput').attr('value') != '') {
		addCategory($('#csCategoryInput').attr('value'));
	}
	var pars = 'rs=CategorySelectAjaxSaveCategories&rsargs[]=' + wgArticleId + '&rsargs[]=' + encodeURIComponent($.toJSON(categories));
	//$.post(ajaxUrl, {rs: 'CategorySelectAjaxSaveCategories', 'rsargs[]': [wgArticleId, $.toJSON(categories)]}, function(result){
	$.ajax({
		url: ajaxUrl,
		data: pars,
		dataType: "json",
		success: function(result){
			if (result.info == 'ok' && result.html != '') {
				tmpDiv = document.createElement('div');
				tmpDiv.innerHTML = result['html'];
				var innerCatlinks = $('#mw-normal-catlinks').get(0);
				if (innerCatlinks) {
					$('#mw-normal-catlinks').get(0).parentNode.replaceChild(tmpDiv.firstChild, $('#mw-normal-catlinks').get(0));
				} else {
					$('#catlinks').get(0).insertBefore(tmpDiv.firstChild, $('#catlinks').get(0).firstChild);
				}
			} else if (result.error != undefined) {
				alert(result.error);
			}
			csCancel();
		}
	});

	// add loading indicator and disable buttons
	$('#csButtonsContainer').addClass('csSaving');
	$('#csSave').get(0).disabled = true;
	$('#csCancel').get(0).disabled = true;
}

function csCancel() {
	csTrack('cancelCategory');

	var csMainContainer = $('#csMainContainer').get(0);
	csMainContainer.parentNode.removeChild(csMainContainer);
	$('#csAddCategorySwitch').show();
}

initCatSelectForEdit = function() {
	$.loadJQueryUI(function() {
		initHandlers();
		initAutoComplete();
		initializeDragAndDrop();
		initializeCategories();
		$('#csHintContainer').hide();
		$('#csMainContainer').show();
	});
}