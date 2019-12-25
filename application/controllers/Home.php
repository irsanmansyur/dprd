<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}


	public function index()
	{
		$validation = $this->form_validation;
		$aspirasi = $this->aspirasi_m;
		$validation->set_rules($aspirasi->getRules());
		if ($validation->run()) {
			$response = is_login('3');
			if (!$response->status) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-with-icon" data-notify="container"><i class="fa fa-volume-up" data-notify="icon"></i><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle"></i></button><span data-notify="message">Maaf Anda Harus Login Terlebih Dahulu.!</span></div>');
				$this->session->set_userdata('url', base_url());
				redirect(base_url("admin/auth/index/3"));
			} else {

				$post = $this->input->post();
				$post['user_id'] = $response->user['id_user'];
				$aspirasi->setFieldTable($post);
				$aspirasi->add();

				$respon = hasilCUD("Sukses mengirimkan Aspirasi Anda");
				die(var_dump($respon));

				redirect(base_url("user"));
			}
		} else {
			$this->visitor->setVisitor();
			$this->template->load("public", 'index', $this->data);
		}
	}
}
