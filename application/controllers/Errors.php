<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->template->load("public", 'error/403');
    }
    public function layout()
    {
    }
}
