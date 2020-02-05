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
class Komentar extends RestController
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

        $this->load->model('komentar_m');
    }

    public function index_get()
    {
        $this->db->select("web_komentar.*,tbl_user_file.file,tbl_user.role_id,tbl_user.name AS username");
        $this->db->from("web_komentar");
        $this->db->join("tbl_user", "tbl_user.id_user=web_komentar.user_id");
        $this->db->join("tbl_user_file", "tbl_user.file_id=tbl_user_file.id_file");
        count($this->get()) > 0 ? $this->db->where($this->get()) : '';
        $eks = $this->db->get();
        if ($eks) {
            $komentar = $eks->result_array();
            if ($komentar) {
                foreach ($komentar as $key => $val) {
                    $komentar[$key]["file"] = getThumb($val['file']);
                }
                $this->response([
                    "status" => true,
                    "data" => $komentar
                ], 200); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No komentar were found'
                ], 200);
            }
        } else  $this->response([
            'status' => false,
            'message' => 'Kesalahan dalam Inputan'
        ], 400);
    }

    public function index_post()
    {
        $this->form_validation->set_rules("komentar", "Komentar", "required|min_length[5]");
        $this->form_validation->set_rules("user_id", "Id User", "required|min_length[4]");
        $this->form_validation->set_rules("aspirasi_id", "Id aspirasi", "required|min_length[4]");
        if ($this->form_validation->run()) {
            $tbl = initTable("web_komentar", "kmt");
            $data = [
                $tbl['key'] => $tbl['field'][$tbl['key']],
                "komentar" => $this->post('komentar'),
                "user_id" => $this->post('user_id'),
                "aspirasi_id" => $this->post('aspirasi_id'),
                "date_created" => time()
            ];
            $this->post('parent') ? $data['parent'] = $this->post('parent') : '';
            $this->db->insert($tbl['name'], $data);
            $respon = hasilCUD("Komentar Ditambahkan");
            if ($respon->status) {
                $this->response($respon, 201);
            } else
                $this->response($respon, 400);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Lengkapi data dulu',
                "dataErrors" => validation_errors('<span class="error">', '</span>')
            ], 200);
        }
    }

    public function index_put()
    {
        $tbl = initTable("web_komentar", "kmt");
        $where = $this->input->get();
        $data = $this->put();
        $data['date_created'] = time();
        if (count($where) > 0) {
            $this->db->where($where);
            $update = $this->db->update($tbl['name'], $data);
            if ($update) {
                $respon = hasilCUD("Data Berhasil Di Update");
                if ($respon->status)
                    $this->response($respon, 201);
                else
                    $this->response($respon, 304);
            } else {
                $this->response(['status' => false], 400);
            }
        }
    }
    public function index_delete()
    {
        $where = $this->input->get();
        if (count($this->delete()) > 0) {
            foreach ($this->delete() as $row => $value) {
                $where[$row] = $value;
            }
        }

        $respon = $this->db->delete("web_komentar", $where);
        if ($respon) {
            $eks = hasilCUD("deleted.!");
            if ($eks->status) {
                $this->response($eks, 200);
            } else $this->response($eks, 304);
        } else {
            $this->response([
                'status' => false,
                "message" => "Terjadi Kesalahan"
            ], 500); // BAD_REQUEST (400) being the HTTP response code
        }
    }
}
