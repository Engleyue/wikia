//* IMAGE *//

window.PageLayoutBuilder = window.PageLayoutBuilder || {};

PageLayoutBuilder.inputEnter = function (e) {
	$(e.target).val("").removeClass("plb-empty-input");
}

PageLayoutBuilder.inputExit = function (e) {
	var value = $(e.target).attr('data-instructions');
	if($(e.target).val() == "") {
		$(e.target).val(value).addClass("plb-empty-input");	
	}
}

$(function() {
	$('.plb-empty-input').focus(PageLayoutBuilder.inputEnter)
						 .blur(PageLayoutBuilder.inputExit);
	
	$("#plbForm,#editform").submit(function() {
		$("input.plb-empty-input, textarea.plb-empty-input ").val("");
	});
	
	WikiaPhotoGalleryView.initGalleries();
});

PageLayoutBuilder.uploadImage = function (size, name) {
	$.loadYUI( function() {
		importStylesheetURI( wgExtensionsPath+ '/wikia/WikiaMiniUpload/css/WMU.css?'+wgStyleVersion );
		$.getScript(wgExtensionsPath+ '/wikia/WikiaMiniUpload/js/WMU.js?'+wgStyleVersion, function() {
			$("body").unbind('imageUploadSummary').bind( 'imageUploadSummary', PageLayoutBuilder.WMU_insertImage);
			WMU_show();
			
			WMU_Event_OnLoadDetails = function() {
				$('#ImageColumnRow,#ImageSizeRow,#ImageWidthRow,#ImageLayoutRow').hide();
			};			

			PageLayoutBuilder.WMU_insertImage = function(event,body) {
				$.ajax({
				  url: wgScript + '?action=ajax&rs=LayoutWidgetImage::getUrlImageAjax&name=' + $("#ImageUploadFileName").val() + "&size=" + size,
				  dataType: "json",
				  method: "get",
				  success: function(data) {
					if(data.status == "ok") {
						$("#imageboxdiv_" + name).css("width", (parseInt( data.size.width ) + 4) + "px");
						$("#imagediv_" + name).css("width", data.size.width + "px")
						.css("line-height", data.size.height + "px")
						.css('background-image', 'url("' +  data.url +'")');
						$("#plb_" + name).val( $("#ImageUploadFileName").val()  + " | " + $("#ImageUploadCaption").val() );
						$("#thumbcaption").val($("#ImageUploadCaption").val());
					}
					WMU_close();
				  }
				});
				return false;
			}
		});
	});
	return false;
}
//* END IMAGE *//

//* MULTILINE *//

PageLayoutBuilder.messages = {},

PageLayoutBuilder.setupTextarea = function(node) {
	// add textarea toolbar node
	var toolbar = $('<div>').
		addClass('plb-form-template-toolbar').
		insertBefore(node).
		css('top', parseInt($(node).position().top ) + "px" );
	
	// show toolbar on focus / hide on blur
	var toolbarHideTimeout = false;
		
	// toolbar buttons
	var toolbarButtons = [
		{
			image: 'bold',
			tagOpen: "'''",
			tagClose: "'''",
			title: this.messages['bold_tip']
		},
		{
			image: 'italic',
			tagOpen: "''",
			tagClose: "''",
			title: this.messages['italic_tip']
		},
		{
			image: 'link',
			tagOpen: "[[",
			tagClose: "]]",
			title: this.messages['link_tip']
		}
	];

	// handle clicks on toolbar buttons
	var self = this;
	var toolbarButtonOnClick = function(tagOpen, tagClose, sampleText) {
		self.insertTags(node[0], tagOpen, tagClose, sampleText);

		// don't hide toolbar and bring focus back
		clearTimeout(toolbarHideTimeout);
		node.focus();
	};

	// add buttons
	for (var i=0; i < toolbarButtons.length; i++) {
		var data = toolbarButtons[i];

		$('<img />').
			appendTo(toolbar).
			attr({
				alt: '',
				"class": 'toolbar-' + data.image,
				height: 24,
				src: window.wgBlankImgUrl,
				tagClose: data.tagClose,
				tagOpen: data.tagOpen,
				title: data.title,
				width: 24
			}).
			click(function() {
				var button = $(this);
				toolbarButtonOnClick(button.attr('tagOpen'), button.attr('tagClose'), button.attr('title'));
			});
	}
}

