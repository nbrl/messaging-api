<?php
/**
 * Contains the MessageController class, where the bulk of the processing occurs.
 *
 * This class is handed a Request object, which contains the details of the
 * submission to the server. The file /index.php checks which verb was used
 * in the request (be it POST, GET etc.) and the correct method is called
 * here (from /index.php). If it is a GET request, getAction runs, returning
 * either a list of all messages or the requested message. If it is a POST
 * request, the body of the submitted JSON is pushed into the Redis data
 * store.
 */

class MessagesController {

	/**
	 * Called for an HTTP GET request
	 *
	 * @param  Request $request an array of key-value pairs decribing the
	 *                          details of the request.
	 * @return array   $date    key-value pair containing the body of the
	 *                          message(s) requested.
	 */
	public function getAction($request) {
		// Given http://host/one/two, url_parts[0] will be "one"
		if (isset($request->url_parts[1]) && $request->url_parts[1] != "" ){
			// Capture the ID for the desired message, if requested
			$message_id = (int) $request->url_parts[1];

			// Retrieve the body of the message from the DB object
			$message_array = $request->db->getMessage($message_id);
			if (!empty($message_array)) {
				$data['body'] = $message_array[0];
			} else {
				header("HTTP/1.0 404 Not Found");
				exit;
			}
		} else {
			// No message ID given, so return all messages
			$message_array = $request->db->getAllMessages();
			if (!is_null($message_array)) {
				$data['body'] = $message_array;
			} else {
				header("HTTP/1.0 404 Not Found");
				exit;
			}
		}
		return $data;
	}

	/**
	 * Called for an HTTP POST request
	 *
	 * @param  Request $request an array of key-value pairs decribing the
	 *                          details of the request.
	 * @return array   $data    key-value pair containing the id of the
	 *                          newly inserted message.
	 */
	public function postAction($request) {
		// If we are POST'd some data, save it to the DB. Return value is message ID
		// ID must be decremented by one to correlate to the ID we use to retrieve.
		$data['id'] = $request->db->newMessage($request->params['body']) - 1;
		return $data;
	}

	/**
	 * Catch all function to return a 404 on non-GET or -POST requests
	 *
	 * @param string $method The name of the unknown method.
	 * @param array  $args   Any arguments submitted with the method.
	 */
	public function __call($method, $args) {
		header("HTTP/1.0 404 Not Found");
	}
}

?>
