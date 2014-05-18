<?php
/**
 * Data store class to simplify database access
 *
 * A Redis object contains a connection to a redis-server using the Predis
 * library. A subset of it's functionality is given via public methods to
 * enable the features of the API.
 */

class Redis {
	private $db;
	private $db_name = "messages";

	/**
	 * Creates the redis connection and saves it
	 */
	public function __construct() {
		try {
			$this->db = new Predis\Client();
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	/**
	 * Retrieves the message body given by $id
	 *
	 * @param  int    $id the ID of the message of interest
	 * @return string -   the body of the message
	 */
	public function getMessage($id=0) {
		//return $this->db->lrange($this->db_name, $id, $id)[0];
		return $this->db->lrange($this->db_name, $id, $id);
	}

	/**
	 * Retrieves the message body of all messages in store
	 *
	 * @return array - array of all of the message bodies
	 */
	public function getAllMessages() {
		return $this->db->lrange($this->db_name, 0, -1);
	}

	/**
	 * Adds a new message into the store
	 *
	 * @param string $content string to be pushed into the store
	 */
	public function newMessage($content="") {
		return $this->db->rpush($this->db_name, $content);
	}

}

?>
