<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Icons_m extends CI_Model
{
    private $table = 'tbl_icons';
    private $idName = 'id';
    private $field = null;
    public function __construct()
    {
        parent::__construct();
        $this->field = [
            "name" => "",
            "code" => "",
            'kategori' => 0,
            "date_created" => time(),
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
                "field" => "code",
                "label" => "Kode",
                "rules" => "required"
            ]
        ];
    }
    //=
    // CM_delete
    //=
    function delete($data)
    {
        $where = [
            $this->idName => $data[$this->idName]
        ];
        $this->db->delete($this->table, $where);
    }
    //=
    //== CM_update
    //=
    function update($data)
    {
        $newData = array_intersect_key($data, $this->field);
        $this->db->where($this->idName, $data[$this->idName]);
        $this->db->update($this->table, $newData);
    }

    //=
    //== CM_insert
    //=
    function add($data)
    {
        $newData = array_intersect_key($data, $this->field);
        $this->db->insert($this->table, $newData);
    }

    //=
    //== CM_show
    //=
    function get()
    {
        return $this->db->order_by('name')->get($this->table)->result();
    }
}
