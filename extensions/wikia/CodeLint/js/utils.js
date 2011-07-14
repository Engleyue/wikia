/**
 * Parses script arguments provided as
 *
 *  --foo=bar --file=/home/foo/bar
 *
 * to an key-value object
 */
exports.parseArgs = function(argv) {
	var args = {},
		argsRegExp = /--([^=]+)=(.*)/;

	for (var i=2, len = process.argv.length; i<len; i++) {
		var parsedLine = String.prototype.match.call(process.argv[i], argsRegExp);

		if (parsedLine) {
			args[parsedLine[1]] = parsedLine[2];
		}
	}

	return args;
}
