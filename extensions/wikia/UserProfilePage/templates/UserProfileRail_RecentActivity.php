<section class="UserProfileRailModule_RecentActivity">
	<h2><?= wfMsg( 'userprofilepage-recent-activity-title', $userName, $wikiName ) ;?></h2>
	<?if ( count($activityFeed) ) :?>
		<ul class="activity_feed">
			<? foreach( $activityFeed as $row ) :?>
				<li>
					<?= FeedRenderer::getSprite( $row, wfBlankImgUrl() ) ;?>
					<span><a href="<?= htmlspecialchars( $row['url'] ) ;?>" class="title" rel="nofollow"><?= htmlspecialchars( $row['title'] ) ;?></a></span>
					<span class="time-ago"><?= FeedRenderer::formatTimestamp( $row['timestamp'] );?></span>
				</li>
			<? endforeach ;?>
		</ul>

		<a class="more view-all" href="<?= $specialContribsLink ;?>">
			<?= wfMsg( 'userprofilepage-top-recent-activity-see-more' ); ?>
			<img src="<?= wfBlankImgUrl() ;?>" class="chevron" />
		</a>
		
	<? else :?>
		<?= wfMsg( 'userprofilepage-recent-activity-default', $userName ) ;?>
	<? endif ;?>
</section>