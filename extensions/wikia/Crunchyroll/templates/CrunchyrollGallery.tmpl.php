<div class="wikia-gallery wikia-gallery-position-center wikia-gallery-spacing-medium wikia-gallery-border-small wikia-gallery-captions-left wikia-gallery-caption-size-medium"><?	$counter = 0;
		$first = 0;
		foreach( $items as $item ){
			if ( ( $counter % $collumns ) == 0 ){
				?><div class="wikia-gallery-row"><?
			}
			?><span class="wikia-gallery-item" style="width:187px; "><div class="thumb" style="height:104px;"><div class="gallery-image-wrapper accent" id="x2222222222222" style="position: relative; height:104px; width:185px;"><p style="margin:0px; height:104px; width:185px;overflow: hidden; display: block"><a class="image link-internal"  href="<?=$item['link']; ?>" title="<?=$item['title']; ?>"><img style="width: 185px; height:104px" src="<?=$item['thumbnail']; ?>" title="<?=$item['title']; ?>"></a></p></div></div><div class="lightbox-caption" style="width:185px;"><?=$item['title']; ?></div></span><?
			
			$counter++;
			if ( ( ( $counter % $collumns ) == 0 ) || ( count( $items ) == ( $counter ) ) ){
				?></div><?
			}
		}
?></div>