<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxes
{
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library(['session','system_vars']);
	}

  public function set_iva($valor=0)
  {
    if ($this->CI->session->user_country=='Colombia')
      $valor = 0;

    return $valor * $this->CI->system_vars->iva();
  }

  public function set_tco($valor=0, $iva_include=false)
  {
    if ( ! $iva_include)
      $valor += $this->set_iva($valor);

		return ($valor * $this->CI->system_vars->tco('pct')) +
            $this->CI->system_vars->tco('add');
  }

  public function set_price($original_price)
  {
    return ceil($original_price) * (1+strval($this->CI->system_vars->comision()));
  }

  public function set_plan_price($original_price, $provider)
  {
    if ($provider == 'Depositphoto') {
      return ceil($original_price);
    } else {
      return ceil($original_price) * (1+strval($this->CI->system_vars->plan_comision()));
    }
  }

  public function plan_comision()
  {
    return 1+strval($this->CI->system_vars->plan_comision());
  }

}
