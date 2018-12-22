<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Countries_model extends CI_Model {

	function __construct()
  {
			$this->load->database();
	}

  function get_countries()
  {
      $countries = $this->db->get('countries');
      return $countries->result();
  }

  function get_states($country_id)
  {
      $condition = array(
        'country_id' => $country_id
      );
      $states = $this->db->get_where('states', $condition);
      return $states->result();
  }

  function get_cities($state_id)
  {
      $condition = array(
        'state_id' => $state_id
      );
      $cities = $this->db->get_where('cities', $condition);
      return $cities->result();
  }

}
