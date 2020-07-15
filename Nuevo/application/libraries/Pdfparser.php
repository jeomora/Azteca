<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/Smalot/PdfParser/Parser.php"; 

class Pdfparser extends PHPExcel{
	public function __construct(){
		parent::__construct();
	}
}