<?php


class User_model extends Directory_API
{
	public function __construct()
	{
		parent::__construct();
		$this->table = 'Users';
		$this->proper = array(
			'Username' => 'username',
			'Password' => 'password',
			'FirstName' => 'first name',
			'LastName' => 'last name',
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
			// Tile Values
			'Username' => 'username',
			'AccountID' => 'id',
			'ImageURL' => 'aux'
		);
	}

	public function item_struct()
	{
		// Array used by page to build forms
		return array(
			'username',
			'id',
			'password',
			'first name',
			'last name',
			'image'
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
