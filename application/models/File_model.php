<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File_model extends CI_Model
{
    private $table = 'tbl_user_file';
    private $idName = 'id_file';
    private $role_id = null;
    private $fieldTable = [];
    public function __construct()
    {
        parent::__construct();
        $this->fieldTable = [$this->idName => $this->getLastId(), "user_id" => "", "file" => "default.jpg", "type" => 1];
        $this->role_id = $this->session->userdata('role_id');
    }

    function _getField()
    {
        return $this->fieldTable;
    }
    function setFieldTable($data)
    {
        foreach ($data as $key => $val) {
            if ($key == "file_id" || $key == "id_user") {
                $this->fieldTable[$key == "file_id" ? "id_file" : "user_id"] = htmlspecialchars($data[$key]);
            } else if (array_key_exists($key, $this->fieldTable))
                $this->fieldTable[$key] = htmlspecialchars($data[$key]);
        }
    }


    // mengambil id akhir untuk penambahan
    function getLastId()
    {
        $this->db->select_max($this->idName);
        $this->db->from($this->table);
        $eks = $this->db->get()->row_array()[$this->idName];
        $noUrut = (int) substr($eks, -3, 3);
        $noUrut++;
        $kodeName = 'file_';
        return  $kodeName . sprintf("%03s", $noUrut);
    }

    // 
    //==== CM_select
    //

    function getAll()
    {
        if ($this->role_id != 1)
            $this->db->where(['user_id' => $this->data['user']['id_user']]);
        return $this->db->get($this->table);
    }


    // 
    //==== CM_update
    //

    function update()
    {
        $this->db->where($this->idName, $this->fieldTable[$this->idName]);
        $this->db->update($this->table, $this->fieldTable);
    }
    // 
    //==== CM_delete
    //


    // 
    //==== CM_insert
    //
    function add($data)
    {
        $this->setFieldTable($data);
        $this->db->insert($this->table, $this->fieldTable);
    }
}
