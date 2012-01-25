/*  ============================================
    == SIMPLAZA.NET JS COMMON LIBRARY ==========
    Version: v0.2

    by The Major / Crome Tysnomi / Ayman Habayeb
    http://gnu32.deviantart.com
    ============================================ */

/*  ============================================
    == CONVENTION WARNING !!! ==================
	For-in loops on any object, including arrays,
	should be implemented using the .walk() function
	and a custom walking function as this library
	adds custom methods and properties to the Object
	class that fucks up For-in loops big time.

	You may use .hasOwnProperty, but it's generally
	cleaner to define your own walk function.
	============================================ */

// ================
// GLOBAL CONSTANTS
// ================
var N = "\n";
var T = "\t";
var BR = '<br />';

// ========================
// GLOBAL UTILITY FUNCTIONS
// ========================
// Inspired by jQuery; an easier function for getting an element:
function $(name) {
	return document.getElementById(name);
}

function r(mag) {
	return Math.floor(Math.random() * mag);
}

function pick(v1,v2) {
	return v1 ? v1 : v2;
}

function choose(v1, v2, favor) {
	var rand = Math.floor( Math.random() * 100) + 1;
	var favor = typeof(favor) != 'undefined' ? favor : 50;

	return rand <= favor ? v1 : v2;
}

function chooseBool(favor) {
	var rand = Math.floor( Math.random() * 100) + 1;
	var favor = typeof(favor) != 'undefined' ? favor : 50;

	return rand <= favor ? true : false;
}
function choose_bool(favor) { return chooseBool(favor); }

function rand(low, high) {
	return Math.floor( Math.random() * (high + 1 - low)) + low;
}

function ajax(url, callback, data) {
	var ajax_obj = new XMLHttpRequest();
	ajax_obj.open('GET', url, true);
	ajax_obj.send(null);

	ajax_obj.onreadystatechange = function() {
		if (this.readyState == 4) {
			if ( this.status == 200 ) callback(this.responseText, data);
            if ( this.status == 404 ) console.error("AJAX call to "+url+" failed with 404.");
		}
	}

	return ajax_obj;
}

// DEPRECATED
function hasClass(dom,classname) {
	if (dom.className.indexOf(classname) > -1)
		return true;
	else
		return false;
}

// DEPRECATED
function addClass(dom,classname) {
	if ( !hasClass(dom,classname) ) {
		var classes = dom.className.split(' ');
		classes.push(classname);

		dom.className = classes.join(' ');
	}

    return dom;
}

// DEPRECATED
function delClass(dom,classname) {
	dom.className = dom.className.replace(classname,'');
    return dom;
}

// DEPRECATED
function toggleClass(dom,classname) {
	if (hasClass(dom,classname))
		delClass(dom,classname);
	else
		addClass(dom,classname);

    return dom;
}

// ======================
// ELEMENT/DOM EXTENSIONS
// ======================

// These currently wrap the deprecated class standalone functions
Element.prototype.hasClass = function(classname) { return hasClass(this, classname); }
Element.prototype.addClass = function(classname) { return addClass(this, classname); }
Element.prototype.delClass = function(classname) { return delClass(this, classname); }
Element.prototype.toggleClass = function(classname) { return toggleClass(this, classname); }

Node.prototype.hasAncestorID = function (id) {
    var pointer = this;
    while (pointer.parentNode) {
        if (pointer.parentNode.id == id) return true;
        pointer = pointer.parentNode;
    }

    return false;
}

Node.prototype.hasAncestorNode = function (dom) {
    var pointer = this;
    while (pointer.parentNode) {
        if (pointer.parentNode == dom) return true;
        pointer = pointer.parentNode;
    }

    return false;
}


// =================
// OBJECT EXTENSIONS
// =================
Object.prototype.pointer = 0;

Object.prototype.next = function() {
	var l = this.pointer;
	this.pointer++;

	if (this.pointer == this.length)
		this.pointer = 0;

	return this[l];
}

Object.prototype.pull = function(needle) {
	for (var i = 0; i < this.length; i++) {
		// If we find the element, splice it out and exit
		if (this[i] == needle) {
			this.splice(i,1);
			break;
		}
	}
}

Object.prototype.random = function() {
	var l = this.length;
	l = r(l);

	return this[l];
}

Object.prototype.walk = function(func) {
	for (key in this) {
		if ( this.hasOwnProperty(key) )
			func(this[key], key, arguments);
	}
}

Object.prototype.empty = function() {
    return (this.length == 0) ? true : false;
}

Object.prototype.hasAncestorByProperty = function(property, value) {
    var pointer = this;
    while (pointer[property]) {
        if (pointer[property] == value) return true;
        pointer = pointer[property];
    }

    return false;
}
// ================
// COOKIE FUNCTIONS
// ================

// Read cookie (thanks http://www.quirksmode.org/js/cookies.html)
function cookieRead(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');

	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}

	return null;
}

function cookieWrite(name, data) {
	var date = new Date();

	date.setFullYear(date.getFullYear() + 5);
	document.cookie = name + '=' + data.toString() +'; expires=' + date.toUTCString() + '; path=/';
}

function cookieDelete(name) {
	document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/';
}

function isCookie(name) {
    if ( document.cookie.indexOf(name+'=') != -1 )
        return true;
    else
        return false;
}

function cookiesEnabled() {
    cookieWrite('commonCookieTest','test');

    if ( isCookie('commonCookieTest') ) {
        cookieDelete('commonCookieTest');
        return true;
    } else {
        return false;
    }
}



