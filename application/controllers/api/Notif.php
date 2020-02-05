<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\RestController;

require(APPPATH . 'libraries/RestController.php');

class Notif extends RestController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index_get()
    {
        $id_user = $this->get('user_id');
        if (!$id_user) {
            $this->response([
                "status" => false,
                "data" => []
            ], 404); // OK (200) being the HTTP response code
        }
        $this->db->select('web_komentar.*,tbl_user_role.name as namerole,web_aspirasi.message,tbl_user.name,tbl_user_file.file');
        $this->db->from("web_komentar");
        $this->db->join("tbl_user", "tbl_user.id_user=web_komentar.user_id");
        $this->db->join('web_aspirasi', "web_aspirasi.id_aspirasi=web_komentar.aspirasi_id");
        $this->db->join("tbl_user_file", "tbl_user_file.id_file=tbl_user.file_id");
        $this->db->join('tbl_user_role', 'tbl_user_role.id=tbl_user.role_id');
        $this->db->where(['web_aspirasi.user_id' => $id_user, 'web_komentar.type' => 0]);
        $this->db->order_by('web_komentar.id_komentar', 'desc');

        $res = $this->db->get();
        if ($res) {
            $this->response([
                "status" => true,
                "data" => $res->result_array()
            ], 200); // OK (200) being the HTTP response code
        } else
            $this->response([
                "status" => false,
                "data" => []
            ], 400); // OK (200) being the HTTP response code
    }
}
