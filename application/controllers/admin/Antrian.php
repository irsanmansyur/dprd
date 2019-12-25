<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Antrian extends Admin_Controller
{
    function index()
    {
        $this->template->load('admin', 'backend/dashboard', $this->data);
    }
    function admin()
    { }
}
