<?php

class RealgravityApiWrapper extends WikiaVideoApiWrapper {

	protected static $CACHE_KEY = 'realgravityapi';

	public function getDescription() {
		$description = $this->getOriginalDescription();
		if ($category = $this->getVideoCategory()) {
			$description .= "\n\nCategory: $category";
		}
		if ($keywords = $this->getVideoKeywords()) {
			$description .= "\n\nKeywords: $keywords";
		}
		
		return $description;
	}

	public function getThumbnailUrl() {
		if (!empty($this->metadata['thumbnail'])) {
			return $this->metadata['thumbnail'];
		}
		elseif (!empty($this->interfaceObj[1])) {
			return $this->interfaceObj[1];
		}
		return '';
	}

	protected function getOriginalDescription() {
		if (!empty($this->interfaceObj[3])) {
			return $this->interfaceObj[3];
		}
		return '';
	}

	protected function getVideoDuration() {
		if (!empty($this->interfaceObj[2])) {
			return $this->interfaceObj[2];
		}
		return '';
	}

	protected function getAspectRatio() {
		$ratio = '';
		if (!empty($this->metadata['dimensions'])) {
			$ratio = $this->metadata['dimensions'];
		}
		elseif (!empty($this->interfaceObj[0])) {
			$ratio = $this->interfaceObj[0];
		}
		if ($ratio) {
			list($width, $height) = explode('x', $ratio);
			$ratio = $width / $height;
		}
		return $ratio;
	}
	
	protected function getVideoPublished() {
		if (!empty($this->metadata['published'])) {
			return $this->metadata['published'];
		}
		
		return '';
	}
	
	protected function getVideoCategory() {
		if (!empty($this->metadata['category'])) {
			return $this->metadata['category'];
		}
		
		return '';
	}
	
	protected function getVideoKeywords() {
		if (!empty($this->metadata['keywords'])) {
			return $this->metadata['keywords'];
		}
		
		return '';
	}

}