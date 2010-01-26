var CreatePage = {};
var CreatePageEnabled = false;

CreatePage.pageLayout = null;

CreatePage.checkTitle = function( title, enterWasHit ) {
	$.getJSON(wgScript,
			{
				'action':'ajax', 
				'rs':'wfCreatePageAjaxCheckTitle',
				'title':title
			},
			function(response) {
				if(response.result == 'ok') {
					if( enterWasHit ) {
						CreatePage.track('enter/' + ( CreatePage.pageLayout == 'format' ? 'standardlayout' : 'blankpage'));
					}
					else {
						CreatePage.track('create/' + ( CreatePage.pageLayout == 'format' ? 'standardlayout' : 'blankpage'));
					}
					location.href = wgScript + '?title=' + title + '&action=edit' + ( CreatePage.pageLayout == 'format' ? '&useFormat=1' : '');
				}
				else {
					CreatePage.displayError(response.msg);
				}
			}
		);
};

CreatePage.openDialog = function(e, titleText) {
	e.preventDefault();
	if( false == CreatePageEnabled ) {
		CreatePageEnabled = true;
		$().getModal(
			wgScript + '?action=ajax&rs=wfCreatePageAjaxGetDialog',
			'#CreatePageDialog', {
					width: 400,
					callback: function() {
						CreatePageEnabled = false;
						CreatePage.track( 'open' );
						if(titleText != null) {
							$('#wpCreatePageDialogTitle').val( titleText );
						}
						$('#wpCreatePageDialogTitle').focus();
					},
				onClose: function() {
					CreatePage.track( 'close' );
				}
			}
		);
	}
};

CreatePage.submitDialog = function( enterWasHit ) {
	CreatePage.checkTitle( $('#wpCreatePageDialogTitle').val(), enterWasHit );
};

CreatePage.displayError = function( errorMsg ) {
	var box = $( '#CreatePageDialogTitleErrorMsg' );	
	box.html( '<span id="createPageErrorMsg">' + errorMsg + '</span>' );
	box.removeClass('hiddenStructure');
};

CreatePage.setPageLayout = function( layout ) {
	CreatePage.pageLayout = layout;
	switch( layout ) {
		case 'format':
			$('#CreatePageDialogFormat').attr( 'checked', 'checked' );
			$('#CreatePageDialogBlankContainer').removeClass( 'chosen' );
			$('#CreatePageDialogFormatContainer').addClass( 'chosen' );
			CreatePage.track('standardlayout');
			break;
		case 'blank':
		default:
			$('#CreatePageDialogBlank').attr( 'checked', 'checked' );
			$('#CreatePageDialogBlankContainer').addClass( 'chosen' );
			$('#CreatePageDialogFormatContainer').removeClass( 'chosen' );
			CreatePage.track('blankpage');
			break;
	}
};

CreatePage.track = function( str ) {
	WET.byStr('CreatePage/' + str);
};

CreatePage.getTitleFromUrl = function( url ) {
	var vars = [], hash;
	var hashes = url.slice(url.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars['title'].replace(/_/g, ' ');
};

$(function() {
	if( WikiaEnableNewCreatepage ) {
		if( $( '#dynamic-links-write-article-icon' ).exists() ) {
			// open dialog on clicking
			$( '#dynamic-links-write-article-icon' ).click( function(e) { CreatePage.openDialog(e, null); });
		}
		if( $( '#dynamic-links-write-article-link' ).exists() ) {
			// open dialog on clicking
			$( '#dynamic-links-write-article-link' ).click( function(e) { CreatePage.openDialog(e, null); });
		}

		$(".new").bind('click', function(e) { CreatePage.openDialog(e, CreatePage.getTitleFromUrl(this.href) ); } );
		$(".createboxButton").bind('click', function(e) {
			var form = $(e.target).parent();
			var field = form.children('.createboxInput');
			CreatePage.openDialog(e, field.val());
			});
	}
});
