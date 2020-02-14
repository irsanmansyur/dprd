<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $user = $this->db->get_where("tbl_user", [
            "id_user" => $this->input->get("id")
        ])->row_array();
        if ($user) {
            $data = [
                'time' => time() + (60 * 60),
                'email' => $user['email'],
                'role_id' => $user['role_id']
            ];

            $this->session->set_userdata($data);
            $this->session->set_flashdata('notif', '<div class="alert alert-succes" role="alert">Succes Login!</div>');
            if ($this->session->userdata('url')) {
                redirect($this->session->userdata('url'));
            }
            $user['role_id'] == 3 ? redirect("user") : redirect('admin/dashboard');
        } else
            redirect("admin/auth");
    }
}
