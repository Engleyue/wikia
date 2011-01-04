<section id="profile-top-pages-body" class="module UserProfileRailModule_TopPages">
	<? if( !empty( $topPages ) ) :?>
		<h1><?= wfMsg( 'userprofilepage-top-pages-title', array( $userName, $wikiName ) ) ;?></h1>
		<? foreach( $topPages as $pageId => $page ) :?>
			<div class="top-page-item hovered">
				<a href="<?= $page['url'] ;?>" title="<?= $page['title'] ;?>">
					<div class="top-page-item-image">
						<?php if( !empty( $topPageImages[ $pageId ][ 0 ][ 'url' ] ) ): ?>
							<img class="item-thumbnail" src="<?= $topPageImages[ $pageId ][ 0 ][ 'url' ] ;?>" alt="<?= $page['title'] ;?>">
						<?php else: ?>
							<div class="snippet">
								<span class="quote">“</span>
								<span class="text"><?= $page['textSnippet']; ?></span>
							</div>
						<?php endif; ?>
					</div>
					<details><?= $page['title'] ;?></details>
				</a>

				<? if( $userIsOwner )  :?>
					<div class="hide-control">
						<a class="HideButton" title="<?= wfMsg( 'userprofilepage-top-wikis-hide-label' ) ;?>" data-id="<?= $page['id'];?>">
							<img class="sprite-small close" src="<?= wfBlankImgUrl() ;?>" alt="<?= wfMsg( 'userprofilepage-top-wikis-hide-label' ) ;?>"/>
							<?= wfMsg( 'userprofilepage-top-wikis-hide-label' ) ;?>
						</a>
					</div>
				<? endif; ?>
			</div>
		<? endforeach ;?>

		<? $hiddenCount = count($hiddenTopPages) ;?>
		<? if( $userIsOwner && $hiddenCount ) :?>
			<ul class="user-profile-action-menu" id="profile-top-pages-hidden">
				<li class="unhide-link">
					<a class="more view-all">
						<?= wfMsgExt( 'userprofilepage-top-pages-hidden-see-more', array( 'parsemag' ), $hiddenCount ); ?>
						<img src="<?= wfBlankImgUrl() ;?>" class="chevron" />
					</a>
				</li>
				<? $counter = 0 ;?>
				<? foreach( $hiddenTopPages as $pageId => $page ) :?>
					<li class="hidden-item<?= ( $counter++ == 0 ) ? ' first' : null;?>">
						<div class="item-name">
							<a href="<?= $page['url'] ;?>" title="<?= $page['title'] ;?>"><?= $page['title'] ;?></a>
						</div>
						<div class="unhide-control">
							<a class="UnhideButton" title="<?= wfMsg( 'userprofilepage-top-page-unhide-label' ) ;?>" data-id="<?= $page['id'];?>">
								<img class="sprite-small add" src="<?= wfBlankImgUrl() ;?>" alt="<?= wfMsg( 'userprofilepage-top-page-unhide-label' ) ;?>"/>
								<?= wfMsg( 'userprofilepage-top-page-unhide-label' ) ;?>
							</a>
						</div>
					</li>
				<? endforeach; ?>
			</ul>
		<? endif ;?>
	<? else :?>
		<span><?= wfMsg( 'userprofilepage-top-pages-default', $specialRandomLink ) ;?></span>
	<? endif ;?>
</section>