PageLayoutBuilder.insertTags = function(txtarea, tagOpen, tagClose, sampleText) {
	var selText, isSample = false;

	// get pure DOM node
	txtarea = $(txtarea)[0];

	if (document.selection  && document.selection.createRange) { // IE/Opera
		//save window scroll position
		if (document.documentElement && document.documentElement.scrollTop)
			var winScroll = document.documentElement.scrollTop
		else if (document.body)
			var winScroll = document.body.scrollTop;
		//get current selection
		txtarea.focus();
		var range = document.selection.createRange();
		selText = range.text;
		//insert tags
		checkSelectedText();
		range.text = tagOpen + selText + tagClose;
		//mark sample text as selected
		if (isSample && range.moveStart) {
			if (window.opera)
				tagClose = tagClose.replace(/\n/g,'');
			range.moveStart('character', - tagClose.length - selText.length);
			range.moveEnd('character', - tagClose.length);
		}
		range.select();
		//restore window scroll position
		if (document.documentElement && document.documentElement.scrollTop)
			document.documentElement.scrollTop = winScroll
		else if (document.body)
			document.body.scrollTop = winScroll;

	} else if (txtarea.selectionStart || txtarea.selectionStart == '0') { // Mozilla

		//save textarea scroll position
		var textScroll = txtarea.scrollTop;
		//get current selection
		txtarea.focus();
		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		selText = txtarea.value.substring(startPos, endPos);
		//insert tags
		checkSelectedText();
		txtarea.value = txtarea.value.substring(0, startPos)
			+ tagOpen + selText + tagClose
			+ txtarea.value.substring(endPos, txtarea.value.length);
		//set new selection
		if (isSample) {
			txtarea.selectionStart = startPos + tagOpen.length;
			txtarea.selectionEnd = startPos + tagOpen.length + selText.length;
		} else {
			txtarea.selectionStart = startPos + tagOpen.length + selText.length + tagClose.length;
			txtarea.selectionEnd = txtarea.selectionStart;
		}
		//restore textarea scroll position
		txtarea.scrollTop = textScroll;
	}

	function checkSelectedText(){
		if (!selText) {
			selText = sampleText;
			isSample = true;
		} else if (selText.charAt(selText.length - 1) == ' ') { //exclude ending space char
			selText = selText.substring(0, selText.length - 1);
			tagClose += ' '
		}
	}
}


$(function() {
	var elements = $(".plb-mlinput-textarea");
	for( var i = 0; i < elements.length; i ++ ) {
		PageLayoutBuilder.setupTextarea($(elements[i]));
	}
});

//* END MULTILINE *//


//* select *//


PageLayoutBuilder.initSelect = function() {
	var select = $(".plb-empty-select");
	select.find("option:first").attr("class", "plb-empty-select");
	select.unbind().change(function(e) {
	    var element = $(e.target);
	    if( element.val() == "") {
	    	element.attr("class", "plb-empty-select");
	    } else {
	    	element.attr("class", "");
	    }
	});
}

$(function() {
	PageLayoutBuilder.initSelect();
});
// * end select * //

//* gallery *//
PageLayoutBuilder.uploadGallery = function(element_id) {
	WikiaPhotoGalleryView.loadEditorJS(function() {
		PageLayoutBuilder.showGalleryForPLB(element_id);
	});
}

PageLayoutBuilder.showGalleryForPLB = function(element_id) {
	var text = $('#plb_' + element_id ).val();
	
	$.ajax({
		url: wgScript + '?action=ajax&rs=LayoutWidgetGallery::getGalleryDataAjax',
		data: {
			element_id : element_id,
			text :text,
			plb_id: $("#wpPlbId").val(),
		},
		type: "POST",
		dataType: "json",
		success: function(data) {
			WikiaPhotoGallery.showEditor({
				from: 'view',
				element_id: element_id,
				gallery: data,
				target: $("body") //.closest('.wikia-gallery')
			}); 
	}});
} 
$(function() {
	$('body').bind('beforeSaveGalleryData', function(e, element_id, gallery, modal) {
		var data = { 
				plb_id: $("#wpPlbId").val(), 
				gallery: WikiaPhotoGallery.JSONtoWikiTextInner(gallery), 
				element_id:element_id};
		
		$().log(data, 'beforeSaveGalleryData' );
		$('#plb_' + element_id).val(WikiaPhotoGallery.JSONtoWikiTextInner(gallery) )
		$.ajax({
			url: wgScript + '?action=ajax&rs=LayoutWidgetGallery::renderForFormAjax',
			data: data,
			type: "POST",
			success: function(data) {
				gallery_element = $('#imageboxmaindiv_' + element_id);
				if(gallery_element.length > 0) {
					$('#instructionsdiv_' + element_id).replaceWith(' ');
				} else {
					gallery_element = $('#gallery-plb_' + element_id);
				}
				gallery_element.replaceWith(data);
				WikiaPhotoGalleryView.initGalleries();
				modal.hideModal();
			}
		});
		return false;
	});
	
	$('body').bind('beforeGalleryShow', function(e, button) {
		WikiaPhotoGalleryView.loadEditorJS(function() {
			var element_id = parseInt($(button).closest('.wikia-gallery').attr('id').replace('gallery-plb_', ''));
			PageLayoutBuilder.showGalleryForPLB(element_id);
		});
		return false;
	});
	
});


//* end gallery * //


