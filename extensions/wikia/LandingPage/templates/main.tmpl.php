<section class="LandingPage">
	<div class="LandingPageSearch">
		<?= F::app()->getView("SearchModule", "Index", array ("placeholder" => "Search Wikia", "fulltext" => "0", "wgBlankImgUrl" => $wgBlankImgUrl, "wgTitle" => $wgTitle)); ?>
	</div>

	<ul class="LandingPageLanguageLinks">
<?php
foreach($languageLinks as $item) {
?>
		<li>[<a href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['text']) ?></a>]</li>
<?php
}
?>
	</ul>

	<ul class="LandingPageSocialLinks">
		<li class="twitter"><a alt="Follow us on Twitter" href="<?= wfMsg('landingpage-twitter-url') ?>"></a></li>
		<li class="facebook"><a alt="Wikia on Facebook" href="<?= wfMsg('landingpage-facebook-url') ?>"></a></li>
		<li class="blog"><a alt="Read our Blog" href="<?= wfMsg('landingpage-wikia-blog-url') ?>"></a></li>
	</ul>

	<div class="LandingPageSites">
		<h2><?= wfMsg('landingpage-start-exploring') ?></h2>
		<p><?= wfMsg('landingpage-start-exploring-text') ?></p>
		
		<ul>
		
<?php
		$counter = 0;
		foreach ($landingPageLinks as $link) {
			$counter ++; ?>
			<li class="landingpage-site<?= $counter ?>" style="background-image: url(<?= $link["imagename"] ?>)" ><a alt="<?=  $link["title"] ?>" href="<?= $link["href"] ?>"><img src="<?= $wgBlankImgUrl ?>"></a></li>
		<?php 
		} ?>
		
		</ul>
		<a href="<?= wfMsg('landingpage-faq-url') ?>" class="more"><?= wfMsg('landingpage-readfaq') ?></a>
	</div>

	<section class="LandingPageWelcome">
				<h1><?= wfMsg('landingpage') ?></h1>
	</section>

	<section class="LandingPageVideo">
		<iframe class="video" swidth="605" sheight="335" frameborder="0" src="http://player.vimeo.com/video/15645799">		</iframe>
	</section>
	
	<p class="LandingPageMain">
		<?= wfMsg('landingpage-text') ?>
		<a href="<?= wfMsg('landingpage-buttons-about-wikia-url') ?>" class="more"><?= wfMsg('landingpage-buttons-learn-more') ?></a>
	</p>
</section>