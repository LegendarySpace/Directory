<?php

class Users extends D_Controller
{
	// ******************************** //
	// !!! Only needed to set model !!! //
	// ******************************** //
	public function __construct()
	{
		$this->model = $this->user_model;
	}

	public function splash($id = FALSE)
	{
		// No user page so return null
		return null;
	}
}
