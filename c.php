<?php
/*  ============================================
    == SIMPLAZA.NET PHP COMMON LIBRARY =========
    Version: v0.3

    by The Major / Crome Tysnomi / Ayman Habayeb
    http://gnu32.deviantart.com
    ============================================ */

// =====
// USAGE
// =====

// * Simply copy and paste all this code into a text editor (not a rich-text editor like Word, Wordpad, Frontpage, etc) and save.
// * Use "include('/path/to/where/you/saved/common.php');" to include the library into your code.
// * If your server configuration allows it, you can "include('http://common.simplaza.net/php;');". Be warned that there is no guarantee this library will be remotely avaliable forever.

// =========
// PHP FIXES
// =========

if ( get_magic_quotes_gpc() ) {
	foreach ($_GET as $key => &$val) $val = stripslashes($val);
	foreach ($_POST as $key => &$val) $val = stripslashes($val);
	foreach ($_COOKIE as $key => &$val) $val = stripslashes($val);
}

// ================
// GLOBAL VARIABLES
// ================

// ===== STRING HELPER CONSTANTS

define('N',   "\n");       // New lines
define('T',   "\t");       // Tab spaces
define('TT',  "\t\t");     // Double tab spaces
define('Q',   "'");        // Single quotes
define('QQ',  '"');        // Double quotes
define('D', '.');          // Single dot
define('BR',  '<br />');   // HTML line break
define('HR',  '<hr />');   // HTML horizontal rule

// ===== SECONDS CONSTANTS

define('DAY',    60*60*24);
define('DAYS7',  DAY*7);
define('DAYS14', DAY*14);
define('DAYS21', DAY*21);
define('YEAR',   DAY*365);

// ===== BROWSER IDENTIFIERS

define('BROWSER_SAFARI', 1);
define('BROWSER_SAFARIMOBILE', 2);
define('BROWSER_FIREFOX', 3);
define('BROWSER_CHROME', 4);
define('BROWSER_IEXPLORER', 5);
define('BROWSER_OPERA', 6);

// ===== REGEXES

// REGEX_URL_PROTOCOLS - Snippet that enumeration of protocols
// REGEX_URL_HOST - Snippet that finds hostname or IP
// REGEX_URL - Finds a basic HTTP/HTTPS/FTP URL, base only
// Matches, with 'https://www.images.google.com/?q=test' as example:
// 0: https://www.images.google.com/
// 1: https://
// 2: https
// 3: www.
// 4: images.google.
// 5: com

define('REGEX_URL_PROTOCOLS',  '(https?|ftp|gopher)');
define('REGEX_URL_HOST',       '((?:[a-z0-9-_]+\.)+)([a-z]{2,10}|[0-9]{1,3})');
define('REGEX_URL',            '@('.REGEX_URL_PROTOCOLS.':\/\/)?(www.)?'.REGEX_URL_HOST.'\/?@i');

define('REGEX_IP',             '@([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})@i');

// REGEX_EMAIL - Finds a basic email address
// Matches, with 'major.rasputin@system.simplaza.net' as example:
// 0: major.rasputin@system.simplaza.net
// 1: major.rasputin
// 2: system.simplaza.
// 3: net

define('REGEX_EMAIL',          '@([a-z0-9\!\#\+\-\_\~\.]+)\@'.REGEX_URL_HOST.'@i');

// ================
// GLOBAL FUNCTIONS
// ================

// ===== FLOW CONTROL HELPERS

// pick(mixed $v1, mixed $v2) - Returns $v1 if it evaluates to true, else returns $v2
function pick($v1, $v2) {
    return $v1 ? $v1 : $v2;
}

// Randomly picks between two values, with optional value for favor
function choose($v1, $v2, $favor = 50) {
	$rand = rand(1,100);
	return $rand <= $favor ? $v1 : $v2;
}

// Randomly picks between true or false, with optional value for favor
function choose_bool($favor = 50) {
	$rand = rand(1,100);
	return $rand <= $favor ? TRUE : FALSE;
}

// iff(mixed $test, mixed $output) - If conditional that returns $output when $test is true, else returns NULL
function iff($test, $output) {
    return $test ? $output : NULL;
}

