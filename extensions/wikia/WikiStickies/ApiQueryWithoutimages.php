<?

if (!defined('MEDIAWIKI')) {
        // Eclipse helper - will be ignored in production
        require_once ('ApiQueryBase.php');
}

class ApiQueryWithoutimages extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent::__construct($query, $moduleName, 'woi');
	}

	public function execute() {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$this->addTables( 'querycache' );
		$this->addFields( array( 'qc_title', 'qc_namespace' ) );
		$this->addWhereFld( 'qc_type', 'Withoutimages' );
		$this->addOption( 'ORDER BY', 'qc_value DESC' );
		$this->addOption( 'LIMIT', $params['limit'] + 1 );		

		$res = $this->select(__METHOD__);
		$count = 0;
		$result = $this->getResult();

		while( $row = $db->fetchObject( $res ) ) {
			if (++$count > $params['limit']) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('continue', $row->qc_title );
				break;
			}
			$vals['title'] = $row->qc_title;
			$vals['namespace'] = $row->qc_namespace;

			$fit = $this->getResult()->addValue(array('query', $this->getModuleName()), null, $vals);
			if(!$fit) {
				break;
			}
		}
		$db->freeResult( $res );
		$this->getResult()->setIndexedTagName_internal(array('query', $this->getModuleName()), 'page');
	}

	public function getParamDescription() {
		return array(
				'limit' => 'Limit how many pages without images will be returned'
			    );
	}

	public function getDescription() {
		return "Returns given number of pages without images";
	}

	public function getAllowedParams() {
		return array (
				'limit' => array (
					ApiBase :: PARAM_TYPE => 'limit',
					ApiBase :: PARAM_DFLT => 5,
					ApiBase :: PARAM_MIN => 1,
					ApiBase :: PARAM_MAX => 50,
					ApiBase :: PARAM_MAX2 => 100
					),
			     );
	}

	protected function getExamples() {
		return 'api.php?action=query&list=withoutimages&woilimit=5';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryWithoutimages.php$';
	}
}

