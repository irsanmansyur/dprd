<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Label extends Admin_Controller
{

    private $post = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/label_m');
        $this->load->model('admin/komisi_m');
        $this->load->library('form_validation');
        $this->post = $this->input->post();
        $this->get = $this->input->get();
    }
    public function index($id = null)
    {
        if (!$id || !isAccess(1)) {
            redirect('admin/user/blocked');
        }

        $this->komisi_m->setPrimaryKey($id);
        $this->data['komisi'] = $this->komisi_m->getWhere($this->komisi_m->getKeyName())->row_array();

        if (!$this->data['komisi']) {
            setNotif(0, "Anda baru saja Mengakses Kontent salah");
            redirect('admin/komisi');
        }
        $this->data['page']['title'] = 'Mengolah Data Label';
        $this->data['page']['description'] = 'Silahkan edit Label Atau Tambahkan Label Baru.!';
        $this->data['page']['before'] = ['url' => base_url('admin/komisi'), "title" => "Komisi"];
        $this->data['page']['submenu'] = 'Daftar Label';

        $validation = $this->form_validation;
        $this->label_m->addRules("label");
        $validation->set_rules($this->label_m->getRules());

        if ($validation->run() == false) {
            $tbl = initTable("web_komisi_label");
            $this->db->select($tbl['name'] . ".*");
            $this->db->from($tbl['name']);
            $this->db->join("web_komisi", "web_komisi_label.komisi_id=web_komisi.id_komisi");
            $this->db->where("komisi_id", $id);
            $this->db->order_by("label", "asc");
            $data = $this->db->get();
            if ($data) {
                $this->data['all_label'] = $data;
            } else
                return var_dump($data);
            $this->template->load('admin', 'komisi/label/index', $this->data);
        } else {
            $this->post['komisi_id'] = $id;



            /**
             * proses menghilankan kata awal dan akhir
             */
            $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
            $stemmer = $stemmerFactory->createStemmer();
            $sentence = $this->input->post('label');
            $outputstemmer = $stemmer->stem($sentence);



            /**
             * proses menghilankan kata yang tidak penting
             */
            $stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
            $stopword = $stopWordRemoverFactory->createStopWordRemover();
            $outputstopword = $stopword->remove($outputstemmer);

            $array = preg_split('/[^[:alnum:]]+/', strtolower($outputstopword));
            $arr_textmining = [];



            foreach ($array as $item) {
                if (array_key_exists($item, $arr_textmining)) {
                    $arr_textmining[$item]++;
                } else {
                    if (strlen($item) > 2) {
                        $tbl = initTable("web_komisi_label", "lbl");
                        $data = [
                            $tbl["key"] => $tbl['field'][$tbl["key"]],
                            "komisi_id" => $this->post['komisi_id'],
                            "label" => $item
                        ];
                        $this->db->insert($tbl['name'], $data);
                        $arr_textmining[$item] = 1;
                        $respon  = hasilCUD("Sukses Menambahkan Label");
                    }
                }
            }
            redirect('admin/komisi/label/' . $id);
        }
    }
    public function delete($id)
    {
        $komisi_id = $this->get['komisi_id'];

        $this->db->delete("web_komisi_label", ["id_label" => $id]);
        $eks = hasilCUD("Sukses Menghapus label");
        $tp = $eks->status ? "success" : "danger";
        $this->session->set_flashdata("message", "<div class='alert alert-$tp' role='alert'>$eks->message</div>");
        redirect(base_url('admin/komisi/label/' . $komisi_id));
    }
    public function edit($id)
    {
        $komisi_id = $this->get['komisi_id'];
        $this->label_m = $this->label_m;
        $validation = $this->form_validation;
        $this->label_m->addRules("label");
        $validation->set_rules($this->label_m->getRules());
        $this->label_m->setField($this->post);
        $this->label_m->setField($this->get);

        if ($validation->run() == false) {
            // redirect(base_url('admin/label/edit/'));
        } else {
            $this->post[$this->label_m->getKey()] = $id;
            $this->label_m->setField($this->post);
            $this->label_m->update();
            hasilCUD("Berhasil Update Label");
            redirect(base_url('admin/komisi/label/' . $komisi_id));
        }
    }
    public function reply($id)
    {
    }
    public function label($id)
    {
        $this->komisi_m = $this->komisi_m;
    }
}
