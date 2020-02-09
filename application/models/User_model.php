<?php



defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'tbl_user';
    private $idName = 'id_user';
    public $lastId, $roleId = null;


    private $fielTable = [];


    public function __construct()
    {
        parent::__construct();
        $this->fielTable = [$this->idName => $this->getLastId(), "name" => '', "email" => '', "password" => '', "role_id" => "", "is_active" => '0', "file_id" => '', "date_updated" => time(), "no_hp" => '', "alamat" => '', 'tentang_saya' => ''];
    }

    // set rules
    private $rule = [
        [
            "field" => "name",
            "label" => "Nama",
            "rules" => "required|trim"
        ],

        [
            "field" => "email",
            "label" => "Email",
            "rules" => "required|trim|valid_email"
        ],
        [
            "field" => "tgl_lahir",
            "label" => "Tanggal Lahir",
            "rules" => "required|trim"
        ],
        [
            "field" => "no_hp",
            "label" => "No Hp",
            "rules" => "required|trim"
        ]
    ];
    public function getRules()
    {
        return $this->rule;
    }
    public function addRules($rule)
    {
        $this->rule[] = $rule;
    }

    // mengambil id akhir untuk penambahan
    function getLastId()
    {
        $this->db->select_max($this->idName);
        $this->db->from($this->table);
        $eks = $this->db->get()->row_array()[$this->idName];
        $noUrut = (int) substr($eks, -3, 3);
        $noUrut++;
        $kodeName = 'user_';
        return $kodeName . sprintf("%03s", $noUrut);
    }
    public function getFieldTable()
    {
        return $this->fielTable;
    }

    public function setFieldTable($data)
    {
        foreach ($this->fielTable as $key => $val) {
            if (array_key_exists($key, $data)) {
                $this->fielTable[$key] = htmlspecialchars($data[$key]);
            }
        }
    }

    // 
    //==== CM_insert 
    //
    function add()
    {
        $this->db->insert($this->table, $this->fielTable);
    }


    // 
    //==== CM_delete 
    //

    // delete user jika lebih dari seminggu tidak di aktivasi
    function _deleteCount()
    {

        $user = $this->db->get_where($this->table, [
            "is_active" => 0
        ])->result_array();
        foreach ($user as $row) {
            // jika lebih seminggu tidak aktivasi hitung detik
            if (time() - $row['date_created'] >= 302400) {
                $this->delete($row['id']);
            }
        }
    }


    // 
    //==== CM_select
    //
    function getAll($where = ['id_user !=' => 1])
    {
        return $this->db->get_where($this->table, $where);
    }

    // menampilkan user berdasarkan email
    function getUser($email)
    {
        $this->db->select('tbl_user.*,tbl_user.image,tbl_user_role.name AS role_name');
        $this->db->from($this->table);
        $this->db->join('tbl_user_role', "tbl_user_role.id=tbl_user.role_id");
        $this->db->where([
            "{$this->table}.email" => $email
        ]);
        return $this->db->get();
    }

    // Mengecek apakah user ada atau tidak berdasarkan email
    function cekUser($email)
    {
        $this->db->select('tbl_user.*,tbl_user_file.image,tbl_user_role.name AS role_name');
        $this->db->from($this->table);
        $this->db->join('tbl_user_file', "tbl_user_file.id_file=tbl_user.image_id");
        $this->db->join('tbl_user_role', "tbl_user_role.id=tbl_user.role_id");
        $this->db->where([
            "{$this->table}.email" => $email
        ]);
        return $this->db->get();
    }



    // 
    //==== CM_update 
    //
    function update()
    {
        $this->db->where('id_user', $this->data['user']['id_user']);
        $this->db->update($this->table, $this->fielTable);
    }



    function newUser()
    {
        $hari = time() + (60 * 24) * 7;
        $eks = $this->db->get($this->table)->result_array();
        $i = 0;
        foreach ($eks as $row) {
            if ($row['date_created'] + (60 * 24) * 7 < time()) {
                $i += 1;
            }
        }
        return $i;
    }




    function getIdRole($name = null)
    {
        $role = $this->db->get("tbl_user_role")->result_array();
        $id = null;
        foreach ($role as $row) {
            if ($name === $row['name']) {
                $id = $row['id'];
            }
        }
        return $id;
    }


    function sendToken($data)
    {
        $this->db->insert('tbl_user_token', $data);
    }
    function delete($id)
    {
        $this->db->delete($this->table, [
            $this->idName => $id
        ]);
        return $this->db->affected_rows();
    }



    function updateToken($email, $data)
    {
        $this->db->where([
            'email' => $email
        ]);
        $this->db->update('tbl_user_token', $data);
    }
    function getAllUser()
    {
        $this->db->select("*,user.{$this->idName} AS 'id'");
        $this->db->from($this->table);
        $this->db->where([
            'user.role_id !=' => "1"
        ]);
        $this->db->join('user_about', "user.{$this->idName} = user_about.user_id");
        return $this->db->get();
    }
    function getUserId($id)
    {
        $this->db->select("*,user.{$this->idName} AS 'id'");
        $this->db->from($this->table);
        $this->db->join('user_about', "user.{$this->idName} = user_about.user_id");
        $this->db->where([
            'user.id' => $id
        ]);
        return $this->db->get();
    }
    public function cekToken($email)
    {
        return $this->db->get_where("tbl_user_token", ["email" => $email]);
    }





    // start datatables
    var $column_order = array(null, 'name', 'no_hp', 'alamat', 'tgl_lahir'); //set column field database for datatable orderable

    var $column_search = array('name', 'no_hp', 'alamat'); //set column field database for datatable searchable
    var $order = [
        "id" => 'asc'
    ]; // default order

    private function _get_datatables_query()
    {
        $this->db->select('user_about.*');
        $this->db->from($this->table);
        $this->db->join('user_about', "user_about.user_id=user.id");
        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if (@$_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        $this->db->where([
            'user.id !=' => "user_001"
        ]);
    }
    function get_datatables()
    {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all()
    {
        $this->db->from($this->table);
        $this->db->where([
            'user.id !=' => "user_001"
        ]);
        return $this->db->count_all_results();
    }
    // end datatables

}
