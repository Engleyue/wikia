/**
 * Javascript unit test runner based on phantomJS
 *
 * @author Jacek "mech" Wozniak <jacek.wozniak(at)wikia-inc.com>
 * @author Federico "Lox" Lucignano <federico(at)wikia-inc.com>
 * @author Sebastiam Marzjan <sebastian.marzjam(at)wikia-inc.com>
 * @author Michał "Mix" Roszka <michal(at)wikia-inc.com>
 *
 * @example phantomjs run-test.js ../path/to/test.js (runs single test)
 */

var RUNNER_TEMP_PATH = '/tmp/run-test.js.' + (new Date()).getTime() + '.html',	
	EXIT_SIGNAL = 'PHANTOM_EXIT',
	DEPENDENCIES_PLACEHOLDER = '<!--DEPENDENCIES-->',
	DEFAULT_USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.163 Safari/535.19',
	DEFAULT_VIEWPORT_WIDTH = 1024,
	DEFAULT_VIEWPORT_HEIGHT = 768,
	SCRIPT_TIMEOUT = 30000,
	SCRIPT_TEMPLATE = '<script type="text/javascript" src="$1"></script>\n',
	CSS_TEMPLATE = '<link type="text/css" rel="$1" />\n',
	fs = require('fs'),
	sys = require('system'),
	cliOptionRegex = new RegExp('-{1,2}', 'gi'),
	decoratorOptionRegex = new RegExp('@test-([^\n]+)', 'gi'),
	includeTestFileRegex = new RegExp('^.*\\/tests\\/[^\\/]+\\.js$'),
	excludeTestFileRegex = new RegExp('^\\.\\.\\/tests\\/.*\\.js$'),
	jsFileRegex = new RegExp('^(.*)\\.js$'),
	cssFileRegex = new RegExp('^(.*)\\.(css|sass|scss)$'),
	tests = [],
	testResults = [],
	testResult,
	options = {
		'user-agent': DEFAULT_USER_AGENT,
		'screen-resolution': {
			width: DEFAULT_VIEWPORT_WIDTH,
			height: DEFAULT_VIEWPORT_HEIGHT
		},
		params: [],
		output: '/tmp'
	},
	optionsCounter = 0,
	timer,
	page;

phantom.injectJs('lib/js/JTR.js');
phantom.injectJs('lib/js/TestResult.js');

function exit(retVal) {
	retVal = (retVal == 1) ? 1 : 0;

	try {
		fs.remove(RUNNER_TEMP_PATH);
	} catch(e) {}

	if(tests.length){
		if(retVal == 1)
			console.error(stylize('[ERROR]', 'lightred'), 'aborting test, running next one.');

		processTest(tests.pop());
	}else
		phantom.exit(retVal);
}

function scanDirectory(path, output, callback) {
	if (fs.isDirectory(path)) {
		fs.list(path).forEach(function (e) {
			if (e !== "." && e !== "..") {
				scanDirectory(path + '/' + e,output,callback)
			}
		});
	} else if (fs.exists(path) && fs.isFile(path)) {
		if(callback(path)) {
			output.push(path);
		}
	}
}

function onPageLoaded(status) {
	(typeof timer != 'undefined') && clearTimeout(timer);

	timer = setTimeout(function () {
		console.error('Maximum execution time exceeded, aborting.');
		exit(1);
	}, SCRIPT_TIMEOUT);
}

