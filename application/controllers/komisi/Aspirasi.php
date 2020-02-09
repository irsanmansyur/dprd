<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aspirasi extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->post = $this->input->post();
        $this->get = $this->input->get();
    }
    public function index()
    {
        $this->data['page']['title'] = 'Daftar Aspirasi Yang Masuk';
        $this->data['page']['description'] = 'Silahkan edit menu Atau Tambahkan Komisi.!';
        // $this->data['page']['before'] = ['url' => base_url('admin/admin/role'), "title" => "Role Access"];
        $this->data['page']['submenu'] = 'Menu Komisi';


        $this->db->select("web_komisi_user.*");
        $this->db->from("web_komisi_user");
        $this->db->where("web_komisi_user.user_id", $this->data['user']['id_user']);
        $eks = $this->db->get()->row_array();
        $this->data['komisi'] = $eks;

        $this->template->load('admin', 'aspirasi/komisi', $this->data);
    }
    public function delete($id)
    {
        $komisi = $this->komisi_m;
        $komisi->setPrimaryKey($id);
        $komisi->deleted();
        hasilCUD("Sukses Menghapus Komisi");
        redirect(base_url('admin/komisi'));
    }
    public function edit($id)
    {
        $komisi = $this->komisi_m;
        $this->post[$komisi->getKeyName()] = $id;
        $komisi->setFieldTable($this->post);
        $komisi->update();
        hasilCUD("Berhasil Update Komisi");
        redirect(base_url('admin/komisi'));
    }
    public function reply($id)
    {
    }
    public function user()
    {
        $this->data['page']['title'] = 'Mengolah data User Komisi';
        $this->data['page']['description'] = 'Silahkan edit User Komisi dan menonaktifkan atau mengaktifkan.!';
        $this->data['page']['before'] = ['url' => base_url('admin/komisi'), "title" => "Komisi"];
        $this->data['page']['submenu'] = 'Daftar User untuk komisi';
        $user = $this->tbl;
        $user->setTable("tbl_user", "user");
        $field = [
            "role_id" => "2"
        ];
        $user->setField($field);
        $eks = $user->getWhere("role_id", [
            "join" => [
                ["tbl_user_file", 'tbl_user']
            ],
            "select" => "tbl_user_file.image",
            "start" => 0
        ]);
        $komisi = $this->tbl;
        $komisi->setTable("web_komisi");
        $kms  = $komisi->getWhere();
        if ($eks->status) {
            $this->data['all_komisi'] = $kms->data;
            $this->data['komisi_user'] = $eks->data;
            $this->template->load('admin', 'komisi/user', $this->data);
        } else
            var_dump($eks);
    }

    public function userSet()
    {
        $user =  new $this->tbl;
        $user->setTable("tbl_user", "user");
        // $data = [
        //     "id_user" => "user_002",
        //     "is_active" => "1"
        // ];
        // $user->setField($data);
        $user->setField($this->post);

        $user->selectField("is_active");
        $eks = $user->update();
        echo json_encode($eks);
    }

    function setkomisi()
    {

        $komisi = new $this->tbl; //load new model
        $komisi->setTable("web_komisi_user", "k_u_a"); // load table dan key exp: k_u_a_001

        $data = [
            "user_id" => $this->post['user_id'],
            "komisi_id" =>  $this->post['komisi_id'],
            'status' => 1
        ];


        $tbl = "web_komisi_user";
        $response  = (object) ['status' => false, 'message' => 'kesalahan Sistem'];
        // $eks  = $this->db->get_where($tbl, ["user_id" => $this->post['user_id']]);
        $eks  = $this->db->get_where($tbl, ["user_id" => $data['user_id']]);
        $eks ? [
            $eks->num_rows() < 1 ? [
                $data[$komisi->getKey()] = $komisi->getLastKey(),
                $this->db->insert($tbl, $data),
                $response = hasilCUD("Sukses Menambah User Ke komisi.!")
            ] : [
                $this->db->update($tbl, $data, ["user_id" => $data['user_id']]),
                $response = hasilCUD("Sukses Mengubah komisi User.!")
            ]
        ] : [];
        echo json_encode($response);
    }

    function userDelete($id)
    {
        $user = $this->tbl;
        $user->setTable("tbl_user", "user");
        $user->setPrimaryKey($id);
        $eks = $user->deleted();
        echo json_encode($eks);
    }
}
