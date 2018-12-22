<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once('RpcParams.php');
/**
* 
*/
class DepositClient {
	public $fullurl;

	function __construct() {
	  $this->fullurl = "localhost";
	}

	function search($criteria) {
		return new rawData();
	}

	function getMediaData($id) {
		return new rawData();
	}

	function login($login, $passwd) {
		return new rawLogin(true);
	}

	function logout($sessionid) {
		return new rawLogin(false);
	}

	function getSubaccounts() {
		return new rawSubaccounts();
	}

}

class rawLogin {
	public $sessionid;
	public $result;

	function __construct($login) {
		$this->sessionid = $login ? $this->login() : $this->logout();
		$this->result = array('time' => date("Y-m-d H:i:s"));
	}

	function login(){
		if (isset($_SESSION['mysession']))
			$sessionid = $_SESSION['mysession'];
		else{
			$sessionid = "1234567890";
			$_SESSION['mysession'] = $sessionid;
		}
		return $sessionid;
	}

	function logout(){
		unset($_SESSION['mysession']);
		return "";
	}

}

class rawData {
	public $result;
	public $count;
	public $sizes;

	function __construct() {
	  $this->result = array('row1' => 'data1', 'row2' => 'data2', 'row3' => 'data3');
	  $this->count = count($this->result);
	  $this->sizes = array('s' => 's', 'm' => 'm', 'l' => 'l');

	}

}

class rawSubaccounts {
	public $subaccounts;

	function __construct() {
		$this->subaccounts = array('row1' => 'data1', 'row2' => 'data2', 'row3' => 'data3');
	}
}
?>