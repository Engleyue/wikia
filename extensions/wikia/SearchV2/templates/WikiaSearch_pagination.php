<fieldset>
<?php if( $windowFirstPage > 1 ): ?>
	<a href="<?= $pageTitle->getFullUrl( array( 'query' => $query, 'start' => (($windowFirstPage-2)*$resultsPerPage), 'crossWikia' => ( $crossWikia ? '1' : '0' ) ) ); ?>">...</a>
<?php endif; ?>
<?php for( $i = $windowFirstPage; $i <= $windowLastPage; $i++ ): ?>

	<?php if($i == $currentPage): ?>
		<?=$i;?>&nbsp;
	<?php else: ?>
		<a href="<?= $pageTitle->getFullUrl( array( 'query' => $query, 'start' => (($i-1)*$resultsPerPage), 'crossWikia' => ( $crossWikia ? '1' : '0' ) ) ); ?>"><?=$i;?></a>&nbsp;
	<?php endif;?>
<?php endfor; ?>

<?php if( $windowLastPage < $pagesNum ): ?>
<a href="<?= $pageTitle->getFullUrl( array( 'query' => $query, 'start' => (($i)*$resultsPerPage), 'crossWikia' => ( $crossWikia ? '1' : '0' ) ) ); ?>">...</a>
<?php endif; ?>
</fieldset>