<?php

require_once APPPATH.'models/Directory_API.php';
class Company_model extends Directory_API
{
	public function __construct()
	{
		$this->table = 'Companies';
		$this->proper = array(
			'Name' => 'name',
			'TowerID' => 'tower',
			'Suite' => 'suites',
			'Reception' => 'reception',
			'ContactNumber'=> 'phone',
			'ContactEmail'=> 'email',
			'Slogan' => 'slogan',
			'Details' => 'details',
			'ImageURL' => 'image'
		);
	}

	public function get_splash($id = FALSE)
	{
		$data = $this->proper;

		return $this->splash($id, $data);
	}

	public function get_tiles($id = FALSE)
	{
		$data = null;

		if($id) $data = $this->proper;
		else $data = array(
			// Tile values
			'Name' => 'name',
			'AccountID' => 'id',
			'Reception' => 'aux'
		);

		return $this->tiles($id, $data);
	}

	public function item_struct()
	{
		// Array used by page to build forms
		return array(
			'name',
			'id',
			'admin',
			'tower',
			'suites',
			'reception',
			'phone',
			'email',
			'slogan',
			'details',
			'image'
		);
	}

	public function create_item()
	{
		// Retrieve and transmit POST data
		$data = $this->proper;

		return $this->create($data);
	}

	public function update_item($id = FALSE)
	{
		$data = $this->proper;

		return $this->update($id, $data);
	}
}
