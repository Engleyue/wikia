(function(window){

	var WE = window.WikiaEditor;

	WE.modules.Categories = $.createClass(WE.modules.base,{

		modes: true,

		headerClass: 'categories',
		headerTextId: 'categories-title',

		template: '<div></div>',
		data: {},

		init: function() {
			WE.modules.Categories.superclass.init.call(this);
			if (this.editor.config.categoriesDisabled) {
				this.enabled = false;
			}
		},

		afterRender: function() {
			var introText = this.editor.config.categoriesIntroText
			if (introText) {
				this.el.append($('<div>').addClass('info-text').text(introText));
			}
		},

		afterAttach: function() {
			this.el.append($('#csMainContainer').show());
			if (typeof initCatSelectForEdit == 'function') {
				csType = "module";
				initCatSelectForEdit();
			}

			// tracking
			this.el.bind({
				categorySelectAdd: this.proxy(function(ev) {this.track('add')}),
				categorySelectMove: this.proxy(function(ev) {this.track('move')}),
				categorySelectEdit: this.proxy(function(ev) {this.track('edit')}),
				categorySelectDelete: this.proxy(function(ev) {this.track('delete')})
			});

			// save
			this.editor.on('state', this.proxy(this.onStateChange));
		},

		onStateChange: function(editor, state) {
			if (state == editor.states.SAVING) {
				var categoriesCount = this.el.find('.CSitem').length;
				this.track('saveNumber', categoriesCount);
			}
		},

		track: function(ev, param) {
			this.editor.track(this.editor.getTrackerMode(), 'categories', ev, param);
		}

	});

	WE.modules.ToolbarCategories = $.createClass(WE.modules.ButtonsList,{

		modes: true,

		headerClass: 'categories_button',

		init: function() {
			WE.modules.ToolbarCategories.superclass.init.call(this);
			if (this.editor.config.categoriesDisabled) {
				this.enabled = false;
			}
		},

		items: [
			'CategoriesButton'
		]

	});

	WE.modules.RailCategories = WE.modules.Categories;

	window.wgEditorExtraButtons['CategoriesButton'] = {
		type: 'modulebutton',
		label: 'categories',
		title: 'Categories',
		module: 'RailCategories',
		autorenderpanel: true
	};

})(this);