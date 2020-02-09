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

class Komisi extends RestController
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

    public function user_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules("komisi_id", "Nama Komisi", "required|min_length[5]");
        $this->form_validation->set_rules("user_id", "Nama User", "required|min_length[5]");
        $this->form_validation->set_rules("dapil_id", "Nama Dapil", "required|min_length[5]");
        $this->form_validation->set_rules("jabatan", "Nama Jabatan", "required");
        if ($this->form_validation->run()) {
            $tbl = initTable("web_komisi_user", "komUs");
            $data = [
                $tbl['key'] => $tbl["field"][$tbl['key']],
                "komisi_id" => $this->post("komisi_id"),
                "dapil_id" => $this->post("dapil_id"),
                "user_id" => $this->post("user_id"),
                "jabatan" => $this->post("jabatan")
            ];
            $this->db->insert($tbl['name'], $data);
            $respon = hasilCUD("Berhasil Menambahkan User ke Komisi");
            $respon->data = $data;
            if ($respon->status) {
                $this->response($respon, 201);
            } else
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
}
