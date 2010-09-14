<?php

class AdModule extends Module {

	private static $config;

	private function configure() {
		global $wgOut, $wgTitle, $wgContentNamespaces, $wgEnableAdInvisibleHomeTop, $wgEnableAdInvisibleTop, $wgEnableFAST_HOME2;

		self::$config = array();

		if(!$wgOut->isArticle()) {
			return;
		}

		$namespace = $wgTitle->getNamespace();

		if(ArticleAdLogic::isMainPage()) {
			// main page
			self::$config['HOME_TOP_LEADERBOARD'] = true;
			self::$config['PREFOOTER_LEFT_BOXAD'] = true;
			self::$config['PREFOOTER_RIGHT_BOXAD'] = true;
			self::$config['INVISIBLE_1'] = true;
			self::$config['INVISIBLE_2 '] = true;
			if(!empty($wgEnableAdInvisibleHomeTop)) {
				self::$config['HOME_INVISIBLE_TOP'] = true;
			}
			if($wgEnableFAST_HOME2) {
				self::$config['HOME_TOP_RIGHT_BOXAD'] = true;
			}
		} else {
			if(in_array($namespace, $wgContentNamespaces)) {
				// content page
				self::$config['TOP_LEADERBOARD'] = true;
				self::$config['INCONTENT_BOXAD_1'] = true;
				self::$config['PREFOOTER_LEFT_BOXAD'] = true;
				self::$config['PREFOOTER_RIGHT_BOXAD'] = true;
				self::$config['INVISIBLE_1'] = true;
				self::$config['INVISIBLE_2 '] = true;
				if(ArticleAdLogic::isLongArticle(self::getSkinTemplateObj()->data['bodytext'])) {
					// long content page
					self::$config['LEFT_SKYSCRAPER_2'] = true;
					self::$config['LEFT_SKYSCRAPER_3'] = true;
				}
				if(!empty($wgEnableAdInvisibleTop)) {
					self::$config['INVISIBLE_TOP'] = true;
				}
			} else if($namespace == NS_FILE) {
				// file/image page
				self::$config['TOP_LEADERBOARD'] = true;
			} else if($namespace == NS_SPECIAL && $wgTitle->isSpecial('Search')) {
				// search results page
				self::$config['TOP_LEADERBOARD'] = true;
				self::$config['TOP_RIGHT_BOXAD'] = true;
			} else if($namespace == NS_CATEGORY) {
				// category page
				self::$config['TOP_LEADERBOARD'] = true;
				self::$config['LEFT_SKYSCRAPER_2'] = true;
				self::$config['PREFOOTER_LEFT_BOXAD'] = true;
				self::$config['PREFOOTER_RIGHT_BOXAD'] = true;
			}
		}
	}


	public $slotname;

	public $ad;

	public function executeIndex($params) {

		if(self::$config === null) {
			$this->configure();
		}

		$this->slotname = $params['slotname'];

		if(isset(self::$config[$this->slotname])) {
			$this->ad = AdEngine::getInstance()->getPlaceHolderIframe($this->slotname);
		}

	}

	public $conf;

	public function executeConfig($params) {

		if(self::$config === null) {
			$this->configure();
		}

		$this->conf = self::$config;

	}

}
