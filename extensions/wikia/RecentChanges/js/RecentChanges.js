jQuery(function($) {
	var RecentChanges = {
		init: function() {
			this.$table = $('.mw-recentchanges-table');
			this.$dropdown = this.$table.find('.WikiaDropdown');
			this.$submit = this.$table.find('input[type="submit"]');
			this.$submit.on('click.RecentChangesDropdown', $.proxy(this.saveFilters, this));

			this.dropdown = new Wikia.MultiSelectDropdown(this.$dropdown);
			this.dropdown.on('change', $.proxy(this.onChange, this));
			
		},
		
		saveFilters: function(event) {
			var self = this;

			event.preventDefault();

			$.nirvana.sendRequest({
				controller: 'RecentChangesController',
				method: 'saveFilters',
				data: {
					filters: self.dropdown.getSelectedValues()
				},
				type: 'POST',
				format: 'json',
				callback: function(data) {
					window.location.reload();
				}
			});
		}
	};

	RecentChanges.init();
});
