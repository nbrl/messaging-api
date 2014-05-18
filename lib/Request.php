<?php

class Request {
	public $url_parts;		// url path parts
	public $verb;			// get/post
	public $params;			// 

	public function __construct() {
		$this->db = new Redis();
		$this->verb = $_SERVER['REQUEST_METHOD'];

		// We need to read different vars depending on the webserver
		if (empty($_SERVER['REQUEST_URI'])) {
			// Apache
			$this->url_parts = explode('/', ltrim($_SERVER['ORIG_PATH_INFO'], '/'));
		} else {
			// PHP built-in
			$this->url_parts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
		}			

		/*echo "ORIG_PATH_INFO";
		print_r($_SERVER('ORIG_PATH_INFO'));
		echo "\nREQUEST_URI";
		print_r($_SERVER('REQUEST_URI'));*/

		$this->parseIncomingParams();

		$this->format = 'json';	// default to JSON
		if (isset($this->params['format'])) {
			$this->format = $this->params['format'];
		}

		return true;
	}

	public function parseIncomingParams() {
		$params = array();
		
		if (isset($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $params);
		}

		// If POSTED data isn't URL encoded - i.e. it's JSON - get data
		$body = file_get_contents("php://input");

		$content_type = false;
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$content_type = $_SERVER['CONTENT_TYPE'];
		}

		switch ($content_type) {
			case "application/json":
				$body_params = json_decode($body);
				if ($body_params) {
					foreach ($body_params as $name => $value) {
						$params[$name] = $value;
					}
				}
				$this->format = 'json';
				break;
			default:
				break;
		}
		$this->params = $params;
	}

}

?>
