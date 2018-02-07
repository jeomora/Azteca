<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	protected $TABLE_NAME;
	protected $PRI_INDEX;
	protected $PARAMETERS;

	public function __construct($params =  array()){
		$this->PARAMETERS = $params;
		if ($this->PARAMETERS) {
			$this->TABLE_NAME = $this->PARAMETERS["table"];
			$this->PRI_INDEX = $this->parameters["primary_key"];
		}
	}

	public function get($columns=NULL, $where=[], $joins=[],  $like=[], $limit=FALSE, $start=FALSE, $order=''){
		if(! empty($columns)) {
			$this->db->select($columns);
		}
		if(! empty($where)){
			$this->db->where($where);
		}
		if(! empty($joins)){
			foreach($joins as $k => $join){
				$this->db->join($join["table"], $join["ON"], $join["clausula"]);
			}
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
		return $query->num_rows() > 0 ? $query->result() : 0;
	}

	public function insert(Array $data){
		if ($this->db->insert($this->TABLE_NAME, $data)){
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function insert_batch(Array $data){
		if ($this->db->insert_batch($this->TABLE_NAME, $data)){
			return $this->db->affected_rows();
		} else {
			return false;
		}
	}
	public function weekNumber($date=NULL){
		if (empty($date)) {
			$date = date("Y-m-d");
		}
		$day	=	substr(date($date),8,2);//Día actual
		$month	=	substr(date($date),5,2);//Mes actual
		$year	=	substr(date($date),0,4);//Año actul
		return date("W", mktime(0,0,0,$month,$day,$year));//El número de la semana
	}

	public function update(Array $data, $where = array()){
		if (!is_array($where)){
			$where = array($this->PRI_INDEX => $where);
		}
		$this->db->update($this->TABLE_NAME, $data, $where);
		return $this->db->affected_rows();
	}

	public function update_batch(Array $data, $where = NULL){
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

	public function count_all(){
		return $this->db->count_all($this->TABLE_NAME);
	}

	public function pagination_get($columns=NULL, $where=[], $joins=[], $like=[], $limit=FALSE, $start=FALSE, $group='', $order=''){
		if(! empty($columns)) {
			$this->db->select($columns);
		}
		if(! empty($where)){
			$this->db->where($where);
		}
		if(! empty($joins)){
			foreach($joins as $k => $join){
				$this->db->join($join["table"], $join["ON"], $join["clausula"]);
			}
		}
		if(! empty($like)){
			$this->db->like($like);
		}
		if(! empty($group)){
			$this->db->group_by($group);
		}
		if(! empty($order)){
			$this->db->order_by($order);
		}
		if($limit !==FALSE && $start !==FALSE){
			$this->db->limit($limit, $start);
		}
		$this->db->where($this->TABLE_NAME.".estatus", 1);

		$this->db->from($this->TABLE_NAME);

		if($where !== NULL){
			if(is_array($where)){
				foreach($where as $field=>$value){
					$this->db->where($field, $value);
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}

		$query = $this->db->get();

		if($query->num_rows() > 0){
			foreach($query->result() as $k => $row){
				$data[]	= $row;
			}
			return $data;
		}
		return false;
	}

	public function get_pagination_query($columns=NULL, $joins=[], $wheres=[], $search=[], $group=NULL, $order=NULL){
		if(! empty($columns)) {
			$this->db->select($columns);
		}
		$this->db->from($this->TABLE_NAME);
		$cont =0;
		if (! empty($search)) {
			foreach ($search as $key => $val) {
				if($_POST["search"]["value"]){
					if($cont===0){
						$this->db->group_start();
						$this->db->like($val, $_POST["search"]["value"]);
					}else{
						$this->db->or_like($val, $_POST["search"]["value"]);
					}
					if(count($search) - 1 == $cont){
						$this->db->group_end();
					}
				}
				$cont++;
			}
		}
		if(! empty($joins)){
			foreach($joins as $k => $join){
				$this->db->join($join["table"], $join["ON"], $join["clausula"]);
			}
		}
		if(! empty($wheres)){
			foreach($wheres as $key => $where){
				$this->db->where($where["clausula"], $where["valor"]);
			}
		}
		if(! empty($group)){
			$this->db->group_by($group);
		}
		if(! empty($order)){
			$this->db->order_by($order, "ASC");
		}
	}

	public function get_pagination($columns=NULL, $joins=[], $wheres=[], $search=[], $group=NULL, $order=NULL){
		$this->get_pagination_query($columns, $joins, $wheres, $search, $group, $order);
		if($_POST["length"] != -1){
			$this->db->limit($_POST["length"], $_POST["start"]);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function count_filtered($column_count=NULL, $wheres=[], $search=[], $joins=[]){
		$this->get_pagination_query("COUNT(DISTINCT {$column_count}) AS rows", $joins, $wheres, $search, NULL, NULL);
		$query = $this->db->get();
		return $query->row()->rows;
	}

}

/* End of file MY_model.php */
/* Location: ./application/models/MY_model.php */