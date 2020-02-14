<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/komisi_m');
        $this->load->library('form_validation');
        $this->post = $this->input->post();
        $this->get = $this->input->get();
    }
    public function index()
    {
        $this->data['page']['title'] = 'Mengolah data User Komisi';
        $this->data['page']['description'] = 'Silahkan edit User Komisi dan menonaktifkan atau mengaktifkan.!';
        $this->data['page']['before'] = ['url' => base_url('admin/komisi'), "title" => "Komisi"];
        $this->data['page']['submenu'] = 'Daftar User untuk komisi';


        $this->db->select("tbl_user.*,web_komisi_user.*,web_komisi.name AS komisi_name");
        $this->db->from("tbl_user");
        $this->db->join("web_komisi_user", "tbl_user.id_user=web_komisi_user.user_id", "left");
        $this->db->join("web_komisi", "web_komisi.id_komisi=web_komisi_user.komisi_id");
        $this->db->where([
            'role_id' => 2
        ]);
        $user = $this->db->get()->result_array();
        $allKomisi = $this->db->get_where("web_komisi", ["id_komisi!=" => "kms_000"])->result_array();
        $allDapil = $this->db->get("web_dapil")->result_array();
        $this->data['all_komisi'] = $allKomisi;
        $this->data['all_dapil'] = $allDapil;
        $this->data['komisi_user'] = $user;
        $this->template->load('admin', 'komisi/user', $this->data);
    }
    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("name", "Nama Lengkap", "required|min_length[5]");
        $this->form_validation->set_rules("email", "email", "required|is_unique[tbl_user.email]");
        $this->form_validation->set_rules("no_hp", "No Handphone", "required|numeric");
        $this->form_validation->set_rules("alamat", "Alamat", "required");
        $this->form_validation->set_rules("password", "Password", "required");
        $this->form_validation->set_rules("role_id", "role id", "required");
        $this->form_validation->set_rules("dapil_id", "Dapil", "required");
        $this->form_validation->set_rules("komisi_id", "Komisi", "required");
        $this->form_validation->set_rules("jabatan", "jabatan", "required");
        if ($this->form_validation->run()) {
            $post = $this->input->post();
            $image = "default.png";
            if (isset($_FILES['image'])) {
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = '3048';
                $config['upload_path'] = './assets/img/profile/';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image')) {
                    $image = $this->upload->data('file_name');

                    /**
                     * Helper CI
                     * Create thumb image with 
                     * _img_create_thumbs({nama file image}, {Folder file gambar});
                     */
                    _img_create_thumbs($image, "profile");
                }
            }
            $tblUser = initTable("tbl_user", "user");
            $tblUser['lastkey'] = $tblUser['field'][$tblUser['key']];

            $data = [
                $tblUser['key'] =>  $tblUser['lastkey'],
                "name" => $post['name'],
                "email" => $post['email'],
                "no_hp" => $post['no_hp'],
                "alamat" => $post['alamat'],
                "role_id" =>  $this->input->post('role_id') ? $this->input->post('role_id') : 3,
                "is_active" => $this->input->post('is_active') ? $post['is_active'] : 0,
                "image" => $image,
                "password" => password_hash($post['password'], PASSWORD_DEFAULT)
            ];
            $this->db->insert($tblUser['name'], $data);

            $tbl = initTable("web_komisi_user", "kom_u");
            $this->db->insert($tbl['name'], [
                $tbl['key'] => $tbl['field'][$tbl['key']],
                "komisi_id" => $this->input->post("komisi_id"),
                "dapil_id" => $this->input->post("dapil_id"),
                "user_id" => $data["id_user"],
                "jabatan" => $this->input->post("jabatan"),
            ]);
            $respon = hasilCUD("Data User Ditambahkan");
            $type = $respon->status ? "success" : "danger";
            $this->session->set_flashdata('message', '<div class="alert alert-' . $type . '" role="alert">' . $respon->message . '!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal..!</div>');
        }
        redirect("admin/komisi/user");
    }
    function edit($id)
    {
        $user = $this->db->get_where("tbl_user", ['id_user' => $id])->row_array();
        if ($user) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules("name", "Nama Lengkap", "required|min_length[5]");
            $this->form_validation->set_rules("email", "email", "required");
            $this->form_validation->set_rules("no_hp", "No Handphone", "required|numeric");
            $this->form_validation->set_rules("alamat", "Alamat", "required");
            $this->form_validation->set_rules("password", "Password", "required");
            $this->form_validation->set_rules("role_id", "role id", "required");
            $this->form_validation->set_rules("dapil_id", "Dapil", "required");
            $this->form_validation->set_rules("komisi_id", "Komisi", "required");
            $this->form_validation->set_rules("jabatan", "jabatan", "required");
            if ($this->form_validation->run()) {
                $post = $this->input->post();
                $image = $user['image'];
                if (isset($_FILES['image'])) {
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['max_size']      = '3048';
                    $config['upload_path'] = './assets/img/profile/';
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('image')) {
                        $image = $this->upload->data('file_name');

                        /**
                         * Helper CI
                         * Create thumb image with 
                         * _img_create_thumbs({nama file image}, {Folder file gambar});
                         */
                        _img_create_thumbs($image, "profile");
                    }
                }
                $tblUser = initTable("tbl_user", "user");
                $tblUser['lastkey'] = $tblUser['field'][$tblUser['key']];

                $data = [];
                foreach ($tblUser['field'] as $key => $val) {
                    if (array_key_exists($key, $this->input->post())) {
                        $data[$key] =  $key == "password" ? password_hash($post['password'], PASSWORD_DEFAULT) :  $this->input->post($key);
                    }
                }
                $data['image'] = $image;
                $this->db->update($tblUser['name'], $data, ["id_user" => $id]);


                $tbl = initTable("web_komisi_user", "kom_u");
                $data = [];
                foreach ($tbl['field'] as $key => $val) {
                    if (array_key_exists($key, $this->input->post())) {
                        $data[$key] = $this->input->post($key);
                    }
                }
                $this->db->update("web_komisi_user", $data, ["user_id" => $id]);

                $respon = hasilCUD("Data User Di Update");
                $type = $respon->status ? "success" : "danger";
                $this->session->set_flashdata('message', '<div class="alert alert-' . $type . '" role="alert">' . $respon->message . '!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal..!</div>');
            }
        } else {
            $this->session->set_flashdata("message", "<div class='alert alert-danger' role='alert'>User Tidak Di temukan</div>");
        }

        redirect("admin/komisi/user");
    }
    function delete($id)
    {
        $user = $this->db->get_where("tbl_user", ["id_user" => $id])->row_array();
        if ($user) {
            $this->db->delete("tbl_user", ["id_user" => $id]);
            $res = hasilCUD("User Di Hapus");
            if ($res->status) {
                if ($user['image'] != "default.png") {
                    deleteImg("profile", $user['image']);
                }
            }
            $type = $res->status ? "success" : "danger";
            $this->session->set_flashdata("message", "<div class='alert alert-$type' role='alert'>$res->message</div>");
        } else {
            $this->session->set_flashdata("message", "<div class='alert alert-danger' role='alert'>User Tidak ditemukan</div>");
        }
        redirect("admin/komisi/user");
    }
    function active($id)
    {
        $user = $this->db->get_where("tbl_user", ["id_user" => $id])->row_array();
        if ($user) {
            return json_encode(["status" => true, "message" => "Sukses"]);
        } else {
            return json_encode(["status" => false, "message" => "gagal"]);
        }
    }
}
