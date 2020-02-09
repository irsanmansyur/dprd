<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
	public function index()
	{
		$this->load->model('visitor_m');
		$dt = $this->visitor_m->getVisitor()->result_array();
		$this->template->load('admin', 'user/dashboard', $this->data);
	}

	function getLog()
	{
		$this->data['log'] = $this->log_model->getId()->result_array();
		$this->template->load('admin', 'user/log/test', $this->data);
	}
	function profile()
	{
		$user = $this->tbl;
		$user->setTable("tbl_user", "user");
		$user->setField($this->data['user']);

		$validation = $this->form_validation;
		$user->addRules("tgl_lahir");

		$validation->set_rules($user->getRules());
		if (!$validation->run()) {

			$this->data['page']['title'] = 'Profile User';
			$this->data['page']['description'] = 'Silahkan lihat data profile anda, dan ubah jika ada yang tidak sesuai dengan anda, </br> Inggat data harus real.!';
			// $this->data['page']['before'] = ['url' => base_url('admin/menu'), "title" => "Menu Access"];
			$this->data['page']['submenu'] = 'Profile User';
			$this->template->load('admin', 'user/profile/index', $this->data);
		} else {

			$status = false;

			$upload_image = $_FILES['image']['name'];
			$post = $this->input->post();

			/**
			 * Change format Time
			 */
			list($m, $d, $y) = explode("/", $post['tgl_lahir']);
			$date  =  "{$d}-{$m}-{$y}";
			$timestamp = strtotime($date);
			$post['tgl_lahir'] = $timestamp;

			$fileName = $this->data['user']['image'];

			if ($upload_image) {
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size']      = '3048';
				$config['upload_path'] = './assets/img/profile/';
				$this->load->library('upload', $config);

				if ($this->upload->do_upload('image')) {

					// cretate thumbnail image
					if ($fileName != "default.png") //file default
					{
						if (is_file(FCPATH . 'assets/img/profile/' . $fileName))
							unlink(FCPATH . 'assets/img/profile/' . $fileName);
						if (is_file(FCPATH . 'assets/img/thumbnail/profile_' . $fileName))
							unlink(FCPATH . 'assets/img/thumbnail/profile_' . $fileName);
					}
					$fileName = $this->upload->data('file_name');
					_img_create_thumbs($fileName, "profile");

					$msg[] = "Foto Profil Updated";
				} else {
					$msg[] = $this->upload->display_errors();
				}
			}
			$post['image'] = $fileName;
			$this->db->update("tbl_user", $post, ['id_user' => $this->data['user']['id_user']]);

			$eks = hasilCUD("Sukses Update");
			$msg[] = $eks->message;
			setNotif($eks->status, $msg);
			header("Refresh:0");
		}
	}

	function changepassword()
	{
		$this->form_validation->set_rules('newPassword', 'Password', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('password', 'Repeat Password', 'trim|required|min_length[3]|matches[newPassword]');

		if ($this->form_validation->run() == false) {
			$this->data['page']['title'] = 'Mengganti Password';
			$this->data['page']['description'] = 'Silahkan ubah password lama anda, gunakan karakter yang susah di tebak.!';
			// $this->data['page']['before'] = ['url' => base_url('admin/menu'), "title" => "Menu Access"];
			$this->data['page']['submenu'] = 'Ganti password';

			$this->template->load('admin', 'user/profile/changepassword', $this->data);
		} else {
			$oldPassword = $this->input->post('oldPassword');
			// cek kebenran password lama
			$user = $this->db->get_where("tbl_user", ["id_user" => $this->data['user']['id_user']])->row_array();
			if (password_verify($oldPassword, $user['password'])) {
				$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
				$email = $user['email'];
				$this->db->set('password', $password);
				$this->db->where('email', $email);
				$this->db->update('tbl_user');
				if ($this->db->affected_rows() > 0) {
					$this->session->unset_userdata('email');
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password has been changed! Please login.</div>');
					redirect('admin/auth');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal.!</div>');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password lama tidak cocok.!</div>');
				redirect('admin/user/changepassword');
			}
		}
	}

	public function laporan_pdf()
	{
		$this->load->model('publikasi_model', 'publikasi');
		$this->load->model('pendidikan_model', 'pendd');
		$this->load->model('jabatan_model', 'jabatan');
		$this->load->model('pelatihan_model', 'pelatihan');

		$this->data['get_publikasi'] = $this->publikasi->getId()->result_array();
		$this->data['get_jabatan'] = $this->jabatan->getId()->result_array();
		$this->data['get_pelatihan'] = $this->pelatihan->getId()->result_array();
		$this->data['get_pendd'] = $this->pendd->getId([
			'pendd_user.user_id' => $this->data['user']['id']
		])->result_array();

		$this->load->library('pdf');

		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-petanikode.pdf";

		$html = $this->load->view('index2', $this->data);
		// $this->pdf->load_html($html);
		// $this->pdf->render();
		// $this->pdf->stream($this->pdf->filename, array("Attachment" => false));
	}


	/**
	 * membuat file baru dari assets milik web sendiri
	 * berdasarkan config pada array , jika 
	 * ingin membuat duplictae gambar
	 * yang kecil atau besar
	 */
	function _create_thumbs($file_name)
	{
		// Image resizing config
		$config = array(
			// Large Image
			// array(
			//     'image_library' => 'GD2',
			//     'source_image'  => './assets/images/' . $file_name,
			//     'maintain_ratio' => FALSE,
			//     'width'         => 700,
			//     'height'        => 467,
			//     'new_image'     => './assets/images/large/' . $file_name
			// ),
			// Medium Image
			// array(
			//     'image_library' => 'GD2',
			//     'source_image'  => './assets/images/' . $file_name,
			//     'maintain_ratio' => FALSE,
			//     'width'         => 600,
			//     'height'        => 400,
			//     'new_image'     => './assets/images/medium/' . $file_name
			// ),
			// Small Image
			array(
				'image_library' => 'GD2',
				'source_image'  => './assets/img/profile/' . $file_name,
				'maintain_ratio' => FALSE,
				'width'         => 100,
				'height'        => 100,
				'new_image'     => './assets/img/thumbnail/profile_' . $file_name
			)
		);

		$this->load->library('image_lib', $config[0]);
		foreach ($config as $item) {
			$this->image_lib->initialize($item);
			if (!$this->image_lib->resize()) {
				return false;
			}
			$this->image_lib->clear();
		}
	}

	function notif()
	{
		$this->template->load('admin', 'admin/notif', $this->data);
	}
	function notif_action()
	{
		$link = 'admin/notif';
		$this->data['get_notif'] =  $this->notif_model->getId()->row_array();
		$read = $this->notif_model->read();
		$this->template->load('admin', 'admin/notif_action', $this->data);
	}
	function blocked()
	{
		$this->data['page']['title'] = 'Maaf Anda Di Larang Mengakses Halaman sebelumnya';
		$this->data['page']['description'] = 'Silahkan pilih menu yang sesuai dengan hak akses anda.!';
		$this->data['page']['submenu'] = 'User Blocked Access';

		$this->template->load('admin', 'error/blocked', $this->data);
	}
	function log()
	{
	}
	function setting()
	{
		foreach ($_POST as $key => $value) {
			$where['name'] = htmlspecialchars($key);
			$data['title'] = htmlspecialchars($value);
		}
		$this->setting_m->update($where, $data);
	}
}
