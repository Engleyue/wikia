var SponsorshipDashboardGroupEditor = {

	init: function(){

		$( '#groupReportList' ).change(
			SponsorshipDashboardGroupEditor.addToGroupList
		);

		$( '#groupUserList' ).change(
			SponsorshipDashboardGroupEditor.addUserToGroupList
		);

		$( 'a.delete' ).click( SponsorshipDashboardGroupEditor.removeReport );
		$( 'a.sd-save' ).click( SponsorshipDashboardGroupEditor.submit );
		$('#groupReportsDisplay').bind('click', SponsorshipDashboardGroupEditor.checkForEvents );
		$('#groupUsersDisplay').bind('click', SponsorshipDashboardGroupEditor.checkForEvents );
		SponsorshipDashboardGroupEditor.refreshElementList();
	},
	addToGroupList : function(){

		var elementValue = $( '#groupReportList' ).val();
		var currentArray = $( '#groupReportsInput' ).val().split( ',' );
		if ( elementValue > 0 ){
			if ( $.inArray( elementValue, currentArray ) == -1 ){
				currentArray.push( elementValue + '|' );
				$( '#groupReportsInput' ).val( currentArray.join(',') );
				$( '#groupReports' ).append( SponsorshipDashboardGroupEditor.reportItemText( elementValue ) );
			}
		}
	},
	addUserToGroupList : function(){
		
		var elementValue = $( '#groupUserList' ).val();
		var currentArray = $( '#groupUsersInput' ).val().split( ',' );
		if ( elementValue > 0 ){
			if ( $.inArray( elementValue, currentArray ) == -1 ){
				currentArray.push( elementValue + '|' );
				$( '#groupUsersInput' ).val( currentArray.join(',') );
				$( '#groupUsers' ).append( SponsorshipDashboardGroupEditor.userItemText( elementValue ) );
			}
		}
	},
	refreshElementList : function(){
		
		var currentArrayTmp = $( '#groupReportsInput' ).val().split( ',' );
		
		for ( var i = 0; i < currentArrayTmp.length; i = i + 1){

			var value = currentArrayTmp[i].replace( '|', '' );
			if ( value > 0 ){
				$( '#groupReports' ).append( SponsorshipDashboardGroupEditor.reportItemText( value ) );
				$( '#groupReportList option[value=' + value + ']' ).hide();
			}
		}

		var currentUserArrayTmp = $( '#groupUsersInput' ).val().split( ',' );

		for ( var i = 0; i < currentUserArrayTmp.length; i = i + 1){

			var value = currentUserArrayTmp[i].replace( '|', '' );
			if ( value > 0 ){
				$( '#groupUsers' ).append( SponsorshipDashboardGroupEditor.userItemText( value ) );
				$( '#groupUserList option[value=' + value + ']' ).hide();
			}
		}
	},
	reportItemText : function( value ){

		var elementText = '<li>';
		elementText = elementText + '<a data-id="' + value + '" data-type="report" class="delete wikia-button secondary"><img src="http://images1.wikia.nocookie.net/__cb33534/common/skins/common/blank.gif" class="sprite trash"></a> ';
		elementText = elementText + $( "#groupReportList option[value=" + value + "]" ).html();
		elementText = elementText + '</li>';
		$( '#groupReportList option[value=' + value + ']' ).hide();

		return elementText;
	},
	userItemText : function( value ){

		var elementText = '<li>';
		elementText = elementText + '<a data-id="' + value + '" data-type="user" class="delete wikia-button secondary"><img src="http://images1.wikia.nocookie.net/__cb33534/common/skins/common/blank.gif" class="sprite trash"></a> ';
		elementText = elementText + $( "#groupUserList option[value=" + value + "]" ).html();
		elementText = elementText + '</li>';
		$( '#groupUserList option[value=' + value + ']' ).hide();

		return elementText;
	},
	removeReport : function( elementId ){

		$( '#groupReportsInput' ).val( $( '#groupReportsInput' ).val().replace( ',' + elementId + '|', '' ) );
		$( '#groupReportList option[value=' + elementId + ']' ).show();
		$( '#groupReports a[data-id=' + elementId + ']' ).parent('li').remove();

	},
	removeUser : function( elementId ){

		$( '#groupUsersInput' ).val( $( '#groupUsersInput' ).val().replace( ',' + elementId + '|', '' ) );
		$( '#groupUserList option[value=' + elementId + ']' ).show();
		$( '#groupUsers a[data-id=' + elementId + ']' ).parent('li').remove();

	},

	checkForEvents : function( ev ){

		var node = $(ev.target).parent('a');
		$().log( node, node );
		if ( node.hasClass('delete') && node.attr( 'data-id' ) > 0 && node.attr( 'data-type' ) == 'report' ){
			SponsorshipDashboardGroupEditor.removeReport( node.attr( 'data-id' ) );
		}
		if ( node.hasClass('delete') && node.attr( 'data-id' ) > 0 && node.attr( 'data-type' ) == 'user' ){
			SponsorshipDashboardGroupEditor.removeUser( node.attr( 'data-id' ) );
		}
	}
};

//on content ready
wgAfterContentAndJS.push( SponsorshipDashboardGroupEditor.init );