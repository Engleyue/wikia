<?php 

/* smart proxy to article comment */

class WallMessage {
	protected $articleComment;
	protected $title;
	
	function __construct(Title $title, $articleComment = null) {
		$this->title = $title;
		$this->articleComment = $articleComment;
	}
	
	static public function newFromTitle(Title $title) {
		$class = new WallMessage( $title );
		return $class;
	}
	
	static public function newFromArticleComment(ArticleComment $articleComment) {
		$class = new WallMessage( $articleComment->getTitle(), $articleComment );
		return $class;
	}
	
	public function canEdit(User $user){
		return $this->isAuthor($user) || $user->isAllowed('walledit');
	}

	public function canDelete(User $user){
		return $this->isWallOwner($user) || $user->isAllowed('walldelete');
	}	
	 
	public function getMetaTitle(){
		return $this->getArticleComment()->getMetadata('title');
	}
	
	public function getWallOwner() {
		$wall_owner = User::newFromName($this->getArticleComment()->getArticleTitle()->getBaseText(), false);
		if(empty($wall_owner)) {
			error_log('EMPTY_WALL_OWNER: (id)'. $this->getArticleComment()->getArticleTitle()->getArticleID());
			error_log('EMPTY_WALL_OWNER: (basetext)'. $this->getArticleComment()->getArticleTitle()->getBaseText());
			error_log('EMPTY_WALL_OWNER: (fulltext)'. $this->getArticleComment()->getArticleTitle()->getFullText());
		}
		return $wall_owner;
	}
	
	public function getArticleTitle(){ 
		return $this->getArticleComment()->getArticleTitle();
	}
	
	public function getMessagePageUrl() {
		return $this->getWallUrl().'/'.$this->getArticleComment()->getTitle()->getArticleId();
	}
	
	public function getWallUrl() {
		return $this->getArticleComment()->getArticleTitle()->getFullUrl();
	}
	
	public function getTopParentObj(){
		$obj = $this->getArticleComment()->getTopParentObj();
		
		if(empty($obj)) {
			return null;
		}
		if($obj instanceof ArticleComment)
			return WallMessage::newFromArticleComment($obj);
		else
			return null;
	}
	
	public function isWallOwner(User $user) {
		$wallUser = $this->getWallOwner();
		if(empty($wallUser)) {
			return false;
		}
		
		return $wallUser->getId() == $user->getId();
	}
	
	public function load($master = false) {
		return $this->getArticleComment()->load($master);
	}
	
	public function getUser(){
		return $this->getArticleComment()->mUser;
	}
	
	public function getData(User $user) {
		$data = $this->getArticleComment()->getData();
		return $data; 
	}
	
	public function isAuthor(User $user){
		return $this->getArticleComment()->isAuthor($user);
	}
	
	public function isWatched(User $user) {
		return $user->isWatched($this->title);
	}
	
	public function addWatch(User $user) {
		$user->addWatch($this->title);		
	}
	
	public function removeWatch(User $user) {
		$user->removeWatch($this->title);
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function doDeleteComment( $reason, $suppress = false ){
		return $this->getArticleComment()->doDeleteComment( $reason, $suppress );
	}
	
	protected function getArticleComment() {
		if(empty($this->articleComment)) {
			$this->articleComment = ArticleComment::newFromTitle($this->title);
		}
		return $this->articleComment;
	}
}