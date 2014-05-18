<?php

class Request {
	public $url_parts;		// url path parts
	public $verb;			// get/post
	public $params;			// 

	public function __construct() {
		$this->db = new Redis();
		$this->verb = $_SERVER['REQUEST_METHOD'];

		// Depending on our configuration, we may need to trim "api" or another
		// foldername from our url_parts
		$this->url_parts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
		if ($this->url_parts[0] == "api") {
			array_shift($this->url_parts);
		}

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
