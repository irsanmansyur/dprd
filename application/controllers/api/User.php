<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require APPPATH . '/libraries/REST_Controller.php';


use Restserver\Libraries\RestController;

require(APPPATH . 'libraries/RestController.php');
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class User extends RestController
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 10; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->library('form_validation');
    }

    public function index_get($id = null)
    {
        $page = 1;
        $halaman = 10; //batasan halaman
        $mulai = 0;
        foreach ($this->get() as $key => $val) {
            if ($key == "page") {
                $page = $val;
                $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                $this->db->limit($halaman, $mulai);
            } else
                $this->db->where([$key => $val]);
        }
        if ($id) {
            $this->db->where([
                "id_user" => $id
            ]);
        }
        $user = $this->db->get()->result_array();
        if ($user) {
            $this->response([
                'status' => true,
                "message" => "User di temukan",
                "data" => $user
            ], 200);
        } else {
            $this->response([
                'status' => false,
                "message" => "User tidak di temukan"
            ], 200);
        }
    }

    public function register_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules("name", "Nama Lengkap", "required|min_length[5]");
        $this->form_validation->set_rules("email", "email", "required|is_unique[tbl_user.email]");
        $this->form_validation->set_rules("no_hp", "No Handphone", "required|numeric");
        $this->form_validation->set_rules("alamat", "Alamat", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_rules("role_id", "role id", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post();
            $image = "default.png";
            if (isset($_FILES['image'])) {
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = '3048';
                $config['upload_path'] = './assets/img/profile/';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $image = $this->upload->data('file_name');

                    /**
                     * Helper CI
                     * Create thumb image with 
                     * _img_create_thumbs({nama file image}, {Folder file gambar});
                     */
                    _img_create_thumbs($image, "profile");
                }
            }
            $tblUser = initTable("tbl_user", "user");
            $tblUser['lastkey'] = $tblUser['field'][$tblUser['key']];

            $data = [
                $tblUser['key'] =>  $tblUser['lastkey'],
                "name" => $post['name'],
                "email" => $post['email'],
                "no_hp" => $post['no_hp'],
                "alamat" => $post['alamat'],
                "role_id" =>  $this->input->post('role_id') ? $this->input->post('role_id') : 3,
                "is_active" => $this->input->post('is_active') ? $post['is_active'] : 0,
                "image" => $image,
                "password" => password_hash($post['password'], PASSWORD_DEFAULT)
            ];
            $this->db->insert($tblUser['name'], $data);
            $respon = hasilCUD("Data User Ditambahkan");
            $respon->data = $data;
            if ($respon->status) {
                if ($post["role_id"] == 3) {
                    $this->_sendEmail([
                        'email' => $post['email'],
                        'type' => "verify",
                        "token" => uniqid()
                    ]);
                }
            }
            $this->response($respon, 200);
        } else {
            $field = $this->form_validation->error_array();
            $this->response([
                'status' => FALSE,
                'message' => 'Lengkapi data dulu',
                "dataErrors" => $field
            ], 200); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function login_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules("email", "Email", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        if ($this->form_validation->run()) {
            $email = $this->post("email");
            $password = $this->post("password");
            $user = $this->db->get_where("tbl_user", [
                "email" => $email
            ])->row_array();
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $this->response([
                        "status" => true,
                        "message" => "user di temukan",
                        "id_user" => $user['email'],
                        "data" => $user
                    ], 200);
                } else {
                    $this->response([
                        "status" => false,
                        "message" => "Password Salah",
                        "data" => $this->post("email")
                    ], 200);
                }
            } else $this->response([
                "status" => false,
                "message" => "User tidak di temukan"
            ], 200);
        } else {
        }
    }


    public function index_put()
    {
        $tbl = initTable("web_aspirasi", "asp");
        $where = $this->input->get();
        if (count($where) > 0) {
            $this->db->where($where);
            $update = $this->db->update($tbl['name'], $this->put());
            if ($update) {
                $respon = hasilCUD("Data Berhasil Di Update");
                if ($respon->status)
                    $this->response($respon, 201);
                else
                    $this->response($respon, 200);
            } else {
                $this->response(['status' => false], 400);
            }
        } else
            $this->response(['status' => false, 'message' => "Update Ditolak, Ada kesalahan.!"], 500);
    }
    public function index_delete()
    {

        $where = $this->input->get();
        if (count($this->delete()) > 0) {
            foreach ($this->delete() as $row => $value) {
                $where[$row] = $value;
            }
        }

        $respon = $this->db->delete("web_aspirasi", $where);
        if ($respon) {
            $eks = hasilCUD("deleted.!");
            $this->response($eks, 200);
        } else {
            $this->response([
                'status' => false,
                "message" => "Terjadi Kesalahan"
            ], 502); // BAD_REQUEST (400) being the HTTP response code
        }
    }
    private function _sendEmail($data)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'berkominfo@gmail.com',
            'smtp_pass' => 'ichaNK01',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->load->library('email');
        $this->email->initialize($config);

        $this->email->from('berkominfo@gmail.com', 'Berkominfo');
        $this->email->to($data['email']);

        if ($data['type'] == 'verify') {
            $this->email->subject('Account Verification');
            $this->email->message('Your Token : ' . $data['token'] . ' ,</br>Click this link to verify you account : <a href="' . base_url() . 'admin/auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($data['token']) . '">Activate</a>');
        } else if ($data['type'] == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Your Token : ' . $data['token'] . ' ,</br>Click this link to reset your password : <a href="' . base_url() . 'admin/auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($data['token']) . '">Reset Password</a>');
        }
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die();
        }
    }
}
