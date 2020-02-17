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

class Kecamatan extends RestController
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

        $this->db->select("*");
        $this->db->from("web_kecamatan");

        $count = count($this->get());

        if ($count > 1) {
            foreach ($this->get() as $key => $val) {
                if ($key == "page") {
                    $page = $val;
                    $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                    $this->db->limit($halaman, $mulai);
                } else 
                    if ($val)
                    $this->db->where([$key => $val]);
            }
        }


        if ($id) {
            $this->db->where([
                "id_kec" => $id
            ]);
        }
        $kecamatan = $this->db->get()->result_array();

        if ($kecamatan) {

            $this->response([
                'status' => true,
                "message" => "User di temukan",
                "data" => $kecamatan
            ], 200);
        } else {
            $this->response([
                'status' => false,
                "message" => "User tidak di temukan"
            ], 200);
        }
    }
}
