<?php

require_once APPPATH.'controllers/D_Controller.php';
    class Homes extends D_Controller{

    	public function __construct()
		{
			$this->model = null;
		}

		public function splash($id = FALSE){
            // initialize splash message
            $splash = array(
            	'message'=>'Welcome to Faux Directory',
				'sub'=>'Surprisingly real'
			);
            $grades = array(
            	'Towers',
				'Events');
			if (empty($splash) || empty($sections)) show_404();
			else return $this->jsonp_return(array('splash'=>$splash, 'grades'=>$grades));
        }

	}
