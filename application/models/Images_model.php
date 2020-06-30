<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Images_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "images";
		$this->PRI_INDEX = "id_image";
	}

}
