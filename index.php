<?php
/**
 * Load any needed PHP files, gather query info and execute/display
 *
 * This file first generates a list of the files which must be included at run
 * time and includes them. A new Request object is created which contains all
 * of the details of the query sent (i.e. access URL, verb, args etc.). The
 * controller is then chosen based on the URL (only ever messages) and the
 * view chosen based on the request format (although only uses JSON).
 * Incorrect API calls are handled by just giving a 404.
 */

// Gather list of all necessary classes
$inc_list = array_merge(
	glob('controllers/*'),
	glob('lib/*'),
	glob('views/*'));
array_push($inc_list, 'vendor/autoload.php');

// Load all classes
foreach ($inc_list as $f) {
	require_once($f);
}

// Request object contains all details required for processing
$request = new Request();

// The URL-dependent controller and view
$controller_name = ucfirst($request->url_parts[0]) . 'Controller';
if (class_exists($controller_name)) {
	$controller = new $controller_name();
	$action_name = strtolower($request->verb) . 'Action';
	$result = $controller->$action_name($request);
	
	// Hint: view is always JsonView
	$view_name = ucfirst($request->format) . 'View';
	if (class_exists($view_name)) {
		$view = new $view_name();
		$view->render($result);
	}
} else {
	// Return 404 if a non-handled controller is requested
	header("HTTP/1.0 404 Not Found");
}

?>
