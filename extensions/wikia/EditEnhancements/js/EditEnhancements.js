$(function() {
	EditEnhancements.init();
});

var EditEnhancements = {

	// Variables used in various EditEnhancements functions
	settings: {
		loopCount: 0  // Counter for loop() function
	},


	/**
	 *  Init funciton is run once ondomready
	 */
	init: function() {	
		/* Repostion elements in layout */
		$('<div id="edit_enhancements_toolbar"><ul>').insertBefore("#editpage-copywarn");
		
		/* List of elements to reposition */
		var elements = ["#wpSave", "#wpPreview"];
		// NS_3 is User_Talk and shouldn't have the summary field and label repositioned.
		if (wgNamespaceNumber != 3) {
			elements.unshift("#wpSummaryLabel", "#wpSummary");
		}
		
		$(elements.toString()).each(function() {
			$(this).wrap("<li>").parent().appendTo("#edit_enhancements_toolbar ul");
		});
		// Checkboxes are done outside of each loop because it must appear last, but isn't last in original layout.
		$(".editCheckboxes").removeClass().wrap('<li class="editCheckboxes">').parent().appendTo("#edit_enhancements_toolbar ul");
		
		// Bind event handlers
		$(window).bind({
			'resize': EditEnhancements.calculate,
			'rteready': EditEnhancements.calculate,
			'load': EditEnhancements.loop
		});
	
		// Enables jump to top of the editor after editor has been loaded
		if (skin == 'oasis') {
			EditEnhancements.jumpToEditor();
		}
	},
		

	/**
	 *  Adjusts the height of the editor so that the Save button is visible without scrolling.
	 */
	calculate: function() {	
		/* Adjust editor height */
		// Declare variables
		var toolbar; 				// Editor toolbar with formatting controls
		var textbox; 				// Editing area
		var textboxOffset; 	// jQuery offset calculation of editing area
		var viewportHeight;	// Height of the browser viewport
		var heightElements; // Array of elements that add height to the interface
		var height;					// The height sum of the elements that add height to the interface
		var targetHeight;		// Height to set the editing area

		
		// Set variables
		toolbar = $('#cke_contents_wpTextbox1');
		
		textbox = false; 
		if ($('#wpTextbox1___Frame').exists()) { 
			// FCK-editor
			textbox = $('#wpTextbox1___Frame');
		} else if ( $('#cke_contents_wpTextbox1').exists()) { 
			// CKeditor
			textbox = $('#cke_contents_wpTextbox1');
		} else { 
			// MediaWiki editor
			textbox = $('#wpTextbox1');
		}
		
		textboxOffset = textbox.offset();
		// textbox not fully initialized (RT #79980)
		if (!textboxOffset) {
			return;
		}
		
		viewportHeight = $(window).height();
		//TODO: FIGURE OUT IF WE CARE ABOUT OPERA 9.5
		viewportHeight = window.innerHeight ? window.innerHeight : $(window).height(); // Opera 9.5
		
		// Create array of elements that add height to the interface
		// TODO: ADD BLOG ELEMENTS: '#blogInfo' AND '.blogPostForm'
		heightElements = ['#toolbar', '.cke_top', '#csMainContainer', '#edit_enhancements_toolbar'];
		height = 0;
		$.each(heightElements, function(index, item) {
			var node = $(item);
			if (node.exists()) {
				height += node.outerHeight();
			}
		});

		// Calculate target height by subtracting height of elements in heightElements array from the viewport height
		targetHeight = viewportHeight - height;
		// Height should be at least 200 and at most 800.
		targetHeight = Math.min(Math.max(targetHeight, 200), 800);

		// Set the textbox height
		textbox.height(targetHeight - 25); // 25 accounts for some slop.. i know, it sucks.


		/* Adjust summary field width */
		/*
		// Declare variables
		var items;							// Items within the toolbar
		var summaryBox;					// The summary text field
		var itemsWidth;					// Width of items in the toolbar
		var targetSummaryWidth; // New width to set summary field

		items = toolbar.find('li');
		summaryBox = $('#wpSummaryEnhanced');
		itemsWidth = 0;

		items.each(function() {
			var item = $(this);

			// on Oasis "minor edit" checkbox is below summary field - skip minor checkbox
			if (window.skin == 'oasis' && item.hasClass('minor')) {
				return false;
			}

			itemsWidth += item.innerWidth() + 5;
		});
		itemsWidth -= summaryBox.innerWidth();

		targetSummaryWidth =  toolbar.innerWidth() - itemsWidth - 20;

		// Width should be at least 200 and at most 500.
		targetSummaryWidth = Math.min(Math.max(targetSummaryWidth, 200), 500);
		// Bernhard: buggy at the summary box width!
		//summaryBox.width(targetSummaryWidth);
		*/
		
		// Inform RTE that its editarea has been resized (RT#36994)
		if (typeof window.RTE !='undefined' && typeof window.RTE.instance == 'object') {
			RTE.instance.fire('resize');
		}
	},
	
	
	/**
	 *  Runs the EditEnhancements.calculate() function 3 times.
	 *  Late loading of images can cause calculation to not be correct the first time.
	 *  This is called once onload.
	 */
	loop: function() {
		if (EditEnhancements.settings.loopCount != 3) {
			EditEnhancements.calculate();
			EditEnhancements.settings.loopCount++;
			setTimeout(arguments.callee, 500);
		}
	},
	
	/**
	 *  Scrolls page to the editor
	 */
	jumpToEditor: function() {
		if ($('.cke_editor').exists()) {
			// CKEditor enabled
			$('html, body').animate({ scrollTop: $('.cke_editor').eq(0).offset().top }, 1);
		} else if ($('#toolbar').exists() && $('#toolbar').is(':visible') == true) {
			// MediaWiki editor enabled
			$('html, body').animate({ scrollTop: $('#toolbar').eq(0).offset().top }, 1);
		} else {
			// Editor not found yet. Wait and try again.
			setTimeout(arguments.callee, 100);
		}
	}

};