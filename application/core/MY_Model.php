<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	protected $TABLE_NAME;
	protected $SELECT;
	protected $PRI_INDEX ;
	protected $parameters;
	protected $joins;
	protected $wheres;
	protected $select;

	public function __construct($params_conf =  array()){
		$this->parameters = $params_conf;
		if ($this->parameters) {
			$this->TABLE_NAME = $this->parameters["table"];
			$this->PRI_INDEX = $this->parameters["primary_key"];
			$this->SELECT = "*";
		}
		/**
		 * [$this->joins ejemplo de uso
		 * $this->joins[] = array("table" => "cita", "on" => "cita.ID = ".$this->TABLE_NAME.".ID_cita", "clausula" => "");]
		 * @var array
		 */
		$this->joins = [];
		$this->wheres = [];
		$this->select  = "";
	}

	public function get($columns='', $where = [], $joins=[],  $like = [], $limit = 0, $start = 10, $order = ''){
		if(! empty($columns)) {
			$this->db->select($columns);
		}
		if(! empty($where)){
			$this->db->where($where);
		}
		if(! empty($joins)){
			$this->db->join($joins["table"], $joins["ON"], $joins["clausula"]);
		}
		if(! empty($like)){
			$this->db->like($like);
		}
		if(! empty($order)){
			$this->db->order_by($order);
		}
		if($limit > 0){
			$this->db->limit($limit, $start);
		}
		$this->db->where($this->TABLE_NAME.".estatus", 1);
		$query = $this->db->get($this->TABLE_NAME);
		if(is_array($where)){
			return $query->result();
		}else{
			return $query->row();
		}
	}

	public function insert(Array $data){
		if ($this->db->insert($this->TABLE_NAME, $data)){
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function insertm(Array $data){
		if ($this->db->insert_batch($this->TABLE_NAME, $data)){
			return $this->db->affected_rows();
		} else {
			return false;
		}
	}

	public function update(Array $data, $where = array()){
		if (!is_array($where)){
			$where = array($this->PRI_INDEX => $where);
		}
		$this->db->update($this->TABLE_NAME, $data, $where);
		return $this->db->affected_rows();
	}

	public function updatem(Array $data, $where = NULL){
		if ($where === NULL){
			$where = $this->PRI_INDEX;
		}
		$this->db->update_batch($this->TABLE_NAME, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete($where = array()){
		if (!is_array($where)){
			$where = array($this->PRI_INDEX => $where);
		}
		$this->db->delete($this->TABLE_NAME, $where);
		return $this->db->affected_rows();
	}

}

/* End of file MY_model.php */
/* Location: ./application/models/MY_model.php */