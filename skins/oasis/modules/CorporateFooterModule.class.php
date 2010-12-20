<?php

class CorporateFooterModule extends Module {
	var $wgBlankImgUrl;

	var $footer_links;
	var $wgUser;
	var $hub;

	public function executeIndex() {
		global $wgLangToCentralMap, $wgContLang, $wgCityId, $wgUser, $wgLang, $wgMemc;
		$catId = WikiFactoryHub::getInstance()->getCategoryId($wgCityId);
		$mKey = wfMemcKey('mOasisFooterLinks', $wgLang->getCode(), $catId);
		$this->footer_links = $wgMemc->get($mKey);
		if (empty($this->footer_links)) {
			$this->footer_links = $this->getWikiaFooterLinks();
			$wgMemc->set($mKey, $this->footer_links, 86400);
		}

		$this->hub = $this->getHub();
	}

	private function getHub() {
		wfProfileIn( __METHOD__ );
		global $wgCityId;

		$catInfo = WikiFactory::getCategory($wgCityId);
		if (empty($catInfo) || ($catInfo->cat_id != 2 && $catInfo->cat_id != 3 && $catInfo->cat_id != 4)) {	// 2: Gaming. 3: Entertainment. 4: Corporate
			//Use Recipes Wiki cityID to force Lifestyle hub
			$catInfo = WikiFactory::getCategory(3355);
		}

		//i18n
		if (!empty($catInfo)) {
			$catInfo->cat_name = wfMsg('hub-'. $catInfo->cat_name);
		}

		wfProfileOut( __METHOD__ );
		return $catInfo;
	}

	/**
	 * @author Inez Korczynski <inez@wikia.com>
	 */
	 private function getWikiaFooterLinks() {
		wfProfileIn( __METHOD__ );

		global $wgCityId;
		$catId = WikiFactoryHub::getInstance()->getCategoryId($wgCityId);

		$message_key = 'shared-Oasis-footer-wikia-links';
		$nodes = array();

		if(!isset($catId) || null == ($lines = getMessageAsArray($message_key.'-'.$catId))) {
			wfDebugLog('monaco', $message_key.'-'.$catId . ' - seems to be empty');
			if(null == ($lines = getMessageAsArray($message_key))) {
				wfDebugLog('monaco', $message_key . ' - seems to be empty');
				wfProfileOut( __METHOD__ );
				return $nodes;
			}
		}

		foreach($lines as $line) {
			$depth = strrpos($line, '*');
			if($depth === 0) {
				$nodes[] = parseItem($line);
			}
		}


		wfProfileOut( __METHOD__ );
		return $nodes;
	}
}
