<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	var baseurl = wgScript + "?action=ajax&rs=LookupContribsAjax::axData&lookupUser=1";
	var username = '<?=$username?>';
	
	if ( !username ) {
		return;
	}
	
	var ajaxRequests = [];
	
	var oTable = $('#lc-table').dataTable( {
		oLanguage: {
			sLengthMenu: "<?=wfMsg('table_pager_limit', '_MENU_');?>",
			sZeroRecords: "<?=wfMsg('table_pager_empty');?>",
			sEmptyTable: "<?=wfMsg('table_pager_empty');?>",
			sInfo: "<?=wfMsgExt('lookupuser-table-recordspager',  array('parseinline'), '_START_', '_END_', '_TOTAL_');?>",
			sInfoEmpty: "<?=wfMsgExt('lookupuser-table-recordspager', array('parseinline'), '0', '0', '0');?>",
			sInfoFiltered: "",
			sSearch: "<?=wfMsg('search')?>",
			sProcessing: "<img src='" + stylepath + "/common/images/ajax.gif' /> <?=wfMsg('livepreview-loading')?>",
			oPaginate : {
				sFirst: "<?=wfMsg('table_pager_first')?>",
				sPrevious: "<?=wfMsg('table_pager_prev')?>",
				sNext: "<?=wfMsg('table_pager_next')?>",
				sLast: "<?=wfMsg('table_pager_last')?>"
			}
		},
		aaSorting : [],
		iDisplayLength : 25,
		aLengthMenu: [[25, 50, 100, 250], [25, 50, 100, 250]],
		sDom: '<"dttoolbar"><"top"flip>rt<"bottom"p><"clear">',
		aoColumns: [
			{ sName: "id" },
			{ sName: "title" },
			{ sName: "url" },
			{ sName: "lastedit" },
			{ sName: "edits" },
			{ sName: "userrights" },
			{ sName: "blocked" }
		],
		aoColumnDefs: [ 
			{ bVisible: false, aTargets: [0], bSortable: false },
			{ bVisible: true,  aTargets: [1], bSortable: false },
			{
				fnRender: function ( oObj ) {
					var row = '<span class="lc-row"><a href="' + oObj.aData[2] + '">' + oObj.aData[2] + '</a></span>';
					row += '&nbsp;(<a href="' + oObj.aData[2] + 'index.php?title=Special:Contributions/' + encodeURIComponent(username) + '">';
					row += '<?=wfMsg('lookupuser-table-contribs')?>';
					row += '</a>)</span>';
					return row;
				},
				aTargets: [2],
				bSortable: false
			},
			{ bVisible: true, aTargets: [3], bSortable: false },
			{ bVisible: true, aTargets: [4], bSortable: false },
			{ bVisible: true, aTargets: [5], bSortable: false },
			{ bVisible: true, aTargets: [6], bSortable: false }
		],
		bProcessing: true,
		bServerSide: true,
		bFilter: false,
		sPaginationType: "full_numbers",
		sAjaxSource: baseurl,
		fnServerData: function ( sSource, aoData, fnCallback ) {
			var limit = 25;
			var offset = 0;
			var groups = 0;
			var loop = 1;
			var order = '';
			
			var sortingCols = 0;
			var iColumns	= 0;
			
			for ( i in aoData ) {
				switch ( aoData[i].name ) {
					case 'iDisplayLength'	: limit = aoData[i].value; break;
					case 'iDisplayStart'	: offset = aoData[i].value; break;
					case 'sEcho'			: loop = aoData[i].value; break;
					case 'sColumns'			: columns = aoData[i].value.split(","); break;
					case 'iColumns'			: iColumns = aoData[i].value; break;
					case 'iSortingCols'		: sortingCols = aoData[i].value; break;
				}
			}
			
			$.ajax({
				dataType: 'json', 
				type: "POST", 
				url: sSource, 
				data: [
					{ name: 'username', value: ( $('#lu_name').exists() ) ? $('#lu_name').val() : '' },
					{ name: 'limit', value: limit },
					{ name: 'offset', value: offset },
					{ name: 'loop', value: loop },
					{ name: 'numOrder', value: sortingCols },
					{ name: 'order', value: order }
				], 
				success: function(json) {
					fnCallback(json);
					
					$('.user-groups-placeholder').each(function(){
						var self = $(this);
						var wikiId = self.find('input.wikiId').val();
						var url = self.find('input.wikiUrl').val();
						var userName = self.find('input.name').val();
						
						try {
							var ajaxRequest = $.ajax({
								dataType: 'json', 
								type: "POST", 
								data: 'url=' + url + '&username=' + username + '&id=' + wikiId,
								url: wgScript + "?action=ajax&rs=LookupUserPage::requestApiAboutUser", 
								success: function(res) {
									var blockedInfo = $('.user-blocked-placeholder-' + wikiId),
										editcountInfo = $('.user-edits-placeholder-' + wikiId);
									
									if( res.success === true && typeof(res.data) !== 'undefined') {
										self.hide();
										
										//user's group data
										if( res.data.groups === false ) {
											self.parent().append('-');
										} else {
											self.parent().append( res.data.groups.join(', ') );
										}
										
										//user's block data
										blockedInfo.hide();
										switch(res.data.blocked) {
											case true: blockedInfo.parent().append('Y'); break;
											case false: blockedInfo.parent().append('N'); break;
										}
										
										//user's editcount data
										editcountInfo.hide();
										editcountInfo.parent().append(res.data.editcount);
									} else {
										self.hide();
										self.parent().append('-');
										
										blockedInfo.hide();
										blockedInfo.parent().append('-');
									}
								}
							});
							
							ajaxRequests.push(ajaxRequest);
						} catch(e) {
							$().log('Exception');
							$().log(e);
						}
					});
					
					$('.paginate_button').click(function(){
						for(i in ajaxRequests) {
							ajaxRequests[i].abort();
						}
						ajaxRequests = [];
					});
				}
			});
		}
	});
});
</script>

<input id="lu_name" type="hidden" value="<?= $username; ?>" />
<table cellpadding="0" cellspacing="0" border="0" class="TablePager" id="lc-table">
	<thead>
		<tr>
			<th width="2%">#</th>
			<th width="25%"><?= wfMsg('lookupuser-table-title') ?></th>
			<th width="20%"><?= wfMsg('lookupuser-table-url') ?></th>
			<th width="20%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-lastedited') ?></th>
			<th width="15%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-editcount') ?></th>
			<th width="15%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-userrights') ?></th>
			<th width="3%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-blocked') ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="6" class="dataTables_empty"><?= wfMsg('livepreview-loading'); ?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th width="2%">#</th>
			<th width="25%"><?= wfMsg('lookupuser-table-title') ?></th>
			<th width="20%"><?= wfMsg('lookupuser-table-url') ?></th>
			<th width="20%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-lastedited') ?></th>
			<th width="15%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-editcount') ?></th>
			<th width="15%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-userrights') ?></th>
			<th width="3%" style="white-space:nowrap"><?= wfMsg('lookupuser-table-blocked') ?></th>
		</tr>
	</tfoot>
</table>

<ul>
<?php if( $isUsernameGloballyBlocked ) { ?>
	<li><?= wfMsg('lookupuser-username-blocked-globally') ?></li>
<?php } else { ?>
	<li><?= wfMsg('lookupuser-username-not-blocked-globally') ?></li>
<?php }?>
</ul>