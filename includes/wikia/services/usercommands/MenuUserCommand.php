<?php

	class MenuUserCommand extends UserCommand {
		
		public function __construct( $id, $caption, $options = array() ) {
			parent::__construct($id);
			
			$this->available = true;
			$this->enabled = true;
			$this->caption = $caption;
			foreach ($options as $k => $v)
				$this->$k = $v;
		}
		
		protected function buildData() {}
		
		protected $items = array();

		protected function getTrackerName() {
			return '';
		}
		
		public function addItem( $item ) {
			$this->items[] = $item;
		}
		
		public function render() {
			if (empty($this->items)) {
				return '';
			}
			return parent::render();
		}
		
		protected function renderIcon() {
			return '<span class="arrow-icon-ctr"><span class="arrow-icon arrow-icon-single"></span></span>';
		}
		
		public function renderSubmenu() {
			$html = '';
			
			$html .= Xml::openElement('ul',array(
				'id' => $this->name . '-menu',
				'class' => 'tools-menu',
			));
			$html .= self::renderList($this->items);
			$html .= Xml::closeElement('ul');
			
			return $html;
		}

		
		static protected function renderList( $list ) {
			$html = '';
			foreach ($list as $item)
				$html .= $item->render();
			return $html;
		}
		
		public function getInfo() {
			$info = parent::getInfo();
			foreach ($this->items as $item) {
				$itemInfo = $item->getInfo();
				if ($itemInfo)
					$info['items'][] = $itemInfo; 
			}
			return $info;
		}
		
	}