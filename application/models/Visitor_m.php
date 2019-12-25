<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Visitor_m extends CI_Model
{
    private $table = 'tbl_visitor';

    public function __construct()
    {
        parent::__construct();
    }
    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows();
    }
    function getVisitor()
    {
        $this->db->select("FROM_UNIXTIME(date_created, '%d') as date_created,COUNT(id_visitor) AS Count");
        $this->db->from($this->table);
        $this->db->group_by("DATE(FROM_UNIXTIME(date_created))", "asc");
        $this->db->where([
            'from_unixtime(`date_created`) >' => " date_sub(now(), interval 7 day)"
        ]);
        return $this->db->get();
    }

    function get($where)
    {
        $this->db->where($where);
        $this->db->order_by('date_created', 'desc');
        $this->db->limit(1);
        return $this->db->get($this->table);
    }
}
