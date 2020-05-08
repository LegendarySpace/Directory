<?php

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
            $sections = array(
            	'Tower',
				'Event');
			if (empty($splash) || empty($sections)) show_404();
			else return $this->jsonp_return(array('splash'=>$splash, 'sections'=>$sections));
        }

	}
