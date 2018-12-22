<?php
use Restserver\Libraries\REST_Controller;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Countries extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('countries_model');
    }

    function index_get()
    {
        if ( ! $this->get('list'))
        {
          $this->response(NULL, 400);
        }
        else
        {

          switch ($this->get('list'))
          {
            case 'countries':
                  $list = $this->countries_model->get_countries();
                  break;

            case 'states':
                  $country_id = $this->get('countryID');
                  $list = $this->countries_model->get_states($country_id);
                  break;

            case 'cities':
                  $state_id = $this->get('stateID');
                  $list = $this->countries_model->get_cities($state_id);
                  break;
          }

          if($list)
          {
              $this->response($list, 200); // 200 being the HTTP response code
          }
          else
          {
              $this->response(NULL, 404);
          }

        }

    }


  // function send($obj)
  // {
  //   $this->output
  //         ->set_content_type('application/json')
  //         ->set_output($obj);
  // }
  //
  // function _output($obj) {
  //   echo $this->input->get('callback')."(".json_encode($obj).")";
  // }

}
