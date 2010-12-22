<?php

class ImageLightbox {
	const MIN_HEIGHT = 300;
	const MIN_WIDTH = 300;

	static $shareFeatureSites = array(
		array(
			'name'	=>	'Facebook',
			'url' 	=>	'http://www.facebook.com/sharer.php?u=$1&t=$2'
		),
		array(
			'name'	=>	'Twitter',
			'url'	=>	'http://twitter.com/home?status=$1%20$2'
		), // message and url goes into the one parameter here for Twitter...
		array(
			'name'	=>	'Stumbleupon',
			'url'	=>	'http://www.stumbleupon.com/submit?url=$1&title=$2'
		),
		array(
			'name' 	=>	'Reddit',
			'url' 	=>	'http://www.reddit.com/submit?url=$1&title=$2'
		)
	);

	/**
	 * Add global JS variable indicating this extension is enabled (RT #47665)
	 */
	static public function addJSVariable(&$vars) {
		$vars['wgEnableImageLightboxExt'] = true;
		return true;
	}

	/**
	 * Handle AJAX request and return bigger version of requested image
	 */
	static public function ajax() {
		global $wgTitle, $wgBlankImgUrl, $wgRequest, $wgServer, $wgStylePath, $wgExtensionsPath, $wgSitename;
		wfProfileIn(__METHOD__);

		// limit dimensions of returned image to fit browser's viewport
		$maxWidth = $wgRequest->getInt('maxwidth', 500) - 20;
		$maxHeight = $wgRequest->getInt('maxheight', 300) - 150;
		$showShareTools = $wgRequest->getInt('share', 0);
		$pageName = $wgRequest->getVal('pageName');

		$image = wfFindFile($wgTitle);

		if (empty($image)) {
			wfProfileOut(__METHOD__);
			return array();
		}

		// get original dimensions of an image
		$width = $image->getWidth();
		$height = $image->getHeight();

		// don't try to make image larger
		if ($width > $maxWidth or $height > $maxHeight) {
			$width = $maxWidth;
			$height = $maxHeight;
		}

		// generate thumbnail
		$thumb = $image->getThumbnail($width, $height);

		$thumbHeight = $thumb->getHeight();
		$thumbWidth = $thumb->getWidth();

		// lightbox should not be smaller then 200x200
		$wrapperHeight = max($thumbHeight, self::MIN_HEIGHT);
		$wrapperWidth = max($thumbWidth, self::MIN_WIDTH);

		//generate share links
		$currentTitle = Title::newFromText($pageName);
		if (empty($currentTitle)) {
			//should not happen, ever
			throw new MWException("Could not create Title from $pageName\n");
		}

		wfLoadExtensionMessages('ImageLightbox');

		$thumbUrl = $thumb->getUrl();
		$imageTitle = $wgTitle->getText();
		$imageParam = preg_replace('/[^a-z0-9_]/i', '-', Sanitizer::escapeId($imageTitle));
		$linkStd = $currentTitle->getFullURL("image=$imageParam");
		$linkWWW = "<a href=\"$linkStd\"><img width=\"" . $thumb->getWidth() . "\" height=\"" . $thumb->getHeight() . "\" src=\"$thumbUrl\"/></a>";
		$linkBBcode = "[url=$linkStd][img]{$thumbUrl}[/img][/url]";

		//double encode - JSON is decoding it
		$linkStdEncoded = rawurlencode(str_replace('&', '%26', $linkStd));
		$linkDescription = wfMsg('lightbox-share-description', $currentTitle->getText(), $wgSitename);
		$shareButtons = array();
		foreach (self::$shareFeatureSites as $button) {
			$url = str_replace('$1', $linkStdEncoded, $button['url']);
			$url = str_replace('$2', $linkDescription, $url);
			$type = strtolower($button['name']);
			$shareButtons[] = array(
				'class' => 'share-' . $type,
				'text' => $button['name'],
				'type' => $type,
				'url' => $url
			);
		}

		// FB like - generate URL and check lightness of skin theme
		$likeHref = $linkStd;
		$likeTheme = SassUtil::isThemeDark() ? 'dark' : 'light';

		// render HTML

		$tmpl = new EasyTemplate(dirname(__FILE__));
		$tmpl->set_vars(array(
			'href' => $wgTitle->getLocalUrl(),
			'likeHref' => $likeHref,
			'likeTheme' => $likeTheme,
			'linkBBcode' => $linkBBcode,
			'linkStd' => $linkStd,
			'linkWWW' => $linkWWW,
			'name' => $imageTitle,
			'shareButtons' => $shareButtons,
			'showShareTools' => $showShareTools,
			'stylePath' => $wgStylePath,
			'thumbHeight' => $thumbHeight,
			'thumbUrl' => $thumbUrl,
			'thumbWidth' => $thumbWidth,
			'wgBlankImgUrl' => $wgBlankImgUrl,
			'wgExtensionsPath' => $wgExtensionsPath,
			'wrapperHeight' => $wrapperHeight,
			'wrapperWidth' => $wrapperWidth
		));

		$html = $tmpl->render('ImageLightbox');

		$res = array(
			'html' => $html,
			'title' => $wgTitle->getText(),
			'width' => $wrapperWidth,
		);

		wfProfileOut(__METHOD__);
		return $res;
	}

	/**
 	 * AJAX function for sending share e-mails
	 *
	 * @author Marooned
	 */
	static function sendMail() {
		global $wgRequest, $wgTitle, $wgNoReplyAddress;
		wfProfileIn(__METHOD__);

		wfLoadExtensionMessages('ImageLightbox');

		$addresses = $wgRequest->getVal('addresses');
		if (!empty($addresses)) {
			$addresses = explode(',', $addresses);
			$countMails = count($addresses);

			$res = array(
				'result' => 1,
				'info-caption' => wfMsg('lightbox-share-email-ok-caption'),
				'info-content' => wfMsgExt('lightbox-share-email-ok-content', array('parsemag'), $countMails)
			);

			//generate shared link
			$pageName = $wgRequest->getVal('pageName');
			$currentTitle = Title::newFromText($pageName);
			if (empty($currentTitle)) {
				//should not happen, ever
				throw new MWException("Could not create Title from $pageName\n");
			}
			$imageTitle = $wgTitle->getText();
			$imageParam = preg_replace('/[^a-z0-9_]/i', '-', Sanitizer::escapeId($imageTitle));
			$linkStd = $currentTitle->getFullURL("image=$imageParam");

			//send mails
			$sender = new MailAddress($wgNoReplyAddress, 'Wikia');	//TODO: use some standard variable for 'Wikia'?
			foreach ($addresses as $address) {
				$to = new MailAddress($address);

				//TODO: support sendHTML
				$result = UserMailer::send(
					$to,
					$sender,
					wfMsg('lightbox-share-email-subject'),
					wfMsg('lightbox-share-email-body', $linkStd),
					null,
					null,
					'ImageLightboxShare'
				);
				if (WikiError::isError($result)) {
					$res = array(
						'result' => 0,
						'info-caption' => wfMsg('lightbox-share-email-error-caption'),
						'info-content' => wfMsgExt('lightbox-share-email-error-content', array('parsemag'), $countMails, $result->toString())
					);
				}
			}
		} else {
			$res = array(
				'result' => 0,
				'info-caption' => wfMsg('lightbox-share-email-error-caption'),
				'info-content' => wfMsgExt('lightbox-share-email-error-content', array('parsemag'), $countMails, wfMsg('lightbox-share-email-error-noaddress'))
			);
		}

		wfProfileOut(__METHOD__);
		return $res;
	}
}