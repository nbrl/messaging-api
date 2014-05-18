<?php

class Redis {
	private $db;
	private $db_name = "messages";

	public function __construct() {
		try {
			$this->db = new Predis\Client();
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	public function getMessage($id=0) {
		//return $this->db->lrange($this->db_name, $id, $id)[0];
		return $this->db->lrange($this->db_name, $id, $id);
	}

	public function getAllMessages() {
		return $this->db->lrange($this->db_name, 0, -1);
	}

	public function newMessage($content="") {
		return $this->db->rpush($this->db_name, $content);
	}

}

?>
