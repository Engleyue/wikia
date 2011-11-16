<div class="item">
	<a class="video-thumbnail video-play" href="<?=$video['fullUrl'];?>" data-ref="<?=$video['prefixedUrl'];?>" data-external="<?=$video['external'];?>" >
		<?
		if ( !empty( $video['duration'] ) ) {
			$mins = floor ($video['duration'] / 60);
			$secs = $video['duration'] % 60;
			$duration = $mins.':'.( ( $secs < 10 ) ? '0'.$secs : $secs );
		} else {
			$duration = 0;
		}
		if( !empty( $duration ) ){  ?><div class="timer"><?=$duration;?></div><? }
		if( !empty( $video['isNew'] ) ){  ?><div class="new"><?=wfMsg('related-videos-video-is-new');?><div  class="newRibbon" ></div></div><? } ?>
		<div class="playButton"></div>
		<img data-src="<?=$video['thumbnailData']['thumb'];?>" src="<?=( $preloaded ) ? $video['thumbnailData']['thumb'] : wfBlankImgUrl();?>" style="height:<?=$video['thumbnailData']['height'];?>px; width:<?=$video['thumbnailData']['width'];?>px;" />
	</a>
	<div class="description">
		<?=$video['title'];?>
	</div>
	<div class="info">
		<?
			$owner = $video['owner'];
			if ( !empty( $owner ) ){
				echo wfMsg('related-videos-added-by') . " ";
				if ( empty( $video['external'] ) || isset( $video['externalByUser'] ) ){
					$ownerUrl = $video['ownerUrl'];
					if ( !empty( $ownerUrl ) ) {
						?><a href="<?=$video['ownerUrl'];?>"><?=$video['owner'];?></a><?
					} else {
						echo $video['owner'];
					}
				} else {
					?><a href="<?=F::app()->wg->wikiaVideoCategoryPath;?>" /><?=wfMsg('related-videos-repo-name');?></a><?
				}
			}
		?>
	</div>
	<div class="options">
		<img src="<?=wfBlankImgUrl();?>" /><a class="remove" href="#"><?=wfMsg('related-videos-remove'); ?></a>
	</div>
</div>