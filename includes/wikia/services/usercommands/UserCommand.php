<?php

	abstract class UserCommand {
		
		protected $id = null;
		protected $type = null;
		protected $name = null;
		protected $data = null;
		
		public function __construct( $id, $data = array() ) {
			$this->id = $id;
			list( $this->type, $this->name ) = explode(':',$this->id,2);
			$this->data = $data;
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getInfo() {
			$defaultCaption = $this->getAbstractCaption();
			$caption = !empty($this->data['caption']) ? $this->data['caption'] : $defaultCaption;
			return array(
				'id' => $this->getId(),
				'defaultCaption' => $defaultCaption,
				'caption' => $caption,
			);
		}
		
		public function isAvailable() {
			$this->needData();
			return $this->available;
		}
		
		public function isEnabled() {
			$this->needData();
			return $this->enabled;
		}
		
		protected $available = false;
		protected $enabled = false;
		
		protected $imageSprite = false;
		protected $imageUrl = false;
		
		protected $listItemId = '';
		protected $listItemClass = '';
		protected $linkId = '';
		protected $linkClass = '';
		protected $accessKey = false;
		
		protected $href = '#';
		protected $caption = null;
		protected $description = null;
		
		protected $abstractCaption = null;
		protected $abstractDescription = null;
		
		protected function getAbstractCaption() {
			$this->needData();
			return $this->caption;
		}
		
		protected function getAbstractDescription() {
			$this->needData();
			return $this->description;
		}
		
		protected function getListItemAttributes() {
			$attributes = array();
			if ($this->listItemId) $attributes['id'] = $this->listItemId;
			if ($this->listItemClass) $attributes['class'] = $this->listItemClass;
			return $attributes;
		}
		
		protected function getLinkAttributes() {
			$attributes = array();
			$attributes['data-tool-id'] = $this->id;
			$attributes['data-name'] = $this->name;
			if ($this->href) $attributes['href'] = $this->href;
			if ($this->linkId) $attributes['id'] = $this->linkId;
			if ($this->linkClass) $attributes['class'] = $this->linkClass;
			if ($this->accessKey) $attributes['accesskey'] = $this->accessKey;
			return $attributes;
		}
		
		public function render() {
			$this->needData();
			
			if (!$this->available) {
				return '';
			}
			
			$html = '';
			$html .= Xml::openElement('li',$this->getListItemAttributes());
			
			$html .= $this->renderIcon();
			
			if ($this->enabled) {
				$html .= Xml::element('a',$this->getLinkAttributes(),$this->caption);
				$html .= $this->renderSubmenu();
			} else {
				$spanAttributes = array(
					'title' => $this->getDisabledMessage(),
				);
				$html .= Xml::element('span',$spanAttributes,$this->caption);
			}
			
			$html .= Xml::closeElement('li');
			return $html;			
		}
		
		protected function renderIcon() {
			return '';
		}
		
		public function renderSubmenu() {
			return '';
		}
		
		protected function getDisabledMessage() {
			return wfMsg('oasis-toolbar-for-admins-only');
		}

		
		protected $dataBuilt = false;
		
		protected function needData() {
			if (!$this->dataBuilt) {
				$this->buildData();
				if (!empty($this->data['caption']))
					$this->caption = $this->data['caption'];
				$this->dataBuilt = true;
			}
		}
		
		abstract protected function buildData();
		
		static protected $skinData = null;
		
		static public function setSkinData( $skinData ) {
			self::$skinData = $skinData; 
		}
		
		static public function needSkinData() {
			if (is_null(self::$skinData)) {
				global $wgTitle;
				self::$skinData = array(
					'content_actions' => $wgSkin->buildContentActions(),
					'nav_urls' => $wgSkin->buildNavUrls(),
				);
			}
		}
		
		
	}
	