<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends Admin_Controller
{
    function index()
    {
        $this->template->load('admin', 'backend/dashboard', $this->data);
    }
    function getAllUser()
    {
        $list = $this->user_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $log) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $log->nip;
            $row[] = $log->name;
            $row[] = $log->email;
            $row[] = $log->alamat;
            // $row[] = $log->image;
            $row[] = date("d M, Y,  H:i:s A", $log->date);
            $row[] = '<a href="' . base_url('admin/admin/delete/') . $log->nip . '"  class="badge badge-pill badge-primary">Delete</a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user_model->count_all(),
            "recordsFiltered" => $this->user_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }



    function role()
    {
        $this->load->library('form_validation');
        $this->data['page']['title'] = 'User Access Menu Permission';
        $this->data['page']['description'] = 'Silahkan Edit dan pilih user untuk mengakses menu menu tertentu saja.!';
        $this->data['page']['submenu'] = 'Role Access';
        $this->data['role'] = $this->menu_m->getRole();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('role', 'Name Role', 'trim|required');

        if ($this->form_validation->run()) {
            $this->db->insert('tbl_user_role', [
                'name' => $this->input->post('role')
            ]);
            hasilCUD("Sukses Menambahkan Role");
            header("Refresh:0");
        } else
            $this->template->load('admin', 'backend/role', $this->data);
    }
    function roleedit($id)
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('role', 'Name Role', 'trim|required');

        $this->template->load('admin', 'backend/role', $this->data);
        if ($this->form_validation->run()) {
            $this->db->where('id', $id);
            $this->db->update('tbl_user_role', [
                'name' => $this->input->post('role')
            ]);
            hasilCUD("Sukses Edit Role");
            redirect(base_url('admin/admin/role'));
        }
    }
    function roledelete($id)
    {
        $this->db->delete('tbl_user_role', [
            'id' => $id
        ]);
        hasilCUD("Sukses Menghapus Role");
        redirect(base_url('admin/admin/role'));
    }

    function roleaccess()
    {
        $this->load->library('form_validation');
        $this->data['page']['title'] = 'Role Acces changed';
        $this->data['page']['description'] = 'Silahkan menu tertentu untuk di akses.!';
        $this->data['page']['before'] = ['url' => base_url('admin/admin/role'), "title" => "Role Access"];
        $this->data['page']['submenu'] = 'Edit Role access Menu';

        $role_id =  $this->data['page']['id'];

        $this->data['role'] = $this->menu_m->getRoleId($role_id)->row_array();

        $this->data['menu'] = $this->menu_m->getMenu()->result_array();
        $this->template->load('admin', 'backend/role_changed', $this->data);
    }
    function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $where = [
            'role_id' => htmlspecialchars($role_id),
            'menu_id' => htmlspecialchars($menu_id)
        ];
        $result = $this->menu_m->getWhereAccessRoleId($where);

        if ($result->num_rows() < 1) {
            $this->menu_m->addAccessMenu($where);
        } else {
            $this->menu_m->deleteAccessMenu($where);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access Changed!</div>');
        // echo json_encode($databack);
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
            $this->data['themes_admin'] = $this->template->open_template('admin');
            $this->data['themes_public'] = $this->template->open_template('public');
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
            toDelete($link);
            redirect(base_url('admin/admin/backup'));
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
