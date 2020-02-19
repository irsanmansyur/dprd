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

class Aspirasi extends RestController
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

        $this->db->select("asp.*,tbl_user.image,tbl_user.name AS username,web_komisi.name AS komisi,(SELECT COUNT(id_komentar) FROM web_komentar AS kmt INNER JOIN tbl_user ON tbl_user.id_user=kmt.user_id where kmt.aspirasi_id=asp.id_aspirasi AND role_id!=2) AS jml_komentar, (SELECT name FROM tbl_user where tbl_user.id_user=asp.penanggun) AS nmpenanggun, (SELECT COUNT(id_komentar) FROM web_komentar AS kmt INNER JOIN tbl_user ON tbl_user.id_user=kmt.user_id where kmt.aspirasi_id=asp.id_aspirasi AND role_id=2) AS jml_tanggapan");
        $this->db->from("web_aspirasi asp");
        $this->db->join("web_komentar kmt", "kmt.aspirasi_id=asp.id_aspirasi", "left");
        $this->db->join("tbl_user", "tbl_user.id_user=asp.user_id");
        $this->db->join("web_komisi", "web_komisi.id_komisi=asp.komisi_id");
        $this->db->group_by("asp.id_aspirasi");
        if ($id) {
            $this->db->where(["asp.user_id" => $id]);
        }

        $page = 0;
        $total = 1;
        foreach ($this->get() as $key => $val) {

            if ($key == "page") {
                $page = $val;
            } elseif ($key == "baru") {
                $this->db->limit(10, 0);
            } elseif ($key == "total") {
                $total = is_int($val) ? $val : 1;
            } else
                $this->db->where([$key => $val]);
        }
        // is_int($page) ? $this->db->limit($total, $page) : '';
        $this->db->order_by('asp.id_aspirasi', "desc");
        $eks = $this->db->get();
        if ($eks) {
            $aspirasi = $eks->result_array();

            if ($aspirasi) {
                foreach ($aspirasi as $key => $val) {
                    $aspirasi[$key]['dateCreated'] = date("d/M/Y", $aspirasi[$key]['date_created']);
                    $aspirasi[$key]["image"] = getThumb($val['image']);
                }
                $this->response([
                    "status" => true,
                    "message" => "Aspirasi berhasil dimuat",
                    "data" => $aspirasi
                ], 200); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No Aspirasi were found'
                ], 200);
            }
        } else  $this->response([
            'status' => false,
            'message' => 'Kesalahan dalam Inputan'
        ], 400);
    }

    public function index_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules("message", "Pesan Aspiarsi", "required|min_length[5]");
        $this->form_validation->set_rules("user_id", "Id User ", "required|min_length[4]");
        $this->form_validation->set_rules("kec_id", "Id Kecamatan", "required|min_length[4]");

        if ($this->form_validation->run()) {
            $sentence = $this->post('message');
            /**
             * Tokenizing
             */
            $stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
            $stopword = $stopWordRemoverFactory->createStopWordRemover();
            $outputstopword = $stopword->remove($sentence);

            /**
             * Stemming
             * proses menghilankan kata awal dan akhir
             */
            $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
            $stemmer = $stemmerFactory->createStemmer();
            $outputstemmer = $stemmer->stem($outputstopword);



            /**
             * load model komisi
             * mengambil label setiap komisi
             */
            $this->load->model("komisi_m");
            $this->load->helper("cosine_helper");

            //menjadikan array Aspirasi setelah di Text Mining
            $array = preg_split('/[^[:alnum:]]+/', strtolower($outputstemmer));
            $arr_textmining = [];
            foreach ($array as $item) {
                if (array_key_exists($item, $arr_textmining)) {
                    $arr_textmining[$item]++;
                } else {
                    if (strlen($item) > 2)
                        $arr_textmining[$item] = 1;
                }
            }

            $all_komisi = $this->komisi_m->getKomisi();

            $max = 0;
            foreach ($all_komisi as $row) {
                $csn = null;

                $arr_label = $this->komisi_m->getLabel($row['id_komisi']);
                $str = lblString($arr_label, $arr_textmining);

                $array = preg_split('/[^[:alnum:]]+/', strtolower($str));
                foreach ($array as $item) {
                    if (strlen($item) > 2) {
                        @$csn[$item]++;
                    }
                }
                // var_dump($csn);
                if ($csn) {
                    $hasil = cosineSimilarity($arr_textmining, $csn);
                    @$cosine[$row['id_komisi']] = $hasil;
                    $hasil > $max ? $max = $hasil : '';
                } else {
                    @$cosine[$row['id_komisi']] = 0;
                }
            }

            $minGap = $max * 0.75;

            $dataku = [];

            if ($minGap > 0) {
                foreach ($cosine as $key => $value) {
                    if ($value >= $minGap) {
                        $this->db->select("web_komisi_user.*");
                        $this->db->from("web_komisi_user");
                        $this->db->join("web_kecamatan", "web_kecamatan.dapil_id=web_komisi_user.dapil_id");
                        $this->db->where([
                            "web_kecamatan.id_kec" => $this->post('kec_id'),
                            "web_komisi_user.komisi_id" => $key
                        ]);
                        $eks = $this->db->get()->result_array();
                        $penanggun = null;
                        if ($eks) {
                            $min = 0;
                            foreach ($eks as $row) {
                                $row['jumlah_tugas'] >= $min ? $penanggun = $row['user_id'] : '';
                            }
                        }
                        if (!$penanggun) {
                            $dt = $this->db->get_where("web_komisi_user", [
                                "komisi_id" => $key,
                                "jabatan" => "Ketua"
                            ])->row_array();
                            $penanggun = $dt['user_id'];
                        }
                        $tbl = initTable("web_aspirasi", "asp");
                        $data = [
                            $tbl['key'] => $tbl['field'][$tbl['key']],
                            "message" => $this->post('message'),
                            "date_created" => time(),
                            "user_id" => $this->post('user_id'),
                            "komisi_id" => $key,
                            "status" => $this->post('status') ? $this->post('status') : 4,
                            "penanggun" => $penanggun ? $penanggun : "user_001",
                            "kec_id" => $this->post('kec_id')
                        ];
                        $this->db->insert("web_aspirasi", $data);

                        $dataku[] = $this->getAsp($tbl['field'][$tbl['key']]);
                    }
                }
            } else {
                $tbl = initTable("web_aspirasi", "asp");
                $data = [
                    $tbl['key'] => $tbl['field'][$tbl['key']],
                    "message" => $this->post('message'),
                    "date_created" => time(),
                    "user_id" => $this->post('user_id'),
                    "komisi_id" => "kms_000",
                    "penanggun" => "user_001",
                    "kec_id" => $this->post('kec_id')
                ];
                $this->db->insert("web_aspirasi", $data);
                $dataku[] = $this->getAsp($tbl['field'][$tbl['key']]);
            }
            $respon = hasilCUD("Data Aspirasi Ditambahkan");
            if ($respon->status) {
                $this->response([
                    'status' => true,
                    "message" => $respon->message,
                    "data" => $dataku
                ], 200);
            } else
                $this->response([
                    'status' => false,
                    "message" => $respon->message,
                    "data" => $dataku
                ], 404);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Lengkapi data dulu',
                "dataErrors" => $this->form_validation->error_array()
            ], 404); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function index_put()
    {
        $tbl = initTable("web_aspirasi", "asp");
        $where = $this->input->get();
        if (count($where) > 0) {
            $this->db->where($where);
            $update = $this->db->update($tbl['name'], $this->put());
            if ($update) {
                $respon = hasilCUD("Data Berhasil Di Update");
                if ($respon->status)
                    $this->response($respon, 201);
                else
                    $this->response($respon, 200);
            } else {
                $this->response(['status' => false], 400);
            }
        } else
            $this->response(['status' => false, 'message' => "Update Ditolak, Ada kesalahan.!"], 500);
    }
    public function index_delete($id = null)
    {
        $where = $this->input->get();
        if (count($this->delete()) > 0) {
            foreach ($this->delete() as $row => $value) {
                $where[$row] = $value;
            }
        }
        if ($id) {
            $where['id_aspirasi'] = $id;
        }

        $respon = $this->db->delete("web_aspirasi", $where);
        if ($respon) {
            $eks = hasilCUD("deleted.!");
            $this->response($eks, 200);
        } else {
            $this->response([
                'status' => false,
                "message" => "Terjadi Kesalahan"
            ], 502); // BAD_REQUEST (400) being the HTTP response code
        }
    }

    public function getAsp($id)
    {
        $this->db->select("asp.*,tbl_user.image,tbl_user.name AS username,web_komisi.name AS komisi,(SELECT COUNT(id_komentar) FROM web_komentar AS kmt INNER JOIN tbl_user ON tbl_user.id_user=kmt.user_id where kmt.aspirasi_id=asp.id_aspirasi AND role_id!=2) AS jml_komentar, (SELECT name FROM tbl_user where tbl_user.id_user=asp.penanggun) AS nmpenanggun, (SELECT COUNT(id_komentar) FROM web_komentar AS kmt INNER JOIN tbl_user ON tbl_user.id_user=kmt.user_id where kmt.aspirasi_id=asp.id_aspirasi AND role_id=2) AS jml_tanggapan");
        $this->db->from("web_aspirasi asp");
        $this->db->join("web_komentar kmt", "kmt.aspirasi_id=asp.id_aspirasi", "left");
        $this->db->join("tbl_user", "tbl_user.id_user=asp.user_id");
        $this->db->join("web_komisi", "web_komisi.id_komisi=asp.komisi_id");
        $this->db->group_by("asp.id_aspirasi");
        $this->db->where("asp.id_aspirasi", $id);
        $aspirasi = $this->db->get()->row_array();
        $aspirasi['dateCreated'] = date("d/M/Y", $aspirasi['date_created']);
        return $aspirasi;
    }
}
