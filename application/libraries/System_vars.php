<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_vars
{
  private $sysvars;

  function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('membership_model');

    $this->sysvars = $this->CI->membership_model->get_system();
	}

  public function iva()
  {
    return strval($this->sysvars['iva']['value']);
  }

  public function tco($type='')
  {
    switch ($type) {
      case 'pct':
        $val = $this->sysvars['2co_percent']['value'];
        break;
      case 'add':
        $val = $this->sysvars['2co_additional']['value'];
        break;
    }
    return strval($val);
  }

  public function comision()
  {
    return strval($this->sysvars['price_comision']['value']);
  }

  public function plan_comision()
  {
    return strval($this->sysvars['plan_comision']['value']);
  }

}
