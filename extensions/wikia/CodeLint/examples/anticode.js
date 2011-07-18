/**
 * This is an example of JS code full on antipatterns
 * and other things you should avoid in JavaScript
 */

function foo() {
	bar = 12;

	return
	{
		retVal: bar
	}
}

function theFunction() {
	var foo = 1;
	return foo;

	// unreachable code
	foo++;
	return foo;
}

console.log(bar);

var obj = new Object(),
	arr = new Array(),
	collection = {
		abc: true,
		foo: false,
	}

alert(collection);

var a = new Function(),
	b = setTimeout('foo', 100),
	c = setInterval('foo', 500);
	
// @see http://www.ibm.com/developerworks/web/library/wa-memleak/
document.write("Circular references between JavaScript and DOM!");
function myFunction(element)
{
	this.elementReference = element;
	// This code forms a circular reference here
	//by DOM-->JS-->DOM
	element.expandoProperty = this;
}
function Leak() {
	//This code will leak
	new myFunction(document.getElementById("myDiv"));
}