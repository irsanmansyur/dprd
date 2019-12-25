<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Komisi_m extends CI_Model
{
    private $table = "web_komisi";
    private $keyName = "id_komisi";
    private $keyString = "kms_";
    private $fieldTable = ['name' => ''];
    public function __construct()
    {
        parent::__construct();
        $this->fieldTable[$this->keyName] = $this->getLastId();
    }

    // methode rule untuk validasi
    // menambahkan custoo rule
    // mensset rules baru
    private $rules = [
        [
            "field" => "name",
            "label" => "Name",
            "rules" => "required|trim|min_length[5]"
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

    /**
     * Set Primary Key to easy to use
     */
    function setPrimaryKey($value)
    {
        $this->fieldTable[$this->keyName] = $value;
    }
    function getPrimaryKey()
    {
        return [$this->keyName => $this->fieldTable[$this->keyName]];
    }

    // mengambil id akhir untuk penambahan
    function getLastId()
    {
        $this->db->select_max($this->keyName);
        $this->db->from($this->table);
        $eks = $this->db->get()->row_array()[$this->keyName];
        $noUrut = (int) substr($eks, -3, 3);
        $noUrut++;
        return $this->keyString . sprintf("%03s", $noUrut);
    }

    public function getKeyName()
    {
        return $this->keyName;
    }

    public function getFieldTable()
    {
        return $this->fieldTable;
    }
    public function setFieldTable($data)
    {
        foreach ($this->fieldTable as $key => $val) {
            $keyOri = $key;
            switch ($key) {
                case "user_id":
                    array_key_exists($key, $data) ? "" : $keyOri = "id_user";
                    break;
                case "aspirasi_id":
                    array_key_exists($key, $data) ? "" : $keyOri = "id_aspirasi";
                    break;
                default:
                    break;
            };
            if (array_key_exists($keyOri, $data)) {
                $this->fieldTable[$key] = htmlspecialchars($data[$keyOri]);
            }
        }
    }

    /**
     * to get Table Name
     */
    function getTable()
    {
        return $this->table;
    }
    // 
    //==== CM_get 
    //
    function _cekKey($key)
    {
        if (array_key_exists($key, $this->fieldTable)) {
            return $this->db->where($key, $this->fieldTable[$key]);
        }
    }

    function getWhere($key = null, $join = null)
    {
        $this->db->select($this->table . ".*");
        $this->db->from($this->table);
        if (is_array($key)) {
            foreach ($key as $row) {
                $this->_cekKey($row);
            }
        } else
            $this->_cekKey($key);
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
    // 
    //==== CM_delete 
    //
    function deleted()
    {
        $this->db->delete($this->table, $this->getPrimaryKey());
    }
    /** 
     * CM_update
     * Update Data 
     * 
     *
     **/

    function update()
    {
        $this->db->update($this->table, $this->fieldTable, [$this->keyName => $this->fieldTable[$this->keyName]]);
    }
}
