<?php
class Test  extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('template');
    }
    function index()
    {
        $data = [
            "join" => [] .
                ['join1', "join2"],
            ['join3', "join4"],
            "hahh",
            ['join5', "join6"]
        ];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                echo "Parent \"{$value[0]}\" Childrent: \"{$value[1]}\" </br>";
            } else {
                echo "table \"{$value}\"  bukan array</br>";
            }
        }
        $arr = $data;
        die();
        $this->template->load('public', 'test', $data);
    }

    function submenu()
    {
        $data['nama'] = "Irsan Mansyur";
        if (!$this->session->userdata('email')) {
            redirect('test');
        }
        $this->load->model('admin/menu_m');
        $data['menu'] = $this->menu_m->getMenuRoleId()->result_array();
        $this->template->load('public', 'index', $data);
    }
}
