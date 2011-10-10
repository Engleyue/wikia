<form id="WikiaSearch" class="WikiaSearch" action="index.php?title=Special:Search" method="get">
	<input type="text" name="search" placeholder="<?= $placeholder ?>" autocomplete="off" accesskey="f" value="<?= $searchterm ?>">
	<input type="hidden" name="fulltext" value="<?= $fulltext ?>">
	<input type="submit">
	<button class="secondary"><img src="<?= $wgBlankImgUrl ?>" class="sprite search" height="17" width="21"></button>
</form>
<?php
if ($wgTitle->isSpecial('Search')) {
	if( $isCrossWikiaSearch ) {
		echo Xml::element('h1', array(), wfMsg('oasis-search-results-from-all-wikis'));
	}
	else {
		echo Xml::element('h1', array(), wfMsg('oasis-search-results-from', $wgSitename));
	}
}
?>