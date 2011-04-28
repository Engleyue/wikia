<?php
class FooterModule extends Module {

	var $wgBlankImgUrl;

	var $showToolbar;
	var $showNotifications;
	var $showLoadTime;
	var $loadTimeStats;

	public function executeIndex() {
		global $wgTitle, $wgContentNamespaces, $wgUser, $wgSuppressToolbar, $wgShowMyToolsOnly, $wgEnableShowPerformanceStatsExt;

		// don't show toolbar when wgSuppressToolbar is set (for instance on edit pages)
		$this->showToolbar = empty($wgSuppressToolbar) && !$wgUser->isAnon();
		if ($this->showToolbar == false) {
			return;
		}

		// show only "My Tools" dropdown on toolbar
		if (!empty($wgShowMyToolsOnly)) {
			return;
		}

		$this->showNotifications = true;

		// If this user is a member of staff, display the 'loadtime' stats in the footer bar.
		$this->showLoadTime = false;
		if(!empty($wgEnableShowPerformanceStatsExt)){
			if($wgUser->isAllowed('performancestats')){
				$this->showLoadTime = true;
				$this->loadTimeStats = wfGetPerformanceStats();
			}
		}
	}

	static protected $toolbarService = null;

	protected function getToolbarService() {
		if (empty(self::$toolbarService)) {
			self::$toolbarService = new OasisToolbarService();
		}
		return self::$toolbarService;
	}


	public function executeToolbar() {
		$service = $this->getToolbarService();
		$toolbar = $service->listToInstance($service->getVisibleList());
		$this->toolbar = $service->instanceToRenderData($toolbar);
	}

	public function executeToolbarConfigurationPopup() {
	}

	public function executeToolbarConfigurationRenameItemPopup() {
	}

	public function executeToolbarConfiguration() {
		$this->configurationHtml = wfRenderModule('Footer','ToolbarConfigurationPopup',array('format' => 'html'));
		$this->renameItemHtml = wfRenderModule('Footer','ToolbarConfigurationRenameItemPopup',array('format' => 'html'));

		$service = $this->getToolbarService();
		$this->defaultOptions = $service->listToJson($service->getDefaultList());
		$this->options = $service->listToJson($service->getCurrentList());
		$this->allOptions = $service->sortJsonByCaption($service->listToJson($service->getAllList()));
		$this->popularOptions = $service->sortJsonByCaption($service->listToJson($service->getPopularList()));
	}

	public function executeToolbarSave( $params ) {
		$this->status = false;
		$service = $this->getToolbarService();
		if (isset($params['toolbar']) && is_array($params['toolbar'])) {
			$data = $service->jsonToList($params['toolbar']);
			if (!empty($data)) {
				global $wgUser;
				$this->status = $service->save($data);
			}
		}
		$this->toolbar = wfRenderModule('Footer','Toolbar',array('format' => 'html'));
	}

	public function executeToolbarGetList() {
		$service = $this->getToolbarService();
		$this->allOptions = $service->listToJson($service->getAllList());
	}

	public function executeDebug() {
		global $wgRequest;
		$service = $this->getToolbarService();
		if ($wgRequest->getVal('clear',false)) {
			$service->clear();
		}
		$this->defaultList = $service->listToJson($service->getDefaultList());
		$this->storedList = $service->load();
		$this->currentList = $service->listToJson($service->getCurrentList());

		$this->seenPromotions = $service->getSeenPromotions();

		global $wgUser;
		$data = $wgUser->getOption('myTools',false);
		$data = $data ? json_decode($data,true) : false;
		$this->myTools = $data;
	}

	public function executeMenu( $params ) {
		$this->items = (array)$params['items'];
	}

}
