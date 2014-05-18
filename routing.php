<?php
/**
 * Routing file for the built-in PHP web server
 *
 * This is the equivalent of .htaccess for Apache, but for PHPs build-in web
 * server. It simply sends any request for a non-existent file to index.php in
 * the document root.
 */

if (file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
	return false; // serve the requested resource as-is.
} else {
	include_once 'index.php';
}

?>
