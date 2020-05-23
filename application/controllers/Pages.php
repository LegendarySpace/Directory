<?php
    class Pages extends CI_Controller{
        public function view($path = 'home') {
            $page = preg_split("/[\/]+/", $path)[0];
            $id = preg_split("/[\/]+/", $path)[1];
            if($page != 'home' && $page != 'company' && $page != 'tower') {
                show_404();
            }

            $data['title'] = ucfirst($page);
            $data['id'] = $id;

            // 
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            // Loads angularJS controller
            $this->load->view('ajshead/'.$page);
            $this->load->view('templates/body');
            $this->load->view('templates/footer');
        }
    }
