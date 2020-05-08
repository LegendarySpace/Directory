<?php

abstract class D_Controller extends CI_Controller
{
	// !!IMPORTANT!!  Set model in constructor
	protected $model = null;

	/*
	 * Needs an array containing the section Names
	 */
	abstract public function splash($id = FALSE);

	public function tiles() {
		return $this->get_tiles();
	}

	public function bubble($id = FALSE) {
		if(!id) show_404();
		return $this->get_tiles($id);
	}

	public function create() {
		if (empty($this->model)) show_404();
		if ($this->model->create_item()) return "Item Successfully Created";
	}

	public function update($id) {
		if (empty($this->model)) show_404();
		if ($this->model->update_item($id)) return "Item Successfully Updated";
	}

	public function delete($id) {
		if (empty($this->model)) show_404();
		if ($this->model->delete_item($id)) return "Item Successfully Deleted";
	}

	protected function get_splash($id, $sections) {
		if (empty($this->model)) show_404();
		$splash = $this->model->get_splash($id);

		if (empty($splash) || empty($sections)) show_404();
		else return $this->jsonp_return(array('splash'=>$splash, 'sections'=>$sections));
	}

	protected function get_tiles($id = FALSE, $raw = FALSE) {
		if (empty($this->model)) show_404();
		$tiles = $this->model->get_tiles($id);

		if (empty($tiles)) show_404();
		elseif ($raw) return $tiles;
		else return $this->jsonp_return($tiles);
	}

	protected function jsonp_return($data) {
		header("Content-Type: application/json");
		$json = json_encode($data);
		if ($json === false) {
			// Avoid echoing empty string
			// JSONify error message instead:
			$json = json_encode(["jsonError" => json_last_error_msg()]);
			if ($json === false) {
				// This shouldn't happen but just in case
				$json = '{"jsonError":"unknown"}';
			}
			// Set HTTP response status code to : 500 - Internal Server Error
			http_response_code(500);
			return false;
		}
		echo isset($_GET['callback'])
			// Use GET with isset instead of $this->input->get('callback')
			? "{$_GET['callback']}($json)"
			: $json;
		return true;
	}
}
