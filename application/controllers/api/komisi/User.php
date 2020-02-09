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

    public function index_post()
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

    public function index_put($idUser = "")
    {
        if ($idUser) {
            $user = $this->db->get_where("tbl_user", ['id_user' => $idUser])->row_array();
            $data = $this->put();

            $data = [];
            $tbl = initTable("tbl_user", "user");
            foreach ($tbl['field'] as $key => $value) {
                if (array_key_exists($key, $this->put())) {
                    $data[$key] = $this->put($key);
                }
            }

            $where = array_merge([
                "id_user" => $idUser
            ], $this->input->get());
            if (isset($_FILES['image']) && $user) {
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = '3048';
                $config['upload_path'] = './assets/img/profile/';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $image = $this->upload->data('file_name');
                    $data['image'] = $image;

                    if ($user['image'] != "defautl.png") {
                        if (is_file(FCPATH . 'assets/img/profile/' . $user['image']))
                            unlink(FCPATH . 'assets/img/profile/' . $user['image']);
                        if (is_file(FCPATH . 'assets/img/thumbnail/profile_' . $user['image']))
                            unlink(FCPATH . 'assets/img/thumbnail/profile_' . $user['image']);
                    }
                    /**
                     * Helper CI
                     * Create thumb image with 
                     * _img_create_thumbs({nama file image}, {Folder file gambar});
                     */
                    _img_create_thumbs($image, "profile");
                }
            }


            $this->db->where($where);
            $update = $this->db->update($tbl['name'], $data);
            if ($update) {
                $respon = hasilCUD("Data Berhasil Di Update");
                if ($respon->status)
                    $this->response(["status" => $respon->status, "message" => $respon->message, "data" => $user], 201);
                else
                    $this->response(["status" => $respon->status, "message" => $respon->message, "field" => $this->put()], 200);
            } else {
                $this->response(['status' => false, "message" => "Gagal", "field" => $this->put()], 200);
            }
        } else
            $this->response(['status' => false, 'message' => "Update Ditolak, Id User kosong.!"], 500);
    }

    public function img_post($idUser)
    {
        $user = $this->db->get_where("tbl_user", ['id_user' => $idUser])->row_array();
        if (isset($_FILES['image']) && $user) {
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = '3048';
            $config['upload_path'] = './assets/img/profile/';
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data('file_name');

                if ($user['image'] != "defautl.png") {
                    if (is_file(FCPATH . 'assets/img/profile/' . $user['image']))
                        unlink(FCPATH . 'assets/img/profile/' . $user['image']);
                    if (is_file(FCPATH . 'assets/img/thumbnail/profile_' . $user['image']))
                        unlink(FCPATH . 'assets/img/thumbnail/profile_' . $user['image']);
                }
                /**
                 * Helper CI
                 * Create thumb image with 
                 * _img_create_thumbs({nama file image}, {Folder file gambar});
                 */
                _img_create_thumbs($image, "profile");
                $this->db->update("tbl_user", ['image' => $image], ['id_user' => $idUser]);
                $respon = hasilCUD("Data Berhasil Di Update");
                if ($respon->status)
                    $this->response($respon, 201);
                else
                    $this->response($respon, 200);
            }
            $this->response(['status' => false, 'message' => "Update Ditolak, Ada kesalahan.!"], 500);
        } else
            $this->response(['status' => false, 'message' => "Update Ditolak, Ada kesalahan.!"], 500);
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
