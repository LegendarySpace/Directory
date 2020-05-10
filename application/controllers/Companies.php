<?php

require_once APPPATH.'controllers/D_Controller.php';
class Companies extends D_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->model = $this->company_model;
	}

	public function splash($id = FALSE)
	{
		// !Important1 All authentication done in PHP
		$sections = array(
			'Events',
			'Employees'
		);
		return $this->get_splash($id, $sections);
		// If $id false or invalid then redirect to parent
	}
}
