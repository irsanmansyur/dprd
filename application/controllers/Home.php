<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$response = is_login(3);
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


				/**
				 * proses menghilankan kata awal dan akhir
				 */
				$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
				$stemmer = $stemmerFactory->createStemmer();
				$sentence = $this->input->post('message');
				$outputstemmer = $stemmer->stem($sentence);



				/**
				 * proses menghilankan kata yang tidak penting
				 */
				$stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
				$stopword = $stopWordRemoverFactory->createStopWordRemover();
				$outputstopword = $stopword->remove($outputstemmer);


				//mengambil fungsional setiap komisi
				//load model komisi
				$this->load->model("komisi_m");
				$this->load->helper("cosine_helper");

				$array = preg_split('/[^[:alnum:]]+/', strtolower($outputstopword));
				$arr_textmining = [];
				foreach ($array as $item) {
					if (array_key_exists($item, $arr_textmining)) {
						$arr_textmining[$item]++;
					} else {
						if (strlen($item) > 2)
							$arr_textmining[$item] = 1;
					}
				}
				var_dump($arr_textmining);
				$all_komisi = $this->komisi_m->getKomisi();
				foreach ($all_komisi as $row) {
					$csn = null;
					$str = lblString($this->komisi_m->getLabel($row['id_komisi']));
					$array = preg_split('/[^[:alnum:]]+/', strtolower($str));
					foreach ($array as $item) {
						if (strlen($item) > 2) {
							@$csn[$item]++;
						}
					}
					if ($row['id_komisi'] != "kms_000")
						@$cosine[$row['id_komisi']] = cosineSimilarity($arr_textmining, $csn);
				}
				$kms_id = null;
				$max = 0;
				foreach ($cosine as $key => $value) {
					$max > $value ? '' : [$max = $value, $kms_id = $key];
				}
				$max == 0 ? $kms_id = "kms_000" : '';
				$aspirasi = initTable("web_aspirasi", 'asp');

				$data = [
					"id_aspirasi" => $aspirasi['field'][$aspirasi['key']],
					'message' => $this->input->post('message'),
					'status' => 3,
					'user_id' => $response->user['id_user'],
					'komisi_id' => $kms_id
				];
				$this->db->insert("web_aspirasi", $data);
				$this->session->set_flashdata('message', '<div style="position: absolute; top:50px; right: 5px;z-index:9999999"><div class="toast fade show"><div class="toast-header"><strong class="mr-auto"><i class="fa fa-globe"></i>  Aspirasi Anda Berhasil Dikirim!</strong><small class="text-muted">just now</small><button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button></div><div class="toast-body">Pantau akun anda untuk menerima tanggapan atau komentar.!</div></div></div>');
				redirect(base_url("user"));
			}
		} else {
			$this->data['page']['title'] = 'Selamat datang.! Silahkan Kirim Keluhan atau aspirasi anda disini.!';

			$this->visitor->setVisitor();
			$this->template->load("public", 'index', $this->data);
		}
	}
}
