<?php

    class Towers extends D_Controller {

		public function __construct()
		{
			$this->model = $this->tower_model;
		}

		public function splash($id = FALSE) {
            // !important! All authentication done in PHP
            $sections = array(
            	'Company',
				'Event',
				'Employee');
			return $this->get_splash($id, $sections);
			// If $id false or invalid then redirect to parent
        }
    }
