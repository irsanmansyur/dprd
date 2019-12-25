<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_m extends CI_Model
{
    private $table = 'tbl_setting';
    protected $idName = "id_setting";
    private $field = null;

    public function __construct()
    {
        parent::__construct();
        $this->field = [
            "name",
            "title",
            'status' => 0,
            "date_updated" => time()
        ];
    }
    public function rules()
    {
        return [
            [
                "field" => "name",
                "label" => "Name",
                "rules" => "required"
            ],
            [
                "field" => "title",
                "label" => "Title",
                "rules" => "required"
            ]
        ];
    }
    function get()
    {
        return $this->db->order_by('name')->get_where($this->table, ['status' => 1]);
    }

    function update($where = [], $data = [])
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
    }

    // CM_delete
    function delete_setting($where)
    {
        $this->db->delete($this->table, $where);
    }

    // CM_insert
    function add($data = array())
    {
        if (count($data)) {
            $this->db->insert($this->table, $data);
        } else return false;
    }
}
