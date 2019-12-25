<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aspirasi_m extends CI_Model
{
    private $table = "web_aspirasi";
    private $idName = "id_aspirasi";
    private $fieldTable = ['message' => '',  'user_id' => '', 'status' => 3];
    public function __construct()
    {
        parent::__construct();
        $this->fieldTable[$this->idName] = $this->getLastId();
    }

    // methode rule untuk validasi
    // menambahkan custoo rule
    // mensset rules baru
    private $rules = [
        [
            "field" => "message",
            "label" => "Message",
            "rules" => "required|trim|min_length[10]"
        ]
    ];
    public function getRules()
    {
        return $this->rules;
    }
    public function addRules($rule)
    {
        $this->rule[] = $rule;
    }
    public function setRules($rules)
    {
        $this->rules = $rules;
    }


    // mengambil id akhir untuk penambahan
    function getLastId()
    {
        $this->db->select_max($this->idName);
        $this->db->from($this->table);
        $eks = $this->db->get()->row_array()[$this->idName];
        $noUrut = (int) substr($eks, -3, 3);
        $noUrut++;
        $kodeName = 'asp_';
        return $kodeName . sprintf("%03s", $noUrut);
    }

    public function getKey()
    {
        return $this->idName;
    }

    public function getFieldTable()
    {
        return $this->fieldTable;
    }
    public function setFieldTable($data)
    {
        foreach ($this->fieldTable as $key => $val) {
            $keyOri = $key;
            if ($key == "user_id") {
                $keyOri = "id_user";
            }
            if (array_key_exists($keyOri, $data)) {
                $this->fieldTable[$key] = htmlspecialchars($data[$keyOri]);
            }
        }
    }


    // 
    //==== CM_get 
    //
    function getWhere($key)
    {
        $this->db->select($this->table . ".*,web_komisi.name AS 'komisi'");
        $this->db->from($this->table);
        $this->db->join("web_komisi", "web_komisi.id_komisi={$this->table}.komisi_id");
        $this->db->where($key, $this->fieldTable[$key]);
        return $this->db->get();
    }

    public function getAll()
    {
        return $this->db->get($this->table);
    }


    // 
    //==== CM_insert 
    //
    function add()
    {
        $this->db->insert($this->table, $this->fieldTable);
    }
}
