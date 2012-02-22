<?php
/**
 * WikiaMobile public API
 *
 * @author Federico "Lox" Lucignano <federico(at)wikia-inc.com>
 */
class WikiaMobileController extends WikiaController{
	/**
	 * Fetches the requested batch for a specific index
	 * section in a category page
	 *
	 * @requestParam string $category The name of the category
	 * @requestParam string $index The index representing a section in the category's page
	 * @requestParam int $batch The number of the batch of items to load
	 *
	 * @responseParam array $batch
	 */
	public function getCategoryBatch(){
		//allow proxying request to a service (shared method implementation)
		$this->request->setInternal( true );
		$this->forward( 'WikiaMobileCategoryService', 'getBatch' );
	}
	
	public function getShareButtons(){
		$this->response->setVal('scripts', 'alert("asd");');
		$this->response->setVal('pageShare', $this->wf->RenderPartial('WikiaMobile', 'getSharePageButtons'));
		$this->response->setVal('imageShare', $this->wf->RenderPartial('WikiaMobile', 'getShareImageButtons'));
	}
}