$(function() {
	//Highlight first formblock
	$(".highlightform").find(".formblock:first").addClass("selected");
	
	//When any "highlightform" element receives focus
	$(".highlightform *").focus(function() {
		//If not already "selected"
		if (!$(this).closest(".formblock").hasClass(".selected")) {
			//Define elements
			selected = $(this).closest(".highlightform").find(".formblock.selected");
			newselected = $(this).closest(".formblock");
			highlight = $(this).closest("form").find(".formhighlight");
			
			//Size and position the animated highlight element
			highlight.attr("top", selected.offset().top).height(selected.outerHeight()).width(selected.width()).show();
			
			//Remove "selected" class
			selected.removeClass("selected");

			//Move highlight
			highlight.animate({
				height: $(this).closest(".formblock").outerHeight(),
				width: $(this).closest(".formblock").width(),
				top: $(this).closest(".formblock").position().top
			}, function() {
				//Set new "selected"
				newselected.addClass("selected");
				
				//Hide animated highlight element
				highlight.hide();
			});
		}
	});
});
