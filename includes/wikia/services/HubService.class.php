<?php

class HubService extends Service {
	private static $comscore_prefix = 'comscore_';
	// WF city_ids
	private static $corporate_sites = array(80433, 111264);

	/**
	 * get proper category to report to Comscore for cityId
	 *
	 * @param integer    $city_id        wikia identifier in city_list
	 *
	 * @return StdClass ($row->cat_id $row->cat_name) or false
	 */
	public static function getComscoreCategory($cityId) {
		$catInfo = null;

		// look for Comscore tag
		$wftags = new WikiFactoryTags($cityId);
		$tags = $wftags->getTags();
		if (is_array($tags)) {
			foreach ($tags as $name) {
				if (startsWith($name, self::$comscore_prefix, false)) {
					$catName = substr($name, strlen(self::$comscore_prefix));
					$category = WikiFactoryHub::getInstance()->getCategoryByName($catName);
					$catInfo = self::initCategoryInfo($category['id'], $category['name']);
					break;
				}
			}
		}

		if (empty($catInfo)) {
			if ((F::app()->wg->enableWikiaHubsExt || F::app()->wg->enableWikiaHubsV2Ext) && self::isCorporatePage($cityId)) {
				$catInfo = self::getWikiaHubsCategory();
			} else {
				$catInfo = WikiFactory::getCategory($cityId);
			}

			if (empty($catInfo)) {
				$lifestyleHub = WikiFactoryHub::getInstance()->getCategory(WikiFactoryHub::CATEGORY_ID_LIFESTYLE);
				$hubName = $lifestyleHub['name'];
				$hubId = WikiFactoryHub::CATEGORY_ID_LIFESTYLE;
				$catInfo = self::initCategoryInfo($hubId, $hubName);
			} else {
				switch ($catInfo->cat_id) {
					// leave these categories alone
					case WikiFactoryHub::CATEGORY_ID_GAMING:
					case WikiFactoryHub::CATEGORY_ID_ENTERTAINMENT:
					case WikiFactoryHub::CATEGORY_ID_LIFESTYLE:
					case WikiFactoryHub::CATEGORY_ID_CORPORATE:
						$catId = $catInfo->cat_id;
						$catName = $catInfo->cat_name;
						break;
					// force category entertainment
					case WikiFactoryHub::CATEGORY_ID_MUSIC:
						$entertainmentHub = WikiFactoryHub::getInstance()->getCategory(WikiFactoryHub::CATEGORY_ID_ENTERTAINMENT);
						$catId = WikiFactoryHub::CATEGORY_ID_ENTERTAINMENT;
						$catName = $entertainmentHub['name'];
						break;
					// force category lifestyle
					default:
						$lifestyleHub = WikiFactoryHub::getInstance()->getCategory(WikiFactoryHub::CATEGORY_ID_LIFESTYLE);
						$catId = WikiFactoryHub::CATEGORY_ID_LIFESTYLE;
						$catName = $lifestyleHub['name'];
				}

				$catInfo = self::initCategoryInfo($catId, $catName);
			}
		}
		return $catInfo;
	}

	public static function isCorporatePage($cityId) {
		if (in_array($cityId, self::$corporate_sites)) {
			return true;
		} else {
			return false;
		}
	}

	protected static function getWikiaHubsCategory() {
		if (F::app()->wg->enableWikiaHubsV2Ext) {
			$catInfo = self::getWikiaHubsV2Category();
		} elseif (F::app()->wg->enableWikiaHubsExt) {
			$catInfo = self::getWikiaHubsV1Category();
		}

		if (empty($catInfo)) {
			$wikiaHub = WikiFactoryHub::getInstance()->getCategory(WikiFactoryHub::CATEGORY_ID_CORPORATE);
			$hubName = $wikiaHub['name'];
			$hubId = WikiFactoryHub::CATEGORY_ID_CORPORATE;
			$catInfo = self::initCategoryInfo($hubId, $hubName);
		}

		return $catInfo;
	}

	private static function getWikiaHubsV1Category() {
		$baseText = F::app()->wg->Title->getBaseText();

		/** @var $tmpTitle Title */
		$title = F::build('Title', array($baseText), 'newFromText');

		$hubsPages = F::app()->wg->wikiaHubsPages;
		if (!empty($hubsPages) && $title instanceof Title) {
			/* @var $title Title */
			$textTitle = $title->getDBKey();

			foreach ($hubsPages as $hubPageId => $hubGroup) {
				if (!empty($textTitle) && in_array($textTitle, $hubGroup)) {
					$hubId = $hubPageId;
					$hubName = $textTitle;
					$catInfo = self::initCategoryInfo($hubId, $hubName);
					return $catInfo;
				}
			}
		}

		return false;
	}

	private static function getWikiaHubsV2Category() {
		$hubsPages = F::app()->wg->wikiaHubsPages;
		$vertical = RequestContext::getMain()->getRequest()->getVal('vertical');
		$title = F::build('Title', array($vertical), 'newFromText');

		if ($title instanceof Title) {
			/* @var $title Title */
			$hubName = $title->getDbKey();

			if ($hubName) {
				foreach ($hubsPages as $hubPageId => $hubGroup) {
					if (in_array($hubName, $hubGroup)) {
						$hubId = $hubPageId;
						$catInfo = self::initCategoryInfo($hubId, $hubName);
						return $catInfo;
					}
				}
			}
		}
		return false;
	}

	private static function initCategoryInfo($id, $name) {
		$catInfo = new StdClass();

		$catInfo->cat_id = $id;
		$catInfo->cat_name = $name;

		return $catInfo;
	}

}
