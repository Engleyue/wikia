/**
 * @author Federico "Lox" Lucignano <federico@wikia-inc.com>
 *
 * Interaction handlers for the creation/editing UI of TopLists extension
 */
$(function() {
	TopListsEditor._init();
});

var TopListsEditor = {
	length: 0,
	_mListContainer: null,
	_mAutocompleteField: null,
	
	_init: function(){
		TopListsEditor._mListContainer = $('#toplist-editor .ItemsList');
		TopListsEditor.length = TopListsEditor._mListContainer.find('li:not(.ItemTemplate)').length;
		TopListsEditor._mAutocompleteField = $('#toplist-editor input[name="related_article_name"]');

		$.loadJQueryAutocomplete(function(){
			TopListsEditor._mAutocompleteField.autocomplete({
				serviceUrl: wgServer + wgScript + '?action=ajax&rs=getLinkSuggest&format=json',
				appendTo: '#toplist-editor div.InputSet:last',
				deferRequestBy: 1000,
				maxHeight: 1000,
				selectedClass: 'selected',
				width: '270px'
			})
		});

		$.loadJQueryUI(function(){
			TopListsEditor._mListContainer.sortable({
				containment: '#toplist-editor .ItemsList',
				items: '.NewItem',
				handle: '.ItemDrag',
				placeholder: 'DragPlaceholder',
				axis: 'y',
				cursorAt: 'right',
				cursor: 'move',
				opacity: 0.5,
				revert: 200,
				scroll: true,
				update: TopListsEditor._fixLabels
			});

			TopListsEditor._mListContainer.find('li').disableSelection();
		});

		//events handlers
		TopListsEditor._mListContainer.find('li .ItemRemove a').click(TopListsEditor.removeItem);
		$('#toplist-add-item').click(TopListsEditor.addItem);
	},

	_fixLabels: function(){
		TopListsEditor._mListContainer.find('li:not(.ItemTemplate) .ItemNumber').each(function(index, elm){
			$(elm).html('#' + (index + 1));
		});
	},

	_addTitleToRemove: function(){
		//TODO: implement, used only in edit mode (move to separate js file perhaps?)
		//if edit mode add an hidden input containing the title of the item to remove
	},

	addItem: function(){
		TopListsEditor.length++;
		
		var item = TopListsEditor._mListContainer
			.find('li:first')
			.clone(true)
			.removeClass('ItemTemplate')
			.appendTo(TopListsEditor._mListContainer)
			.show();

			item
				.find('.ItemNumber')
				.text('#' + TopListsEditor.length);

			item
				.find('input[type=text]:disabled')
				.removeAttr('disabled');
	},

	removeItem: function(objItem){
		$(this)
			.closest('li')
			.remove();
		
		TopListsEditor.length--;
		TopListsEditor._fixLabels();

		//TODO: detect edit mode and call _addTitleToRemove
	}
};