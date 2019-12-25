<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $response = is_login(3);
        if (!$response->status) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Menu yang anda akses sebelumnya dilarang!</span></div>');
            redirect('admin/auth/index/3');
        }
    }

    public function index()
    {
        $aspirasi = $this->aspirasi_m;
        $this->data['all_aspirasi'] = $aspirasi->getWhere("user_id")->result_array();

        $this->template->load("public", 'user/index', $this->data);
    }


    public function profile()
    {
    }
    public function login()
    {
        $this->_permission();
        $this->template->load("public", 'login', $this->data);
    }
    public function register($id = null)
    {
        $this->_permission();
        if (!$id) {
            redirect('/');
        }

        $this->data['page']['title'] =  "Form Registrasi Masyarakat";
        $this->template->load("public", 'register', $this->data);
    }
    public function logout()
    {
        $this->session->unset_userdata('user_login');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out!</div>');
        redirect('/');
    }
    public function _cekUser()
    {
        $email = $this->session->userdata('user_login');
        $this->load->model('user_model');
        $user = $this->user_model->getUser($email);
        if ($user)
            $this->user = $user;
        else $this->logout();
    }
    function _permission()
    {
        if ($this->session->userdata('user_login')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Anda Sudah Login!</span></div>');
            redirect("/user");
        }
    }
}
