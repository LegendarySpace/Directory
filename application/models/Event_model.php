<?php


class Event_model extends Directory_API
{
	public function __construct()
	{
		parent::__construct();
		$this->table = 'Events';
		$this->proper = array(
			'Name' => 'name',
			'CompanyID' => 'company',
			'TowerID' => 'tower',
			'Host' => 'host',
			'Slogan' => 'slogan',
			'Location' => 'location',
			'Details' => 'details'
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

		if ($id) $data = $this->proper;
		else $data = array(
			// Tile Values
			'Name' => 'name',
			'AccountID' => 'id',
			'Host' => 'aux'
		);
	}

	public function item_struct()
	{
		// Array used by page to build forms
		return array(
			'name',
			'id',
			'company',
			'tower',
			'host',
			'slogan',
			'location',
			'details'
		);
	}

	public function create_item()
	{
		$data = $this->proper;

		return $this->create($data);
	}

	public function update_item($id = FALSE)
	{
		$data = $this->proper;

		return $this->update($id, $data);
	}
}
