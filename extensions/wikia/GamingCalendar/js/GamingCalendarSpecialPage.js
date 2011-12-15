var EditGamingCalendarEntries = {

	init: function() {
		$("#EditGamingCalendarEntries")
			.find("ul").sortable({
				axis: "y",
				handle: ".drag",
				opacity: 0.8,
				stop: EditGamingCalendarEntries.renumber
			}).end()
			.find(".drag").live("mousedown", function(event) {
				event.preventDefault();
			}).end()
			.find(".trash").live("click", EditGamingCalendarEntries.remove).end()
			.find(".add-new a").click(EditGamingCalendarEntries.addNew).end()
			.find(".create").click(EditGamingCalendarEntries.onSave);
		if ($("#EditGamingCalendarEntries").closest(".modalWrapper")) {
			// Presented in modal. Do specific modal stuff
			$("#EditGamingCalendarEntries").find(".cancel").click(function(event) {
				event.preventDefault();
				$(this).closest(".modalWrapper").closeModal();
			});
		}
	},

	addNew: function(event) {
		event.preventDefault();
		$("#EditGamingCalendarEntries .new-item").clone().removeClass("new-item").appendTo("#EditGamingCalendarEntries ul");
		EditGamingCalendarEntries.renumber();
	},

	remove: function() {
		$(this).closest("li").slideUp("fast", function() {
			$(this).remove();
			EditGamingCalendarEntries.renumber();
		});
	},

	renumber: function() {
		$("#EditGamingCalendarEntries li:not('.new-item') label.order").each(function(i) {
			$(this).text("#" + (i + 1));
		});
		$("#EditGamingCalendarEntries input.correct").each(function(i) {
			$(this).val(i);
		});
	},

	onSave: function(event) {
		event.preventDefault();

		// track number of titles
		var titleCount = 0;
		$("#EditGamingCalendarEntries li:not('.new-item') input[type='text']").each(function() {
			if ($(this).val().length > 0) {
				titleCount++;
			}
		});
		EditGamingCalendarEntries.track('/titlecount/' + titleCount);

		if ($("#EditGamingCalendarEntries").data('calendarentriesdate')) {
			// editing existing Calendar Entries
			$.post('/wikia.php?controller=GamingCalendarSpecialPage&method=updateCalendarEntriesForDate&format=json', $("#EditGamingCalendarEntries").find("form").serialize(), function(data) {
				if (data.res.success) {
					document.location = data.res.url;
				} else if (data.error) {
					$("#EditGamingCalendarEntries").find(".errorbox").remove().end().prepend(data.error);
				}
			});
		}
	},

	track: function(fakeUrl) {
		window.jQuery.tracker.byStr('gc' + fakeUrl, true);
	}
};

$(function() {
	if (wgAction != "edit" && wgAction != "submit"){
		// only init on special page
		EditGamingCalendarEntries.init();
	}
});