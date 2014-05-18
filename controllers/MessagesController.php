<?php

class MessagesController {

	public function getAction($request) {
		// Given http://host/one/two, url_parts[0] will be "one"
		if (isset($request->url_parts[1]) ){ //&& !empty($request->url_parts[1])) { // &&... is a bit dirty
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

	public function postAction($request) {
		// If we are POST'd some data, save it to the DB. Return value is message ID
		$data['id'] = $request->db->newMessage($request->params['body']);
		return $data;
	}

	public function __call($method, $args) {
		$data['body'] = "Method not defined.";
		return $data;
	}
}

?>
