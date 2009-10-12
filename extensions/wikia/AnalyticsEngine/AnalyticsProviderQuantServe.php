<?php

class AnalyticsProviderQuantServe implements iAnalyticsProvider {

	private $account = 'p-8bG6eLqkH6Avk';

	function getSetupHtml(){
		static $called = false;
		if ($called == true){
			return '';
		} else {
			$called = true;
		}

		return  '<script type="text/javascript" src="http://edge.quantserve.com/quant.js"></script>' . "\n" .
			"<script type=\"text/javascript\">/*<![CDATA[*/
			try {
				_qoptions = { qacct: '{$this->account}' };
				// Hard code some hosts per Gil
				if (window.location.hostname == 'answers.wikia.com') { 
					_qoptions.labels = 'lifestyles,mom';
				} else if (window.location.hostname == 'recipes.wikia.com'){
					_qoptions.labels = 'lifestyles,mom';
				} else {
					_qoptions.labels = Athena.getPageVar('hub');
					for (var i = 0; i < ProviderValues.list.length; i++){
						_qoptions.labels += ',' + Athena.getPageVar('hub') + '.' + ProviderValues.list[i].value;
					}
				}
			} catch (e){
				// Fall back to old way.
				_qacct=\"{$this->account}\";
			}
			/*]]>*/</script>";
	}

	function trackEvent($event, $eventDetails=array()){
		switch ($event){
		  case AnalyticsEngine::EVENT_PAGEVIEW : return '<script type="text/javascript">quantserve();</script>';
                  default: return '<!-- Unsupported event for ' . __CLASS__ . ' -->';
		}
	}


}