function processTest(test) {
	var testSource = fs.read(test),
		requiredFiles = [],
		deps = '',
		testOptions = {
			'require-file': [],
			'screen-resolution': options['screen-resolution'],
			'user-agent': options['user-agent']
		},
		runner,
		matches,
		htmlTemplate;

	//process test decorator options
	if((matches = testSource.match(decoratorOptionRegex)) !== null){
		for(var x = 0, y = matches.length; x < y; x++){
			var tokens = matches[x].replace(/^@test-/i, '').split(' ', 2),
				t = tokens[0];

			if(t == 'exclude'){
				console.log(stylize('[WARN]', 'yellow'), 'Test excluded, skipping...');
				exit(0);
				return;
			}else if(t == 'require-file' && tokens[1]){
				testOptions[t].push(tokens[1]);
			}else if(t == 'screen-resolution' && tokens[1]){
				var res = tokens[1].split('x', 2);
	
				testOptions[t] = {
					width: res[0],
					height: res[1]
				};
			}else{
				testOptions[tokens[0]] = tokens[1] || true;
			}
		}
	}

	if(testOptions.framework) {
		switch(testOptions.framework.toLowerCase()) {
			case 'qunit':
				requiredFiles.push('lib/qunit/qunit.js');
				requiredFiles.push('lib/qunit/console_reporter.js');
				runner = 'lib/qunit/test-runner.html';
				break;
			case 'jasmine':
				requiredFiles.push('lib/jasmine/jasmine.js');
				requiredFiles.push('lib/jasmine/console_reporter.js');
				runner = 'lib/jasmine/test-runner.html';
				break;
		}
	}

	if(!runner) {
		console.error(stylize('[ERROR]', 'lightred'), 'Missing or unrecognized framework declaration.');
		exit(1);
		return;
	}

	if(testOptions['require-file'] instanceof Array){
		testOptions['require-file'].forEach(function(item){
			scanDirectory('../' + item, requiredFiles, function(arg) {return true;});
		});
	}

	if(testOptions['screen-resolution']){
		page.viewportSize = testOptions['screen-resolution'];
	}else{
		page.viewportSize = options['screen-resolution'];
	}

	if(testOptions['user-agent']){
		page.settings.userAgent = testOptions['user-agent'];
	}else{
		page.settings.userAgent = options['user-agent'];
	}

	requiredFiles.push(test);

	requiredFiles.forEach(function (item) {
		if (item.match(jsFileRegex)) {
			deps += SCRIPT_TEMPLATE.replace('$1', fs.absolute(item));
		} else if (item.match(cssFileRegex)) {
			deps += CSS_TEMPLATE.replace('$1', fs.absolute(item));
		}
	});

	htmlTemplate = fs.read(runner).replace(DEPENDENCIES_PLACEHOLDER, deps);
	fs.write(RUNNER_TEMP_PATH, htmlTemplate, 'w');
	testResult = new TestResult(test);
	page.open(RUNNER_TEMP_PATH, onPageLoaded);
}

//commandline options processing
sys.args.forEach(function(item){
	if(++optionsCounter === 1)
		return;

	if(item.indexOf('-') === 0){
		//option
		var tokens = item.replace(/^-{1,2}/g, '').split('=', 2),
			t = tokens[0];

		if(t == 'screen-resolution' && tokens[1]){
			var res = tokens[1].split('x', 2);

			options[t] = {
				width: res[0],
				height: res[1]
			};
		}else
			options[tokens[0]] = tokens[1] || true;
	}else{
		//param
		options.params.push(item);
	}
});

if(!options.params.length){
	scanDirectory('..',tests,function(arg) {
		return (!excludeTestFileRegex.test(arg) && includeTestFileRegex.test(arg));
	});

	tests.reverse();

}else{
	options.params.forEach(function(item){
		tests.push(item);
	});
}

function stylize(str, style) {
	  var styles = {
	  //styles
	  'bold'      : [1,  22],
	  'italic'    : [3,  23],
	  'underline' : [4,  24],
	  'inverse'   : [7,  27],
	  //grayscale
	  'white'     : [37, 39],
	  'grey'      : [90, 39],
	  'black'     : [90, 39],
	  //colors
	  'blue'      : [34, 39],
	  'cyan'      : [36, 39],
	  'green'     : [32, 39],
	  'magenta'   : [35, 39],
	  'red'       : [31, 39],
	  'lightred'       : ['1;31', '0;39'],
	  'yellow'    : [33, 39]
	  };
	  return '\033[' + styles[style][0] + 'm' + str +
	         '\033[' + styles[style][1] + 'm';
	};


phantom.injectJs('lib/js/Xml.js');
phantom.injectJs('lib/js/JUnitReport.js');

