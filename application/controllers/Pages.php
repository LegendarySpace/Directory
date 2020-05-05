<?php
    class Pages extends CI_Controller{
        public function view($page = 'home') {
            if(!file_exists(APPPATH.'views/ajshead/'.$page.'.php')) {
                show_404();
            }

            $data['title'] = ucfirst($page);

            $this->load->view('templates/header');
            $this->load->view('templates/sidebar');
            // Loads angularJS controller
            $this->load->view('ajshead/'.$page);
            $this->load->view('templates/body', $data);
            $this->load->view('templates/footer');
        }
    }
