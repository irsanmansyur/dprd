<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Label_m extends CI_Model
{
    /**
     * required insert table name
     */
    private $table = "web_komisi_label";

    /**
     * mode must set otomatic
     * fiel table
     * key name
     */

    /**
     * set default String key Identitas
     * otomatis insert primary key
     * exp : tbl_001
     */
    private $keyString = "lbl_";

    /**
     * default config
     */
    private $field = [];
    private $key = '';

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    /**
     * inisialisai table setting fiel and key
     * dont change it
     */

    function init()
    {
        foreach ($this->db->field_data($this->table) as $row) {
            if ($row->primary_key == 1) {
                $this->key = $row->name;
                $val = $this->getLastKey();
            } else if ($row->name == "date_created" || $row->name == "date_updated") {
                $val = time();
            } else
                $val = '';
            $this->field[$row->name] = $val;
        }
    }

    /**
     * Set Default empty Rules Validation
     * You Can Add Rules validation
     * Get rules Validation
     */
    private $rules = array();

    /**
     * You cant Add Rules Here
     * we set default rule
     * reuqired|trim
     * add others
     * exp :
     * $this->addRules("nama");
     * or :
     * $this->addRules("nama",email_valid);
     */
    function addRules($field, $rules = null)
    {
        if (array_key_exists($field, $this->field)) {
            $rls = '';
            if ($rules) {
                $rls = "|" . $rules;
            }
            $arr =  [
                "field" => $field,
                "label" => "Nama Label",
                "rules" => "required|trim" . $rls
            ];
            $this->rules[] = $arr;
            return response(true, "Berhasil tambah rules");
        } else {
            return response("Field Tidak di Temukan di table {$this->table}");
        }
    }

    /**
     * to get rules validation
     * exp useing this 
     * exp :
     * $validation->set_rules($this->getRules())
     */
    public function getRules()
    {
        return $this->rules;
    }


    /**
     * Set Primary Key to easy to use
     */
    function setPrimaryKey($value)
    {
        $this->field[$this->key] = $value;
    }
    /**
     * return array in Keyname and value
     * exp : [id_label => lbl_001 ]
     */
    function getPrimaryKey()
    {
        return [$this->key => $this->field[$this->key]];
    }

    /**
     * this use from add new rows ini table
     */

    function getLastKey()
    {
        $this->db->select_max($this->key);
        $this->db->from($this->table);
        $eks = $this->db->get()->row_array()[$this->key];
        $noUrut = (int) substr($eks, -3, 3);
        $noUrut++;
        return $this->keyString . sprintf("%03s", $noUrut);
    }

    public function getkey()
    {
        return $this->key;
    }

    public function getField()
    {
        return $this->field;
    }
    public function setField($data)
    {
        foreach ($this->field as $key => $val) {
            $keyOri = $key;
            switch ($key) {
                case "user_id":
                    array_key_exists($key, $data) ? "" : $keyOri = "id_user";
                    break;
                case "komisi_id":
                    array_key_exists($key, $data) ? "" : $keyOri = "id_komisi";
                    break;
                default:
                    break;
            };
            if (array_key_exists($keyOri, $data)) {
                $this->field[$key] = htmlspecialchars($data[$keyOri]);
            }
        }
    }




    /**
     * is use where user get Where field
     * is check in field table
     */
    function _cekKey($key)
    {
        switch ($key) {
            case "id_user":
                $keyOri = "user_id";
                break;
            case "id_komisi":
                $keyOri = "komisi_id";
                break;
            default:
                $keyOri = "";
        }
        if (array_key_exists($keyOri, $this->field)) {
            return $this->db->where($keyOri, $this->field[$keyOri]);
        }
    }


    // 
    //==== CM_insert 
    //
    function add()
    {
        if ($this->fieldEmpty()) {
            $this->db->insert($this->table, $this->field);
        }
        return hasilCUD();
    }
    /**
     * this function cek field empty value
     * cek key and return true or false
     * if empty value return false
     */
    function fieldEmpty()
    {
        foreach ($this->field as $key => $value) {
            $value = trim($value);
            if (empty($value))
                return false;
            else
                return true;
        }
    }

    // 
    //==== CM_delete 
    //
    function deleted()
    {
        if (!empty($this->field[$this->key]))
            $this->db->delete($this->table, $this->getPrimaryKey());
        return hasilCUD("deleted {$this->field[$this->key]}");
    }

    /** */
    /** 
     * CM_update
     * Update Data 
     * 
     *
     **/

    function update()
    {
        $this->db->update($this->table, $this->field, [$this->key => $this->field[$this->key]]);
        return hasilCUD("Updated {$this->field[$this->key]}");
    }

    function relasi($table, $type = "")
    {

        if ($type == 'hashMany') {
            $tableParent = $this->table;
            $tableChildrent = $table;
        } else if ($type == "hashOne") {
            $tableParent = $table;
            $tableChildrent = $this->table;
        } else {
            $tableParent = $table;
            $tableChildrent = $type;
        }
        $sql = "SELECT TABLE_NAME AS tableChildrent, COLUMN_NAME AS FK, CONSTRAINT_NAME, REFERENCED_TABLE_NAME AS tableParent, REFERENCED_COLUMN_NAME AS PK FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '{$this->db->database}' AND REFERENCED_TABLE_NAME = '{$tableParent}' AND TABLE_NAME='{$tableChildrent}'";
        $eks = $this->db->query($sql)->row();

        return $eks;
    }

    function hashOne($parent)
    {
        $relasi = $this->relasi($parent->getTable());
        $dataParent = $parent->getWhere($relasi->FK);
        return $dataParent;
    }
    function hashMany($childrent)
    {
        $relasi = $this->relasi($childrent->getTable(), "hashMany");
        $datachildrent = $childrent->getWhere($relasi->FK);
        return $datachildrent;
    }

    /**
     * To Select All 
     * $this->getWhere();
     * 
     * To select where field
     * must :
     * $this->setField([Sfieldname=>$value])
     * 
     * then :
     * $this->getWhere($fieldName);
     * 
     * to Limit or Join must set paramater 2
     * $parameter2       = [
     *                       'start' => [
     *                          $limit = 10,
     *                          $start = 0,
     *                          ],
     * Join this table      "join" => [ 
     *                                 [TableJoin] 
     *                               ]
     * Join table others    "join" => [ 
     *                                  ["tbl1,tbl2"]
     *                               ]
     * Join many Table      "join" => [ 
                                        ["tbl1,tbl2"],
                                        ["tbl1,tbl2"],
                                        ["tbl1,tbl2"],
                                        ["tbl1,tbl2"],
                                     ]
     *                     ]
     * $this->getWhere($fieldName=''/null, );
     */
    function getWhere($key = "", $custom = [])
    {
        $msg =  response(true, "Data succes");
        $this->db->select($this->table . ".*");
        $this->db->from($this->table);
        if (is_array($key)) {
            foreach ($key as $row) {
                $this->_cekKey($row);
            }
        } else
            $this->_cekKey($key);

        if (is_array($custom)) {
            foreach ($custom as $key => $val) {

                if ($key === "join") {
                    $tableChildrent = $this->table;
                    if (is_array($val)) {
                        /**
                         * loopin many join tabel
                         */
                        foreach ($val as $key => $value) {
                            if (is_array($value)) {
                                $tableParent = $value[0];
                                !empty($value[1]) ? $tableChildrent = $value[1] : '';
                            } else {
                                $tableParent = $value;
                            }

                            /**
                             * joined table
                             */
                            $join = $this->InJoin($tableParent, $tableChildrent);
                            if (!$join) {
                                $msg->status = false;
                                $msg->message = "</br>Periksa Tabel Join anda pada indeks : {$key} = <b>{$tableParent}</b></br>";
                            }
                        }
                    } else {

                        /**
                         * join one table
                         */
                        $tableParent = $val;
                        $join = $this->InJoin($tableParent, $tableChildrent);
                        if (!$join) {
                            $msg->status = false;
                            $msg->message = "</br>Periksa Tabel Join anda pada indeks : {$key} = <b>{$tableParent}</b></br>";
                        }
                    }
                } else if ($key === "start") {
                    $start = 0;
                    $limit = 10;
                    if (is_array($val)) {
                        $msg->data = $val;
                        $vals = $val[0];
                        $val[0] =  (int) $val[0];
                        if ($val[0] > 0 || $vals == '0') {
                            if (!empty($val[1])) {
                                $val[1] = (int) $val[1];
                                $limit = $val[1] > 0 ? $val[1] : 10;
                            } else $limit = 10;
                            $msg->status = true;
                            $start = $val[0];
                            $this->db->limit($limit, $start);
                        } else {
                            $msg->status = false;
                            $msg->message =  "Val array Start must Integer and Not Empty";
                        }
                    } else {
                        $vals = $val;
                        $val =  (int) $val;
                        if ($val > 0 || $vals == '0') {
                            $msg->status = true;
                            $start = $val;
                            $this->db->limit($limit, $start);
                        } else {
                            $msg->status = false;
                            $msg->message =  "Val Start must Integer and Not Empty";
                        }
                    }
                } else  $msg->message = "harus ada value dan atau Parameter kedua tidak dikenali";
            }
        } else {
            $msg->message = "Parameter kedua harus array";
        }
        $msg->data = $this->db->get();
        if (!$msg->data) {
            $msg->status = false;
        }
        return $msg;
    }
    function InJoin($tableParent, $tableChildrent)
    {

        $relasi = $this->relasi($tableParent,  $tableChildrent);

        if ($relasi) {
            $dt = $this->db->join("$relasi->tableParent", "$relasi->tableParent.$relasi->PK=$relasi->tableChildrent.$relasi->FK");
            return true;
        } else return false;
    }
}
