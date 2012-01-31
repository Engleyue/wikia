<?php

class MyvideoApiWrapper extends ApiWrapper {
	
	protected static $RESPONSE_FORMAT = self::RESPONSE_FORMAT_XML;
	protected static $API_URL = 'https://api.myvideo.de/prod/mobile/api2_rest.php?method=myvideo.videos.get_details&dev_id=$1&website_id=$2&movie_id=$3&player_size=3';
	protected static $CACHE_KEY = 'myvideoapi';

	protected function getApiUrl() {
		global $wgMyvideoApiDevId, $wgMyvideoApiWebsiteId;
		$apiUrl = str_replace( '$1', $wgMyvideoApiDevId, static::$API_URL );
		$apiUrl = str_replace( '$2', $wgMyvideoApiWebsiteId, $apiUrl );
		$apiUrl = str_replace( '$3', $this->videoId, $apiUrl );
		return $apiUrl;
	}

	protected function initializeInterfaceObject(){
		$interfaceObj = $this->getInterfaceObjectFromType( static::$RESPONSE_FORMAT );
		$this->interfaceObj = $interfaceObj['child']['']['response'][0]['child']['']['myvideo'][0]['child']['']['movie'][0]['child'][''];
	}

	public function getTitle() {
		return $this->getVideoTitle();
	}

	protected function getVideoTitle() {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_title'][0]['data'];
		}

		return '';
	}

	public function getDescription() {
		$desc = $this->getOriginalDescription();
		if ($this->getVideoCategory()) $desc .= "\n\nCategory: " . $this->getVideoCategory();
		if ($this->getVideoKeywords()) $desc .= "\n\nKeywords: " . $this->getVideoKeywords();
		return $desc;
	}
	
	public function getThumbnailUrl() {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_thumbnail'][0]['data'];
		}
		
		return '';
	}
		
	protected function getOriginalDescription($stripTitleAndYear=true) {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_description'][0]['data'];
		}
		
		return '';		
	}
	
	protected function getVideoPublished() {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_added'][0]['data'];
		}
		return '';
	}
	
	protected function getVideoDuration() {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_length'][0]['data'];
		}
	}	
	
	protected function getVideoKeywords() {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_tags'][0]['data'];
		}
		return '';
	}
	
	protected function getVideoCategory() {
		if (!empty($this->interfaceObj)) {
			return $this->interfaceObj['movie_cat'][0]['data'];
		}
	}
}