<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transform_depositphoto extends CI_Driver {
	public $source;
	public $count;

	function convert($obj) {
		//echo "<h1>This is first Child Driver</h1>";
		$this->source = $obj; 
		$this->count = $obj->count; 
		return $this;
	}

}