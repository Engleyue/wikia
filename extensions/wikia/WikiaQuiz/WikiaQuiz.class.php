<?php

/**
 * This class represents a quiz: a set of WikiaQuizElements (question and answers)
 */

class WikiaQuiz {
	private $mCategory;
	private $mData;
	private $mExists;
	private $mMemcacheKey;
	private $mName;
	private $mQuizId;

	const CACHE_TTL = 86400;
	const CACHE_VER = 5;
	const QUIZ_CATEGORY_PREFIX = 'Quiz_';
	const TITLESCREENTEXT_MARKER = 'TITLESCREENTEXT:';
	const IMAGE_MARKER = 'IMAGE:';

	private function __construct($quizId) {
		$this->mData = null;
		$this->mExists = false;
		$this->mMemcacheKey = wfMemcKey('quiz', 'data', $quizId, self::CACHE_VER);
		$this->mQuizId = $quizId;

		wfDebug(__METHOD__ . ": quiz #{$quizId}\n");
	}

	/**
	 * Return instance of WikiaQuiz for given quiz ID
	 */
	static public function newFromId($id) {
		return $id ? new self($id) : null;
	}

	/**
	 * Return instance of WikiaQuiz for given article from Quiz namespace
	 */
	static public function newFromArticle(Article $article) {
		$id = $article->getID();
		return self::newFromId($id);
	}

	/**
	 * Return instance of WikiaQuiz for given title from Quiz namespace
	 */
	static public function newFromTitle(Title $title) {
		$id = $title->getArticleId();
		return self::newFromId($id);
	}

	/**
	 * Load quiz data (try to use cache layer)
	 */
	private function load($master=false) {
		global $wgMemc;
		wfProfileIn(__METHOD__);

		if (!$master) {
			//@todo use memcache
			//$this->mData = $wgMemc->get($this->mMemcacheKey);
		}

		if (empty($this->mData)) {
			$article = Article::newFromID($this->mQuizId);

			// check quiz existence
			if (empty($article)) {
				wfDebug(__METHOD__ . ": quiz doesn't exist\n");
				wfProfileOut(__METHOD__);
				return;
			}

			// get quiz's author and creation timestamp
			$title = $article->getTitle();
			$firstRev = $title->getFirstRevision();
			$titleText = $title->getText();
			$titleScreenText = '';
			$images = array();
			$imageShorts = array();

			// parse wikitext containing quiz data
			$content = $article->getContent();

			$lines = explode("\n", $content);
			
			foreach($lines as $line) {
				if (startsWith($line, self::TITLESCREENTEXT_MARKER)) {
					$titleScreenText = trim( substr($line, strlen(self::TITLESCREENTEXT_MARKER)) );
				}
				elseif (startsWith($line, self::IMAGE_MARKER)) {
					$imageShort = trim( substr($line, strlen(self::IMAGE_MARKER)) );
					$images[] = $this->getImageSrc($imageShort);					
					$imageShorts[] = $imageShort;
				}
			}

			// load quiz's elements
			if (empty($this->mCategory)) {
				$catName = self::QUIZ_CATEGORY_PREFIX . $titleText;
				$cat = F::build('Category', array($catName), 'newFromName');
				$this->mCategory = $cat;
			}

			$quizElements = array();
			if (empty($this->mCategory) || !$this->mCategory->getID()) {
				wfDebug(__METHOD__ . ": quiz's category doesn't exist\n");
			}
			else {
				// get quiz elements
				$quizIterator = $this->mCategory->getMembers();
				while ($quizElementTitle = $quizIterator->current()) {
					$quizElement = WikiaQuizElement::newFromId($quizElementTitle->getArticleId());
					$quizElements[] = $quizElement->getData();
					$quizIterator->next();
				}				
			}

			$this->mData = array(
				'id' => $this->mQuizId,
				'name' => $titleText,
				'elements' => $quizElements,
				'titlescreentext' => $titleScreenText,
				'images' => $images,
				'imageShorts' => $imageShorts
			);

			wfDebug(__METHOD__ . ": loaded from scratch\n");

			// store it in memcache
			//@todo use memcache
			//$wgMemc->set($this->mMemcacheKey, $this->mData, self::CACHE_TTL);
		}
		else {
			wfDebug(__METHOD__ . ": loaded from memcache\n");
		}

		$this->mExists = true;
		
		wfProfileOut(__METHOD__);
		return;
	}

	public function getId() {
		return $this->mQuizId;
	}

	/**
	 * Get quiz's data
	 */
	public function getData() {
		if (is_null($this->mData)) {
			$this->load();
		}

		return $this->mData;
	}

	/**
	 * Get quiz's name (does not include the mandatory category prefix)
	 */
	public function getName() {
		if (is_null($this->mData)) {
			$this->load();
		}
		return $this->mName;
	}
	
	/**
	 * Get quiz's title (does not include the mandatory category prefix)
	 */
	public function getTitle() {
		if (is_null($this->mData)) {
			$this->load();
		}
		return $this->mData['name'];
	}

	
	/**
	 * Get quiz's elemeents
	 */
	public function getElements() {
		if (is_null($this->mData)) {
			$this->load();
		}
		return $this->mData['elements'];
	}
	
	/**
	 * Return true if current quizElement exists
	 */
	public function exists() {
		if (is_null($this->mData)) {
			$this->load();
		}

		return $this->mExists === true;
	}

	/**
	 * Render HTML for Quiz page
	 */
	public function render() {
		return wfRenderModule('WikiaQuiz', 'Index', array('quiz' => $this));
	}

	/**
	 * Purges memcache entry
	 */
	public function purge() {
		global $wgMemc;
		wfProfileIn(__METHOD__);

		// clear data cache
		$wgMemc->delete($this->mMemcacheKey);
		$this->mData = null;

		$article = Article::newFromId($this->mQuizId);
		if (!empty($article)) {
			// purge quiz page
			$article->doPurge();

			// apply changes to page_touched fields
			$dbw = wfGetDB(DB_MASTER);
			$dbw->commit();
		}

		wfDebug(__METHOD__ . ": purged quiz #{$this->mQuizId}\n");

		wfProfileOut(__METHOD__);
	}
	
	//@todo refactor this function and the version in WikiaQuizElement.class
	private function getImageSrc($filename) {
		$imageSrc = '';
		$fileTitle = Title::newFromText($filename, NS_FILE);
		$image = wfFindFile($fileTitle);
		if ( !is_object( $image ) || $image->height == 0 || $image->width == 0 ){
			return $imageSrc;
		} else {
			$thumbDim = ($image->height > $image->width) ? $image->width : $image->height;
			$imageServing = new imageServing( array( $fileTitle->getArticleID() ), $thumbDim, array( "w" => $thumbDim, "h" => $thumbDim ) );
			$imageSrc = wfReplaceImageServer(
				$image->getThumbUrl(
					$imageServing->getCut( $thumbDim, $thumbDim )."-".$image->getName()
				)
			);
		}

		return $imageSrc;
	}
}
