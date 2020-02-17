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
        $this->db->select("tbl_user.*,tbl_user_role.name AS komisi");
        $this->db->from("tbl_user");
        $this->db->join("tbl_user_role", "tbl_user_role.id=tbl_user.role_id");
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

            foreach ($user as $key => $val) {
                $user[$key]['tgl_lahir'] = date("d/M/Y", $val['tgl_lahir']);
                $user[$key]['image'] = getThumb($val["image"]);
                $user[$key]['image_ori'] = getImg($val["image"], "profile");
            }
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
        $this->form_validation->set_rules("name", "Nama Lengkap", "required|min_length[5]", [
            "required" => "Nama Harus Di isi",
            "min_length" => "Nama Min 5 Karakter"
        ]);
        $this->form_validation->set_rules("email", "email", "required", [
            "required" => "Email Harus Di isi"
        ]);

        $this->form_validation->set_rules("no_hp", "No Handphone", "required|numeric", [
            "required" => "nomor Handphone Harus Di isi",
            "numeric" => "Nomor Handphone Hanya boleh Nomor"
        ]);
        $this->form_validation->set_rules("alamat", "Alamat", "required", [
            "required" => "Alamat Harus Di isi",
        ]);
        $this->form_validation->set_rules("password", "Password", "required", [
            "required" => "Password Harus Di isi"
        ]);

        if ($this->form_validation->run()) {
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
                "name" => $this->post('name'),
                "email" => $this->post('email'),
                "no_hp" => $this->post('no_hp'),
                "alamat" => $this->post('alamat'),
                "role_id" =>  $this->post('role_id')  ? $this->post('role_id') : "3",
                "is_active" => $this->post('is_active') ? $this->post('is_active') : 0,
                "image" => $image,
                "password" => password_hash($this->post('password'), PASSWORD_DEFAULT)
            ];
            $this->db->insert($tblUser['name'], $data);
            $respon = hasilCUD("Data User Ditambahkan");
            $respon->data = $data;
            if ($respon->status == true) {
                if ($data['role_id'] == 3) {
                    _sendEmail([
                        'email' => $data['email'],
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
                if ($user['is_active'] === 0) {
                    $this->response([
                        "status" => false,
                        "message" => "User Belum Di aktivasi",
                        "data" => $this->post("email")
                    ], 200);
                } else if (password_verify($password, $user['password'])) {
                    $user['image_sm'] = getThumb($user['image']);
                    $user['image_lg'] = getImg($user['image'], "profile");
                    $this->response([
                        "status" => true,
                        "message" => "user di temukan",
                        "id_user" => $user['id_user'],
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
                "message" => "Email tidak di temukan"
            ], 200);
        } else {
            $this->response([
                "status" => false,
                "message" => "Data Tidak Lengkap",
                "dataError" => $this->form_validation->error_array()
            ], 200);
        }
    }

    public function upload_post()
    {
        $image = $this->post("id_user") . ".jpeg";
        $path = "assets/img/profile/" . $image;
        if (file_put_contents($path, base64_decode($this->post("image")))) {
            $this->db->update("tbl_user", ['image' => $image], ['id_user' => $this->post("id_user")]);
            /**
             * Helper CI
             * Create thumb image with 
             * _img_create_thumbs({nama file image}, {Folder file gambar});
             */
            $path = "assets/img/thumbnail/profile_" . $image;
            file_put_contents($path, base64_decode($this->post("image")));
            $this->response([
                "status" => true,
                "message" => "Foto Profile Di Upload",
                "data" => ['image' => $image]
            ], 200);
        } else {
            $this->response([
                "status" => false,
                "message" => "Foto Profile Gagal Di Upload",
                "data" => $this->post('id_user')
            ], 200);
        }
    }

    public function index_put($id)
    {
        $tbl = initTable("tbl_user", "user");
        $user = $this->db->get_where($tbl['name'], ["id_user" => $id])->row_array();
        if ($user) {
            $update = $this->db->update($tbl['name'], $this->put(), ["id_user" => $id]);
            if ($update) {
                $respon = hasilCUD("Data Berhasil Di Update");
                if ($respon->status) {
                    $user = $this->db->get_where($tbl['name'], ["id_user" => $id])->row_array();
                    $user['image_sm'] = getThumb($user['image']);
                    $user['image_lg'] = getImg($user['image'], "profile");
                    $respon->data = $user;
                    $this->response($respon, 201);
                } else
                    $this->response($respon, 200);
            } else
                $this->response(['status' => false, "message" => "Gagal Update", "data" => $user], 200);
        } else
            $this->response(['status' => false, 'message' => "User Tidak Dikenali.!"], 500);
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
}
