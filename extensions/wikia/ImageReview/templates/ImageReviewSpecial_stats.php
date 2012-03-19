<style type="text/css" scoped>
fieldset {
        display: inline;
}

table {
	width: 100%;
}
</style>

<form method="get">
<?php
	$dates = array( 'start', 'end' ); $currentYear = date( 'Y' );
	foreach ( $dates as $prefix ) {
?>
	<fieldset>
		<legend><?= ucfirst( $prefix ) ?> date</legend>
		<select name="<?= $prefix ?>Day">
			<?php for ( $i = 1; $i <= 31; ++$i ) {
				echo Xml::option( $i, $i, $i == date( 'd' ) );
			} ?>
		</select>
		<select name="<?= $prefix ?>Month">
<?php
		global $wgLang;
		for( $i = 1; $i < 13; $i++ )
                       echo Xml::option( $wgLang->getMonthName( $i ), $i, date( 'n' ) == $i );
?>
		</select>
		<select name="<?= $prefix ?>Year">
			<?php for ( $i = 2012; $i <= $currentYear; ++$i ) {
				echo Xml::option( $i, $i, $i == $currentYear );
			} ?>
		</select>
	</fieldset>
<?php	} ?>

	<?= Xml::submitButton( 'Generate stats' ) ?>
</form>

<h2>Summary</h2>

<?= Xml::buildTable( array( $summary ), array( 'class' => 'wikitable' ), $summaryHeaders ); ?>

<h2>Breakdown by user</h2>

<?= Xml::buildTable( $data, array( 'class' => 'wikitable sortable' ), $headers ); ?>
