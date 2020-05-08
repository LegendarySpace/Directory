<?php


class Events extends D_Controller
{
	// ******************************** //
	// !!! Only needed to set model !!! //
	// ******************************** //
	public function __construct()
	{
		$this->model = $this->event_model;
	}

	public function splash($id = FALSE)
	{
		// There's no page for events so no need for splash
		return null;
	}
}
