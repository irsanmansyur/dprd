<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->load->library("form_validation");
		$this->form_validation->set_rules('search', 'Searching', 'required');
		if ($this->form_validation->run()) {
			$search = $this->input->post('search');
			$this->load->model("admin/menu_m");
			$this->data['sub_menu'] = $this->menu_m->getSearchSubmenu(["tbl_user_sub_menu.title" => $search])->result_array();
			$this->template->load('admin', 'search', $this->data);
		} else redirect(base_url('admin/dashboard'));
	}
}
