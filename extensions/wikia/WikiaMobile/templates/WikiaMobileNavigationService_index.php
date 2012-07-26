<?php
	$loggedIn = $wg->User->getId() > 0;
    $searchPage = $wg->Title->isSpecial( 'Search' );
?>
<section id=wkTopNav<?= $searchPage ? ' class="srhOpn"' : ''?>>
	<div id=wkTopBar>
		<a href=<?= Title::newMainPage()->getFullURL() ?>>
		<? if( $wordmarkType == "graphic" ) :?>
			<img id=wkImgMrk src="<?= $wordmarkUrl ;?>">
		<? else :?>
			<div id=wkWrdMrk><?= $wikiName ;?></div>
		<? endif ;?>
		</a>

	<a href=#wkNavSrh id=wkSrhTgl class=tgl></a>
	<a href=#wkNavMenu id=wkNavTgl class=tgl></a>
	<? if($wg->EnableUserLoginExt) : ?>
		<a href="<?= SpecialPage::getTitleFor('UserLogin')->getLocalURL() ;?>" id=wkPrfTgl class='tgl <?= ($loggedIn ? 'lgdin' : 'lgdout') ?>'></a>
	<? endif ; ?>
	</div>
	<div id=wkSrh>
		<form id=wkSrhFrm action="<?= SpecialPage::getSafeTitleFor( 'Search' )->getLocalURL()?>" method=get>
			<input type=hidden name=fulltext value=Search>
			<input class=wkInp id=wkSrhInp type=text name=search placeholder="<?= $wf->Msg( 'wikiamobile-search-this-wiki' ); ?>" required=required autocomplete=off>
			<div id=wkClear class='clsIco hide'></div>
			<input id=wkSrhSub class='wkBtn main' type=submit value='<?= $wf->Msg( 'wikiamobile-search' ); ?>'>
		</form>
		<ul id=wkSrhSug class=wkLst></ul>
	</div>
	<div id=wkNav>
		<header class="wkPrfHead up"><?= $wf->Msg('wikiamobile-menu') ?></header>
		<nav id=wkWikiNav></nav>
	</div>
	<? if($wg->EnableUserLoginExt) : ?>
	<div id=wkPrf>
		<header class="wkPrfHead<?= (!$loggedIn) ? ' up' : '' ?>">
		<? if($loggedIn) {
		$userName = $wg->User->getName();
			echo AvatarService::renderAvatar( $userName, 22 ) . $userName;
		} else {
			echo $wf->Msg('userlogin-login-heading');
		}
		?>
		</header>
	<? if($loggedIn) :?>
		<ul class=wkLst>
			<li><a class=chg href="<?= AvatarService::getUrl($userName) ;?>"><?= $wf->Msg('wikiamobile-profile'); ?></a></li>
			<li><a class=logout href="<?= str_replace( "$1", SpecialPage::getSafeTitleFor('UserLogout')->getPrefixedText() . '?returnto=' . $wg->Title->getPrefixedURL(), $wg->ArticlePath ) ;?>"><?= $wf->Msg('logout'); ?></a></li>
		</ul>
	<? endif; ?>
	</div>
	<? endif ; ?>
</section>