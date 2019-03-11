<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactions extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('membership_model');
        $this->load->library('membership');
    }

    public function store_transaction()
    {
        if ($this->membership->is_logged_in())
		{
			if ( $query = $this->membership_model->record_transaction() )
			{
				$datares['recId'] = $this->membership_model->record_download();
				$datares['result'] = 'recorded';
			}
		}
		else{
			$datares['recId'] = '';
			$datares['result'] = 'not recorded';
		}

		echo json_encode($datares);
	}

	public function set_license_id()
	{
		if ($this->membership->is_logged_in())
		{
			if ($this->membership_model->set_license_id())
				$datares['result'] = 'set';
			else
				$datares['result'] = '';
		}

		echo json_encode($datares);
	}

	public function save_to_cart()
	{
		$datares['recId'] = '';
		$datares['result'] = 'not logged';

        if ($this->membership->is_logged_in())
		{
			if ( ! $this->membership_model->cart_found() )
				$this->membership_model->save_cart();

			$datares['recId'] = $this->membership_model->save_cart_details();
			$datares['result'] = 'recorded';
		}
		else{
			$datares['recId'] = '';
			$datares['result'] = 'not recorded';
		}

		echo json_encode($datares);
	}

	public function view_cart()
	{
		$cartitems = '';
		$datares = [];
    	if ($this->membership->is_logged_in())
		{
			$cartitems = $this->membership_model->get_cart();
			$datares = $cartitems->result();
		}

		echo json_encode($datares);
	}

	public function view_downloads() 
	{
		$username = $this->input->get('username');
		$download_list = $this->membership_model->get_downloads($username);
		$data = $download_list->result();
		echo json_encode($data);
	}

	public function view_planes() {
		$username = $this->input->get('username');
		$data = $this->membership_model->user_planes($username);
		echo json_encode($data);
	}

	public function items_in_cart()
	{
		$datares = $this->membership_model->count_cart_items();
		echo json_encode($datares);
	}

	public function item_cart_delete()
	{
		$datares = $this->membership_model->delete_cart_item();
		echo json_encode($datares);
	}

	public function getTotalPayments()
	{
		$datares = $this->membership_model->get_total_payments();
		echo json_encode($datares);
	}

  public function user_downloads()
  {
    $datares = $this->membership_model->user_downloads();
    //header('Content-Type: application/json');
    echo json_encode($datares);
  }

}