function outputTestsResult() {
	/*
		var suiteData = {
				name: suiteName,
				'package': 'com.wikia.javascript.tests',
				errors: suite.stats[JTR.status.ERROR],
				failures: suite.stats[JTR.status.FAILURE],
				skipped: suite.stats[JTR.status.SKIPPED],
				tests: suite.stats.total,
				id: suiteId,
				time: suite.stats.time / 1000
		};
		var accumKeys = ['errors','failures','skipped','tests','time'];
		for (var i=0;i<accumKeys.length;i++) {
			wrapperData[accumKeys[i]] += suiteData[accumKeys[i]];
		}
	 */
	var errors = 0, failures = 0, skipped = 0, tests = 0, time = 0.0;
	
	for(var i = 0 ; i < testResults.length ; i++) {
		if (options.output) {
			var xml = JUnitReport.getXml(testResults[i]);
			var fileName = options.output + '/cc_js_' + new Date().getTime() + '_' + i + '.xml';
			fs.write(fileName, xml, 'w');
		}
		var testResult = testResults[i];
		console.log('Running ' + stylize(testResult.name, 'bold'));
		
		for (var suiteName in testResult.suites) {
			console.log(stylize(suiteName, 'bold'));
			var suite = testResult.suites[suiteName];
			errors += suite.stats[JTR.status.ERROR];
			failures += suite.stats[JTR.status.FAILURE];
			skipped += suite.stats[JTR.status.SKIPPED];
			tests += suite.stats.total;
			time += suite.stats.time / 1000;
			for (var testName in suite.tests) {
				var test = suite.tests[testName];
				if (test.status == JTR.status.SUCCESS) {
					var assertions = '1 assertion';
					if (test.assertions > 1) {
						assertions = test.assertions + ' assertions';
					} else {
						assertions = stylize('no assertions', 'yellow');
					}
					console.log('\t'+testName+'\t'+stylize('[OK]', 'green') + ' (' + assertions + ')');	
				} else {
					console.log('\t'+testName+'\t'+stylize('[FAIL]', 'lightred'));
					if (test.messages) {
						var messages = test.messages.split('\n');
						for(var j = 0 ; j < messages.length ; j++) {
							console.log('\t\t' + messages[j]);
						}
					}
				}
			}
		}
	}
	
	var status = (errors + failures) == 0;
	
	var passed = tests - errors - failures - skipped;
	if (passed > 0) passed = stylize(passed + ' passed', 'green');
	else passed = '0 passed';
	
	if (errors == 1) errors = stylize('1 error', 'lightred');
	else if (errors > 1) errors = stylize(errors + ' errors', 'lightred');
	else errors = '0 errors';
	
	if (failures == 1) failures = stylize('1 failure', 'lightred');
	else if (failures > 1) failures = stylize(failures + ' failures', 'lightred');
	else failures = '0 failures';

	if (skipped > 0) skipped = ' ('+stylize(skipped + ' skipped', 'yellow')+')';
	else skipped = '';
	console.log('Ran ' + tests + ' tests with '+passed + ' and ' +failures+' and '+errors+skipped+' in ' + Math.round(time*100)/100 + ' seconds');
	
	return status;
}

page = require('webpage').create({
	onConsoleMessage : function(msg) {
		try {
			msg = JSON.parse(msg);
			switch(msg.command) {
			case 'startTest':
				testResult.startTest(msg.name, msg.extra);
				break;
			case 'stopTest':
				testResult.stopTest(JTR.status[msg.status], msg.assertions, msg.messages);
				break;
			case 'startSuite':
				testResult.startSuite(msg.name, msg.extra);
				break;
			case 'stopSuite':
				testResult.stopSuite();
				break;
			case EXIT_SIGNAL:
				testResults.push(testResult);
				testResult = null;
				if(tests.length){
					processTest(tests.pop());
				}else {
					var result = outputTestsResult();
					exit( (result) ? 0 : 1 );
					return;
				}
				break;
			}
			return;
		}catch(e){};
	},

	onError : function(msg, trace) {
		console.error('Error:', msg);

		trace.forEach(function(item) {
			console.error('\t-', item.file, ':', item.line);
		});
	},

	settings : {
		loadPlugins : true,
		localToRemoteUrlAccessEnabled : true,
		XSSAuditingEnabled : true,
		userAgent : options['user-agent']
	},

	viewportSize : options['screen-resolution']
});

if(tests.length){
	processTest(tests.pop());
}else{
	console.error(stylize('[ERROR]', 'lightred'), 'No tests found');
	exit(1);
}