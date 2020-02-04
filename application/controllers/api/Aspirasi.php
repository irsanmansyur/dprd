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

class Aspirasi extends RestController
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

        $this->db->select("asp.*,tbl_user.name AS username,tbl_user_file.file,web_komisi.name AS komisi,(SELECT COUNT(id_komentar) FROM web_komentar AS kmt INNER JOIN tbl_user ON tbl_user.id_user=kmt.user_id where kmt.aspirasi_id=asp.id_aspirasi AND role_id!=2) AS jml_komentar,(SELECT COUNT(id_komentar) FROM web_komentar AS kmt INNER JOIN tbl_user ON tbl_user.id_user=kmt.user_id where kmt.aspirasi_id=asp.id_aspirasi AND role_id=2) AS jml_tanggapan");
        $this->db->from("web_aspirasi asp");
        $this->db->join("web_komentar kmt", "kmt.aspirasi_id=asp.id_aspirasi", "left");
        $this->db->join("tbl_user", "tbl_user.id_user=asp.user_id");
        $this->db->join("tbl_user_file", "tbl_user.file_id=tbl_user_file.id_file");
        $this->db->join("web_komisi", "web_komisi.id_komisi=asp.komisi_id");
        $this->db->group_by("asp.id_aspirasi");


        $page = 0;
        $total = 1;
        foreach ($this->get() as $key => $val) {

            if ($key == "page") {
                $page = $val;
            } elseif ($key == "baru") {
                $this->db->limit(10, 0);
            } elseif ($key == "total") {
                $total = is_int($val) ? $val : 1;
            } else
                $this->db->where([$key => $val]);
        }
        // is_int($page) ? $this->db->limit($total, $page) : '';
        $this->db->order_by('asp.id_aspirasi', "desc");
        $eks = $this->db->get();
        if ($eks) {
            $aspirasi = $eks->result_array();
            if ($aspirasi) {
                $this->response([
                    "status" => true,
                    "data" => $aspirasi
                ], 200); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No Aspirasi were found'
                ], 200);
            }
        } else  $this->response([
            'status' => false,
            'message' => 'Kesalahan dalam Inputan'
        ], 400);
    }

    public function index_post()
    {
        $this->form_validation->set_rules("message", "Pesan Aspiarsi", "required|min_length[5]");
        $this->form_validation->set_rules("user_id", "Id User", "required|min_length[4]");
        $this->form_validation->set_rules("komisi_id", "Id Komisi", "required|min_length[4]");
        $this->form_validation->set_rules("status", "Status Aspirasi", "in_list[3,4]");
        if ($this->form_validation->run()) {
            $tbl = initTable("web_aspirasi", "asp");
            $data = [
                $tbl['key'] => $tbl['field'][$tbl['key']],
                "message" => $this->post('message'),
                "user_id" => $this->post('user_id'),
                "komisi_id" => $this->post('komisi_id')
            ];
            $this->db->insert("web_aspirasi", $data);
            $respon = hasilCUD("Data Aspirasi Ditambahkan");
            if ($respon->status) {
                $this->response($respon, 200);
            } else
                $this->response($respon, 404);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Lengkapi data dulu',
                "dataErrors" => validation_errors('<span class="error">', '</span>')
            ], 404); // NOT_FOUND (404) being the HTTP response code
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
