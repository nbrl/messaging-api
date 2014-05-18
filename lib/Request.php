<?php
/**
 * Gather all HTTP request data together and format it into useful values
 *
 * Takes all of the request information and stores it in variables for later
 * access. Additionally, creates a data store object used for adding and
 * reading data.
 */

class Request {
	public $url_parts;		// url path parts
	public $verb;			// get/post
	public $params;			// contents to add to data store

	/**
	 * Prepares the Request object
	 *
	 * Does all necessary work in preparing the object for use.
	 */
	public function __construct() {
		$this->db = new Redis();
		$this->verb = $_SERVER['REQUEST_METHOD'];

		// Depending on our configuration, we may need to trim "api" or another
		// foldername from our url_parts. Apache needs trimming, PHP not.
		$this->url_parts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
		if ($this->url_parts[0] == "api") {
			array_shift($this->url_parts);
		}

		// Grab the JSON if any
		$this->parseIncomingParams();

		$this->format = 'json';	// default to JSON
		if (isset($this->params['format'])) {
			$this->format = $this->params['format'];
		}

		return true;
	}

	/**
	 * Takes $_SERVER data and stores the useful parts
	 *
	 * Most of the data processing occurs here. $_SERVER is analysed for
	 * the QUERY_STRING which is the path used in the request, used for GETs
	 * (i.e. ../messages/10). Any JSON-encoded data is stored (as this is
	 * what is stored. Finally, the CONTENT_TYPE is checked to ensure it is
	 * JSON so we deal with it correctly.
	 *
	 * @return array $params key-value store containing the useful data taken
	 *                       from the server request.
	 */
	public function parseIncomingParams() {
		$params = array();

		if (isset($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $params);
		}

		// If POSTED data isn't URL encoded - i.e. it's JSON - get data
		$body = file_get_contents("php://input");

		$content_type = false;
		if (isset($_SERVER['CONTENT_TYPE'])) {
			// Apache
			$content_type = $_SERVER['CONTENT_TYPE'];
		} else if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
			$content_type = $_SERVER['HTTP_CONTENT_TYPE'];
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
