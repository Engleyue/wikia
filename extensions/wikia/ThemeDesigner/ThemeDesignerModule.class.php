<?php
class ThemeDesignerModule extends Module {

	var $wgCdnRootUrl;
	var $wgExtensionsPath;
	var $wgStylePath;

	var $wgServer;
	var $dir;
	var $mimetype;
	var $charset;

	var $themeSettings;

	public function executeIndex() {
		$this->themeSettings = new ThemeSettings();
	}

	public function executeThemeTab() {

	}

	public function executeCustomizeTab() {

	}

}
