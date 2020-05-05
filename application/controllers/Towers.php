<?php
    class Towers extends CI_Controllers{
        public function splash($id = FALSE) {
            // If $id false or invalid then redirect to parent
            // !important! All authentication done in PHP
            $data = $this->tower_model->get_splash();

            // $admin = $_SESSION['userID'] === $row['AdminID'];
            $splashArray = array('name'=>$data['Name'], 'location'=>$data['Location'], 'manage'=>$data['ManagementCompany'],
                'phone'=>$data['ManagementContact'], 'email'=>$data['ManagementContactEmail'],
                'details'=>$data['Details'], 'img'=>$data['ImageURL']);

            $sectionsArray = array('Company', 'Event', 'Employee');

            if (empty($splashArray) || empty($sectionsArray)) show_404();
            else jsonreturn(array('splash'=>$splashArray, 'sections'=>$sectionsArray));
        }

        public function tiles() {
            $tileArray = array();
            $tiles = $this->tower_model->get_tiles();
            foreach($tiles as $tile) {
                array_push($tileArray, array('name' => $tile['Name'], 'aux' => $tile['Location'], 'id' => $tile['AccountID']));
            }
            if(empty($tileArray)) show_404();
            else $this->jsonpreturn($tileArray);
        }

        public function bubble($id = FALSE) {
            if(!id) show_404();
            $bubbleArray = $this->tower_model->get_tiles($id);
            if(empty($bubbleArray)) show_404();
            else $this->jsonpreturn($bubbleArray);
        }

        public function create() {
            // Call Model function then redirect
            $this->tower_model->create_tower();
            // Inform user of creation
        }

        public function update($id) {
            // TODO Submit data to update tower
            // If !id valid return error
        }

        public function delete($id) {
            // Remove data
            $this->tower_model->delete_tower($id);
            // Inform user of deletion
        }

        public function jsonpreturn($data) {
            header("Content-Type: application/json");
            $json = json_encode($data);
            if ($json === false) {
                // Avoid echo of empty string (which is invalid JSON), and
                // JSONify the error message instead:
                $json = json_encode(["jsonError" => json_last_error_msg()]);
                if ($json === false) {
                    // This should not happen, but we go all the way now:
                    $json = '{"jsonError":"unknown"}';
                }
                // Set HTTP response status code to: 500 - Internal Server Error
                http_response_code(500);
            }
            echo isset($_GET['callback'])
                ? "{$_GET['callback']}($json)"
                : $json;
        }
    }
