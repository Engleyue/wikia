<?php

class RealgravityFeedIngester extends VideoFeedIngester {
	protected static $API_WRAPPER = 'RealgravityApiWrapper';
	protected static $PROVIDER = 'realgravity';
	private static $REALGRAVITY_API_KEY = '4bd3e310-9c30-012e-b52b-12313d017962';
	private static $API_PROVIDER_IDS = array('MACHINIMA'=>240);
	private static $API_PAGE_SIZE = 100;
	private static $API_VIDEOS_URL = 'http://mediacast.realgravity.com/vs/2/videos/$1.xml?providers=$2&lookup_columns=tag_list,title&search_term=$3&per_page=$4&page=$5';
	
	public function import($file='', $params=array()) {
		$numCreated = 0;
		
		if (!empty($params['keyphrasesCategories'])) {
			foreach ($params['keyphrasesCategories'] as $keyphrase=>$categories) {
				$movieParams = array(
				    'addlCategories'	=> $categories,
				    'debug'		=> !empty($params['debug']), 
				    'startDate'		=> !empty($params['startDate']) ? $params['startDate'] : '',
				    'endDate'		=> !empty($params['endDate']) ? $params['endDate'] : ''					
				    );
				$numCreated += $this->importVideosForKeyphrase($keyphrase, $movieParams);
			}
		}
		
		return $numCreated;
	}

	protected function importVideosForKeyphrase($keyword, $params=array()) {
		$addlCategories = !empty($params['addlCategories']) ? $params['addlCategories'] : array();
		$debug = !empty($params['debug']);
		$startDate = !empty($params['startDate']) ? $params['startDate'] : '';
		$endDate = !empty($params['endDate']) ? $params['endDate'] : '';
		
		$articlesCreated = 0;		
		$page = 1;

		do {
			$numVideos = 0;
	
			$url = $this->initFeedUrl($keyword, $startDate, $endDate, $page++);

			$info = array();
			print("Connecting to $url...\n");

			$xmlContent = $this->getUrlContent($url);

			if (!$xmlContent) {
				print("ERROR: problem downloading content!\n");
				return 0;
			}

			$doc = new DOMDocument( '1.0', 'UTF-8' );
			@$doc->loadXML( $xmlContent );
			$videos = $doc->getElementsByTagName('video');
			$numVideos = $videos->length;
			print("Found $numVideos videos...\n");
			for ($i=0; $i<$numVideos; $i++) {
				$clipData = array();
				$video = $videos->item($i);
				$clipData['clipTitle'] = $video->getElementsByTagName('title')->item(0)->textContent;
				$clipData['videoId'] = $video->getElementsByTagName('guid')->item(0)->textContent;
				$clipData['thumbnail'] = $video->getElementsByTagName('thumbnail-url')->item(0)->textContent;
				$clipData['duration'] = $video->getElementsByTagName('duration')->item(0)->textContent;
				$clipData['published'] = $video->getElementsByTagName('published-at')->item(0)->textContent;
				$clipData['category'] = $video->getElementsByTagName('category-name')->item(0)->textContent;
				$clipData['keywords'] = $video->getElementsByTagName('tag-list')->item(0)->textContent;
				if ($video->getElementsByTagName('source-video-props')->item(0)) {			
					$sourceVideoPropsTxt = $video->getElementsByTagName('source-video-props')->item(0)->textContent;
					$sourceVideoProps = explode('|', $sourceVideoPropsTxt);
					if (sizeof($sourceVideoProps)) {
						$clipData['aspectRatio'] = $sourceVideoProps[0];
					}
				}
				
				$clipData['description'] = $video->getElementsByTagName('description')->item(0)->textContent;

				$msg = '';
				$createParams = array('addlCategories'=>$addlCategories, 'debug'=>$debug);
				$articlesCreated += $this->createVideo($clipData, $msg, $createParams);
				if ($msg) {
					print "ERROR: $msg\n";
				}
			}
		}
		while ($numVideos == self::$API_PAGE_SIZE);
		
		return $articlesCreated;
	}

	private function initFeedUrl($keyword, $startDate, $endDate, $page=1) {
		global $wgRealgravityApiKey;
		
		$url = str_replace('$1', $wgRealgravityApiKey, self::$API_VIDEOS_URL);
		$url = str_replace('$2', self::$API_PROVIDER_IDS['MACHINIMA'], $url);
		$url = str_replace('$3', urlencode($keyword), $url);
		$url = str_replace('$4', self::$API_PAGE_SIZE, $url);
		$url = str_replace('$5', $page, $url);
		if ($startDate && $endDate) {
			$url .= '&date_range=' . $startDate . '..' . $endDate;
		}
		return $url;
	}
	
	protected function generateName(array $data) {
		$name = $data['clipTitle'];
		$name = VideoHandlersUploader::sanitizeTitle($name);

		return $name;		
	}
	
	protected function generateTitleName(array $data) {
		// not relevant because there are no movies/TV shows from
		// our RealGravity providers		
		return '';
	}
	
	protected function generateCategories(array $data, $addlCategories) {
		$categories = !empty($addlCategories) ? $addlCategories : array();
		$categories[] = 'RealGravity';
		$categories[] = 'Games';
		
		return $categories;		
	}
	
	protected function generateParsedData(array $data, &$errorMsg) {
		$parsedData = array(
		    'thumbnail'		=> $data['thumbnail'],
		    'duration'		=> $data['duration'],
		    'published'		=> $data['published'],
		    'category'		=> $data['category'],
		    'keywords'		=> $data['keywords'],
		    'aspectRatio'	=> $data['aspectRatio'],
		    'description'	=> $data['description']
		    );
		
		return $parsedData;
	}
}