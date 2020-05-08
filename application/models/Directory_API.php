<?php

abstract class Directory_API extends CI_Model
{
	// !!IMPORTANT!! Set defaults in constructor and make sure to call parent constructor
	/*
	 * structure of $properties - an associative array
	 * 		key = column name of the searched table
	 * 		exp = name to export value as
	 */
	protected $table = null;
	protected $proper = null;
	public function __construct()
	{
		$this->load->database;
	}

	public function delete_item($id = FALSE) {
		if(!id) return false;
		$this->db->where('AccountID', $id);
		return $this->db->delete($this->table);
	}

	protected function splash($id = FALSE, $properties) {
		if(!$this->table || !id) return null;
		$query = $this->db->get_where($this->table, array('AccountID' => $id));
		$temp = $query->row_array();
		$data = array();
		foreach ($properties as $key => $exp) {
			if(isset($temp[$key])) $data[$exp] = $temp[$key];
		}
		if (empty($data)) return false;
		return $data;
	}

	protected function tiles($id = FALSE, $properties) {
		$result = null;
		if(!$id) {
			$query = $this->db->get($this->table);
			$result = $query->result_array();
		} else {
			$query = $this->db->get_where($this->table, array('AccountID' => $id));
			$result = array($query->row_array());
		}
		$data = array();
		foreach ($result as $item) {
			$temp = array();
			foreach ($properties as $key => $exp) {
				if (isset($item[$key])) $temp[$exp] = $item[$key];
			}
			if(!empty($temp)) array_push($data, $temp);
		}
		if (empty($data)) return null;

		if ($id) return $data[0];
		else return $data;
	}

	protected function create($properties) {
		$data = array();
		foreach ($properties as $key => $exp) {
			$data[$key] = $this->input->post($exp);
		}
		if(empty($data)) return false;
		else return $this->db->insert($this->table, $data);
	}

	protected function update($id, $properties) {
		if (!$id) return false;
		$data = array();
		foreach ($properties as $key => $exp) {
			$data[$key] = $this->input->post($exp);
		}
		if(empty($data)) return false;
		$this->db->where('AccountID', $id);
		return $this->db->update($this->table, $data);
	}

	abstract public function get_splash($id = null);

	abstract public function get_tiles($id = null);

	abstract public function item_struct();

	abstract public function create_item();

	abstract public function update_item($id = null);

	// TODO FUTURE ADDITIONS
	public function verify_admin($id, $caller) {
		// $id is AccountID and $caller is compared to Admin
		return false;
	}
	public function verify_security($id, $caller) { return false;}
}
