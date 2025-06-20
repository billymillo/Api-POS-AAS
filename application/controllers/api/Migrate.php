<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function index()
    {
        $this->load->library('migration');
        if ($this->migration->version(24) === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migration successful!";
        }
    }

}
