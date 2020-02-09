<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aspirasi extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->data['page']['title'] = 'Managemen Aspirasi Masyarakat';
        $this->data['page']['description'] = 'Silahkan edit Atau Hapus jika ada kesalahan Komisi.!';
        // $this->data['page']['before'] = ['url' => base_url('admin/admin/role'), "title" => "Role Access"];
        $this->data['page']['submenu'] = 'Daftar Aspirasi';

        $this->data['all_komisi'] = $this->db->get("web_komisi")->result_array();
        $this->template->load('admin', 'aspirasi/admin', $this->data);
    }
}
