<?php

class JsonView {
	public function render($content) {
		header('Content-Type: application/json');
		echo json_encode($content);
		return true;
	}
}
?>
