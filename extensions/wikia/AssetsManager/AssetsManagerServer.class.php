<?php

/**
 * @author Inez Korczyński <korczynski@gmail.com>
 */

class AssetsManagerServer {

	public static function serve(WebRequest $request) {

		try {
			switch($request->getText('type')) {
				case 'one':
					$builder = new AssetsManagerOneBuilder($request);
					break;

				case 'group':
					$builder = new AssetsManagerGroupBuilder($request);
					break;

				case 'groups':
					$builder = new AssetsManagerGroupsBuilder($request);
					break;

				case 'sass':
					$builder = new AssetsManagerSassBuilder($request);
					break;

				default:
					Wikia::log(__METHOD__, false, "Unknown type: {$_SERVER['REQUEST_URI']}", true /* $always */);
					Wikia::log(__METHOD__, false, AssetsManager::getRequestDetails(), true /* $always */);
					throw new Exception('Unknown type.');
			}

		} catch (Exception $e) {
			// HTTP 501 is not "grabbed" by the Varnish
			header('HTTP/1.1 501 Not Implemented');
			echo $e->getMessage();
			return;
		}

		$headers = array();
		$headers['Vary'] = 'Cookie,Accept-Encoding';

		if($builder->getContentType()) {
			$headers['Content-Type'] = $builder->getContentType();
		}

		$cacheDuration = $builder->getCacheDuration();
		if($cacheDuration > 0) {
			$headers['Expires'] = gmdate('D, d M Y H:i:s \G\M\T', strtotime($cacheDuration . ' seconds'));
			$headers['X-Pass-Cache-Control'] = $builder->getCacheMode() . ', max-age=' . $cacheDuration;
			$headers['Cache-Control'] = $builder->getCacheMode() . ', max-age=' . $cacheDuration;
		}

		$headers['Last-Modified'] = gmdate('D, d M Y H:i:s \G\M\T');

		foreach($headers as $k => $v) {
			header($k . ': ' . $v);
		}

		echo $builder->getContent();
	}
}