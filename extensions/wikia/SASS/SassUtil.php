<?php
/**
 * @author Sean Colombo
 */

class SassUtil {
	
	const DEFAULT_OASIS_THEME = 'oasis';

	/**
	 * Gets theme settings from following places:
	 *  - theme designer ($wgOasisThemeSettings)
	 *  - theme chosen using usetheme URL param
	 */
	public static function getOasisSettings() {
		global $wgOasisThemes, $wgUser, $wgAdminSkin, $wgRequest, $wgOasisThemeSettings, $wgContLang, $wgABTests;
		wfProfileIn(__METHOD__);

		// Load the 5 deafult colors by theme here (eg: in case the wiki has an override but the user doesn't have overrides).
		static $oasisSettings = array();

		if (!empty($oasisSettings)) {
			wfProfileOut(__METHOD__);
			return $oasisSettings;
		}

		$themeSettings = new ThemeSettings();
		$settings = $themeSettings->getSettings();

		$oasisSettings["color-body"] = self::sanitizeColor($settings["color-body"]);
		$oasisSettings["color-page"] = self::sanitizeColor($settings["color-page"]);
		$oasisSettings["color-buttons"] = self::sanitizeColor($settings["color-buttons"]);
		$oasisSettings["color-links"] = self::sanitizeColor($settings["color-links"]);
		$oasisSettings["color-header"] = self::sanitizeColor($settings["color-header"]);
		$oasisSettings["background-image"] = wfReplaceImageServer($settings['background-image'], self::getCacheBuster());
		$oasisSettings["background-align"] = $settings["background-align"];
		$oasisSettings["background-tiled"] = $settings["background-tiled"];
		if (isset($settings["wordmark-font"]) && $settings["wordmark-font"] != "default") {
			$oasisSettings["wordmark-font"] = $settings["wordmark-font"];
		}

		// RTL
		if($wgContLang && $wgContLang->isRTL()){
			$oasisSettings['rtl'] = 'true';
		}

		// RT:70673
		foreach ($oasisSettings as $key => $val) {
			if(!empty($val)) {
				$oasisSettings[$key] = trim($val);
			}
		}

		wfDebug(__METHOD__ . ': ' . Wikia::json_encode($oasisSettings) . "\n");

		wfProfileOut(__METHOD__);
		return $oasisSettings;
	}

	/**
	 * Get default theme settings
	 */
	private static function getDefaultOasisSettings() {
		global $wgOasisThemes;
		return $wgOasisThemes[self::DEFAULT_OASIS_THEME];
	}

	/**
	 * Get cache buster value for current version of theme settings
	 */
	public static function getCacheBuster() {
		global $wgOasisThemeSettingsHistory;
		wfProfileIn(__METHOD__);
		static $cb = null;

		if (is_null($cb)) {
			$currentSettings = end($wgOasisThemeSettingsHistory);
			if (!empty($currentSettings['revision'])) {
				$cb = $currentSettings['revision'];
			}
			else {
				$cb = 1;
			}
		}

		wfProfileOut(__METHOD__);
		return $cb;
	}

	/**
	 * Get normalized color value (RT #74057)
	 */
	private static function sanitizeColor($color) {
		$color = trim(strtolower($color));
		return $color;
	}

	/**
	 * Returns an associative array of the parameters to pass to SASS.  These are based on the theme
	 * for the wiki and potentially user-specific overrides.
	 */
	public static function getSassParams(){
		wfProfileIn( __METHOD__ );

		$sassParams = http_build_query(self::getOasisSettings());

		wfProfileOut( __METHOD__ );
		return $sassParams;
	}

	/**
	 * Calculates whether currently used theme is light or dark
	 */
	public static function isThemeDark() {
		wfProfileIn(__METHOD__);

		$oasisSettings = self::getOasisSettings();
		if (empty($oasisSettings)) {
			$oasisSettings = self::getDefaultOasisSettings();
		}

		$backgroundColor = $oasisSettings['color-page'];

		// convert RGB to HSL
		list($hue, $saturation, $lightness) = self::rgb2hsl($backgroundColor);

		$isDark = ($lightness < 0.5);

		wfDebug(__METHOD__ . ': ' . ($isDark ? 'yes' : 'no') . "\n");

		wfProfileOut(__METHOD__);
		return $isDark;
	}
	
	/**
	 * Convert RGB colors array into HSL array
	 *
	 * @see http://blog.archive.jpsykes.com/211/rgb2hsl/index.html
	 *
	 * @param string RGB color in hex format (#474646)
	 * @return array HSL set
	 */
	private static function rgb2hsl($rgbhex){
		wfProfileIn(__METHOD__);

		// convert HEX color to rgb values
		// #474646 -> 71, 70, 70
		$rgb = str_split(substr($rgbhex, 1), 2);
		$rgb = array_map('hexdec', $rgb);

		$clrR = ($rgb[0] / 255);
		$clrG = ($rgb[1] / 255);
		$clrB = ($rgb[2] / 255);

		$clrMin = min($clrR, $clrG, $clrB);
		$clrMax = max($clrR, $clrG, $clrB);
		$deltaMax = $clrMax - $clrMin;

		$L = ($clrMax + $clrMin) / 2;

		if (0 == $deltaMax){
			$H = 0;
			$S = 0;
		}
		else{
			if (0.5 > $L){
				$S = $deltaMax / ($clrMax + $clrMin);
			}
			else{
				$S = $deltaMax / (2 - $clrMax - $clrMin);
			}
			$deltaR = ((($clrMax - $clrR) / 6) + ($deltaMax / 2)) / $deltaMax;
			$deltaG = ((($clrMax - $clrG) / 6) + ($deltaMax / 2)) / $deltaMax;
			$deltaB = ((($clrMax - $clrB) / 6) + ($deltaMax / 2)) / $deltaMax;
			if ($clrR == $clrMax){
				$H = $deltaB - $deltaG;
			}
			else if ($clrG == $clrMax){
				$H = (1 / 3) + $deltaR - $deltaB;
			}
			else if ($clrB == $clrMax){
				$H = (2 / 3) + $deltaG - $deltaR;
			}
			if (0 > $H) $H += 1;
			if (1 < $H) $H -= 1;
		}

		wfProfileOut(__METHOD__);
		return array($H, $S, $L);
	}

}
