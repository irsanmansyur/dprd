<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require APPPATH . '/libraries/REST_Controller.php';

class DbHelper extends CI_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('tbl_m');
    }
    public function index()
    {
        $data['message'] = '';
        $this->load->view("welcome_message", $data);
    }
}
