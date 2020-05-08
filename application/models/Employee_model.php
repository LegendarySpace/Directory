<?php


class Employee_model extends Directory_API
{
	public function __construct()
	{
		parent::__construct();
		$this->table = 'Employees';
		$this->proper = array(
			'FirstName' => 'first name',
			'LastName' => 'last name',
			'CompanyID' => 'company',
			'Title' => 'title',
			'Phone' => 'phone',
			'Email' => 'email',
			'Address' => 'address',
			'Public' => 'public',
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

		if ($id) $data = $this->proper;
		else $data = array(
			// Tile values
			'FirstName' => 'first name',
			'LastName' => 'last name',
			'AccountID' => 'id',
			'Title' => 'aux'
		);

		return $this->tiles($id, $data);
	}

	public function item_struct()
	{
		// Array used by page to build forms
		return array(
			'first name',
			'last name',
			'id',
			'company',
			'title',
			'phone',
			'email',
			'address',
			'public',
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
