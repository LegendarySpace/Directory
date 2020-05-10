<?php

require_once APPPATH.'controllers/D_Controller.php';
class Employees extends D_Controller
{
	// ******************************** //
	// !!! Only needed to set model !!! //
	// ******************************** //
	public function __construct()
	{
		parent::__construct();
		$this->model = $this->employee_model;
	}

	public function splash($id = FALSE)
	{
		// No page for employees so return null
		return null;
	}

	public function get_tiles($id)
	{
		// Perform additional checks before releasing values
		$tiles = array();
		$temp = parent::get_tiles($id, true);

		/*
		 * For each item in temp, if at least one of the following conditions is met add to tiles
		 * 		1) Is marked as Public
		 * 		2) Account is Admin
		 * 		3) Account is security
		 */
		foreach ($temp as $item) {
			$accepted = false;
			if($item['public']) $accepted = true;
			else {
				$userid = null; // GET USER ID
				if ($this->company_model->verify_admin($item['company'], $userid)) $accepted = true;
				elseif ($this->company_model->verify_security($item['company'], $userid)) $accepted = true;
			}

			if ($accepted) array_push($tiles, item);
			// NOTE changing company id to it's name will be done on client side
		}
	}
}
