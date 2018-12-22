<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transform extends CI_Driver_Library {

	function __construct() {
		$this->valid_drivers = array('depositphoto');
	}
	
	function index() {
		echo "<h1>Parent driver</h1>";  
	}

	function depositphoto($obj) {
		return $this->depositphoto->convert($obj);
	}
}