// ===== CONDITION TESTERS

// is_included(__FILE__) - Returns true if calling script has been included by another script, else returns false
// Example: A library, e.g. common.libs.php, can use is_included() to check if it's being directly accessed and return an error if it returns false.
function is_included($file) {
    return ( strtolower( realpath($file) ) != strtolower( realpath($_SERVER['SCRIPT_FILENAME']) ) ) ? true : false;
}

// ===== FORMATTING
// ===== WARNING: THE FOLLOWING FUNCTIONS ASSUME YOU ARE USING UTF-8 ENCODING ON YOUR PAGES

// html(string $text) - Shortcut function to sanitize text for HTML without screwing up on quotes or internalization
function html($text) { return htmlentities($text, ENT_QUOTES, 'UTF-8'); }

// un_html(string $text) - Shortcut function to desanitize text for HTML without screwing up on quotes or internalization
function un_html($text) { return html_entity_decode($text, ENT_QUOTES, 'UTF-8'); }

// ===== DIRECT DATA MODIFICATION
// ===== WARNING: THESE FUNCTIONS DIRECTLY MODIFY THE DATA IN A VARIABLE
// ===== FOR EXAMPLE: Using $test = 'lol', calling upper($test); will result in $test == 'LOL';

// lower(string &$string) - Shortcut that destructively changes a string to lower-case
function lower(&$string) { $string = strtolower($string); }

// upper(string &$string) - Shortcut that destructively changes a string to UPPER-CASE
function upper(&$string) { $string = strtoupper($string); }

// ===== SCRIPT CONTROL FUNCTIONS

// php_includeonly(__FILE__, [string $error]) - Call this at the beginning of a script to ensure it's only executed if it's being included
function php_includeonly($file, $error = 'You cannot access this file directly.') {
    if ( !is_included($file)  )
        die($error);
}

// php_exposesource([bool $highlight]) - Call this anywhere in a script to expose its whole source code
// $highlight:
// - True (DEFAULT) causes the code to be rendered in HTML, colour-coding it.
// - False causes it to output raw code.
function php_exposesource($highlight = true) {
    if ($highlight)
        $source = highlight_file($_SERVER['SCRIPT_FILENAME'], true);
    else
        $source = htmlentities( file_get_contents($_SERVER['SCRIPT_FILENAME']) );

    echo $source;
}

function php_astext() {
	header('Content-type: text/plain;');
}

// Returns random element of array
function array_rande($array) {
	$rand = array_rand($array);
	return $array[$rand];
}

// Case-insensitive array search, thanks http://www.php.net/manual/en/function.array-search.php#101595 !
function array_searchi($str, $array){
    foreach ($array as $key => $value)
        if ( stristr($str,$value) ) return $key;

    return false;
}

// Array regex search
function array_searchr($pattern, $array){
    foreach ($array as $key => $value)
        if ( preg_match($pattern, $value) ) return $key;

    return false;
}

// ===== DEBUGGING

// print_web(mixed $data)
function print_web($data) {
    if (PHP_SAPI != 'cli')
        print_r($data);
}

function print_cli($data) {
    if (PHP_SAPI == 'cli')
        print_r($data);
}

// debug(mixed $output) - Debugging system that allows for quick data-checking and other misc. debugging
// Usage: Call this function with any data you want to debug into $output where appropriate.
// Then when you want this debugging data to appear, either append "?debug" (or "&debug" if you've already got query data) to the end of the URL
// OR if you're debugging via command-line, add "debug" as an argument.
// Example: If the script is called 'test.php' on a host called 'simplaza.net' with a single call: debug("Hello world!");
// - "http://simplaza.net/test.php?debug" will output "Hello world!"
// - "php ./test.php debug" will output "Hello world!"
// Notes: As debug() uses print_r for output, you can feed it an array and it will expose the whole contents of that array, even multi-dimensionally.
function debug($output) {
	if ( isset($_GET['debug']) || in_array( 'debug', pick($argv, array()) ) )
		print_r($output);
}

// ==========
// MISC STUFF
// ==========

// Directly accessing this file = expose source code (for sharing publicly)
if ( !is_included(__FILE__) )
    php_exposesource();
?>