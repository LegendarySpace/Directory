<?php

require_once APPPATH.'controllers/D_Controller.php';
class Companies extends D_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->model = $this->company_model;
	}

	// add jsonp_return to this level on other controllers
	public function splash($id = FALSE)
	{
		// !Important1 All authentication done in PHP
		$grades = array(
			'Events',
			'Employees'
		);
		$splash = $this->get_splash($id);
		return $this->jsonp_return(array('splash'=>$splash,'grades'=>$grades));
		// If $id false or invalid then redirect to parent
	}
}
