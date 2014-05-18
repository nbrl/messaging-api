<?php
/**
 * The only view as we are only considering JSON
 *
 * When data is sent to be rendered by this view, it is simply JSON-encoded
 * and output.
 */
class JsonView {
	/**
	 * Renders the content sent to it
	 *
	 * @param string $content the content to be rendered
	 */
	public function render($content) {
		header('Content-Type: application/json');
		echo json_encode($content);
		return true;
	}
}
?>
