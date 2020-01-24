<?php
defined('BASEPATH') or exit('No direct script access allowed');
class komisi_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
    }
    function getLabel($idKomisi)
    {
        $this->db->select("label");
        $this->db->from("web_komisi_label");
        $this->db->where("komisi_id", $idKomisi);
        return $this->db->get()->result_array();
    }
    function getkomisi()
    {
        return $this->db->get("web_komisi")->result_array();
    }
}
