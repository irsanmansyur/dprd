<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends Admin_Controller
{
    // ==
    // == == CM_SHOW
    // ==

    //Menampilkan Dashboard
    function index()
    {
        $file = $this->file_model;
        $this->data['jumlah_user'] =  $this->user_model->getAll()->num_rows();
        $this->data['jumlah_submenu'] =  $this->menu_m->getSubmenu()->num_rows();
        $this->data['jumlah_menu'] =  $this->menu_m->getMenu()->num_rows();
        $this->data['jumlah_file'] =  $file->getAll()->num_rows();


        $visitor = $this->visitor_m->getVisitor()->result_array();
        foreach ($visitor as $row) {
            $this->data['days'][] = $row['date_created'];
            $this->data['count'][] = $row['Count'];
        }
        $this->template->load('admin', 'dashboard', $this->data);
    }

    function test()
    {
    }




    // CM_show
    function setting()
    {
        $this->data['setting'] = $this->setting_m->get()->result();

        $this->load->library('form_validation');
        foreach ($this->data['setting'] as $row) {
            $this->form_validation->set_rules($row->name, $row->name, 'trim|required');
        }
        if ($this->form_validation->run()) {
            foreach ($this->input->post() as $row => $value) {
                $where = ['name' => $row];
                $data = ['title' => $value];
                $this->setting_m->update($where, $data);
            }
            $this->session->set_flashdata('message', "<div class='mb-5 alert alert-success' role='alert'>Sukses Update Pengaturan .!</div>");

            header("Refresh:0");
        } else {
            $this->data['themes_admin'] = $this->template->bacaThemes('admin');
            $this->data['themes_public'] = $this->template->bacaThemes('public');
            $this->template->load('admin', 'setting', $this->data);
        }
    }
    function backup($new = null, $db = "")
    {
        $this->data['db_backup'] = bacaFolder(FCPATH . "/assets/backup/db/");

        if ($new == "new") {
            $this->load->dbutil();
            $prefs = array(
                'format' => 'zip',
                'filename' => 'my_db_backup.sql'
            );

            $backup = $this->dbutil->backup($prefs);

            $this->load->library("TanggalIndo");
            $tgl = $this->tanggalindo->init(date("Y-m-d"));
            $db_name = 'backup-on-' . str_replace(" ", "-", $tgl) . '.zip'; // file name
            $save  = './assets/backup/db/' . $db_name; // dir name backup output destination

            $this->load->helper('file');
            write_file($save, $backup);
            $this->session->set_flashdata('message', "<div class='mb-5 alert alert-danger' role='alert'>Database Backup baru .!</div>");
            redirect(base_url("admin/admin/backup"));
            // $this->load->helper('download');
            // force_download($db_name, $backup);
        } else if ($new == "download") {
            $link = "assets/backup/db/" . $db;
            toDownload($link);
        } else if ($new == "delete") {
            $link = "assets/backup/db/" . $db;
            toDownload($link);
        } else {
            $this->template->load('admin', 'backup', $this->data);
        }
    }
    // CM_delete
    function delete_setting($id)
    {
        $where = ['id_setting' => $id];
        $this->setting_m->delete_setting($where);
        hasilCUD("Sukses Menghapus pengaturan .!");
        redirect(base_url("admin/admin/setting"));
    }

    // CM_insert page
    function add_setting()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        if ($this->form_validation->run()) {
            $name = str_replace(" ", "_", $this->input->post('name'));
            $data = [
                'name' => $name,
                'title' => $this->input->post('title'),
                'status' => $this->input->post('status') ? 1 : 0
            ];
            $this->setting_m->add($data);
            hasilCUD("Sukses Menambahkan pengaturan baru.!");
        } else {
            $this->session->set_flashdata('message', "<div class='mb-5 alert alert-danger' role='alert'>Gagal Menambahkan Pengaturan baru .!</div>");
        }
        redirect(base_url("admin/admin/setting"));
    }

    function _setting()
    {
        foreach ($_POST as $key => $value) {
            $where['name'] = htmlspecialchars($key);
            $data['title'] = htmlspecialchars($value);
        }
        $this->setting_model->update($where, $data);
    }
}
