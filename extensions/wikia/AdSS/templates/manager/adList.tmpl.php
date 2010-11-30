<fieldset class="panel">
<p><a class="wikia-button" href="<?php echo $buyUrl; ?>"><?php echo wfMsgHtml('adss-buy-another'); ?></a></p>
<?php echo $adList; ?>
</fieldset>

<script type="text/javascript">/*<![CDATA[*/
$("a.close").click( function(e) {
	e.preventDefault();
	if( confirm( 'Are you sure you want to delete this ad?' ) ) {
		var id = $(this).parent().attr("id");
		$.getJSON( wgScript, {
			'action': 'ajax',
			'rs': 'AdSS_ManagerController::closeAdAjax',
			'rsargs[0]': id
			}, function( response ) {
				if( response.result == "success" ) {
					var cur = $('span#'+response.id);
					cur.closest("tr").remove();
				} else {
					alert(response.respmsg);
				}
			}
		);
	}
} );

</script>

