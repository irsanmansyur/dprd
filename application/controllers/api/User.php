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
        $email = $this->post("email");
        $password = $this->post("password");
        $user = $this->db->get_where("tbl_user",[
            "email" => $email
            // "password"=> $password
        ])->row_array();
        if($user){
            $this->response([
                "status" => true,
                "message" => "user di temukan",
                "data" => $user
            ], 200);
        }
        else $this->response([
            "status" => false,
            "message" => "tidak di temukan"
        ],400);
    }

    public function login_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules("email", "Email", "required");
        $this->form_validation->set_rules("password", "Password","required");
        if($this->form_validation->run()){
            $email = $this->post("email");
            $password = $this->post("password");
            $user = $this->db->get_where("tbl_user",[
                "email" => $email
            ])->row_array();
            if($user){
                if(password_verify($password, $user['password'])){
                    $this->response([
                        "status" => true,
                        "message" => "user di temukan",
                        "data" => $user['email']
                    ], 200);
                } else {
                    $this->response([
                        "status" => false,
                        "message" => "Password Salah",
                        "data" => $this->post("email")
                    ], 200);
                }
                
            }
            else $this->response([
                "status" => false,
                "message" => "tidak di temukan"
            ],400);
        } else{

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
}
