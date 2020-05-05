<?php
    class Homes extends CI_Controller{
        public function splash(){
            // TODO Return splash data
            // initialize splash message
            $splashArray = array('message'=>'Welcome to Faux Directory', 'sub'=>'Surprisingly real');
            // add sections
            $sectionsArray = array('Tower', 'Event');
            if (empty($splashArray) || empty($sectionsArray)) show_404();
            else jsonreturn(array('splash'=>$splashArray, 'sections'=>$sectionsArray));
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