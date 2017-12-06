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

	public function get($where = array(), $like = array()) {
		if ($this->select) {
			$this->db->select($this->select);
		} else {
			$this->db->select("*");
		}
		if ($like) {
			$this->db->like($like);
		}

		$this->db->from($this->TABLE_NAME);
		if ($this->joins) {
			foreach ($this->joins as $join) {
				$this->db->join($join["table"], $join["on"], $join["clausula"]);
			}
		}
		if ($where !== NULL) {
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}

		$this->db->where($this->TABLE_NAME.".estatus", 1);
		$result = $this->db->get()->result();

		if ($result) {
			if (is_array($where)) {
				return $result;
			} else {
				return array_shift($result);
			}
		} else {
			return false;
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