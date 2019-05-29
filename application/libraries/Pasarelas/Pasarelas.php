<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasarelas extends CI_Driver_Library {

    function __construct() {
		$this->valid_drivers = array('payu','tco');
    }

    function index() {
		echo "<h1>Parent Pasarelas driver</h1>";
	}

}