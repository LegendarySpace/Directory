<?php

require_once APPPATH.'controllers/D_Controller.php';
    class Towers extends D_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->model = $this->tower_model;
		}

		public function splash($id = FALSE) {
            // !important! All authentication done in PHP
            $grades = array(
            	'Companies',
				'Events',
				'Employees');
			return $this->get_splash($id, $grades);
			// If $id false or invalid then redirect to parent
        }
    }
