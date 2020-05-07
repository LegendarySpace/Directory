<?php
    class Tower_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        pulic function get_splash($id = FALSE) {
            if (!$id) return null;
            $query = $this->db-get_where('Towers', array('AccountID' => $id));
            return $query->row_array();
        }

        public function get_tiles($id = FALSE) {
            if(!$id) {
                $query = $this->db->get('Towers');
                return $query->result_array();
            } else {
                $query = $this->db->get_where('Towers', array('AccountID' => $id));
                return $query->row_array();
            }
        }

        public function create_tower() {
            // Retrieve POST data and transmit it
            $data = array(
                'Name' => $this->input->post('name'),
                'AdminID' => $this->input->post('id'),
                'Location' => $this->input->post('location'),
                'ManagementCompany' => $this->input->post('management company'),
                'ManagementContact' => $this->input->post('number'),
                'ManagementContactEmail' => $this->input->post('email'),
                'ImageURL' => null,
                'Details' => $this->input->post('details')
            );

            return $this->db->insert('Towers', $data);
        }

        public function update_tower($id = FALSE) {
            // Retrieve POST data and transmit it
            $data = array(
                'Name' => $this->input->post('name'),
                'AdminID' => $this->input->post('id'),
                'Location' => $this->input->post('location'),
                'ManagementCompany' => $this->input->post('management company'),
                'ManagementContact' => $this->input->post('number'),
                'ManagementContactEmail' => $this->input->post('email'),
                'ImageURL' => null,
                'Details' => $this->input->post('details')
            );

            $this->db->where('AccountID', $id);
            return $this->db->update('Towers', $data);
        }

        public function delete_tower($id = FALSE) {
            if(!id) return false;
            $this->db->where('AccountID', $id);
            $this->db->delete('Towers');
            return true;
        }
    }
