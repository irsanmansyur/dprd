<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Userku extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    }
    function id($email)
    {
        $data = [
            'time' => time() + (60 * 60),
            'email' => $email,
            'role_id' => 3
        ];
        $this->session->set_userdata($data);
        $response = is_login(3);
        $aspirasi = $this->db->get_where("web_aspirasi", ['user_id' => $this->data['user']['id_user']]);
        $this->data['all_aspirasi'] = $aspirasi->result_array();
        $this->data['page']['title'] =  "Daftar aspirasi User";
        $this->template->load("public", 'user/index', $this->data);
    }
}
