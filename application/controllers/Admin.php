<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_DPUSER', 'latincolorimages');
define('DEF_DPPASS', 'latincol2016$');

class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('membership');
		$this->load->model('membership_model');
		$this->load->driver('providers');
		$this->load->helper('tag');

	}

	public function index() {
			if ($table = $this->input->get('table')) {
				$data['datares'] = $this->get_data($table);
				$page = $table.'_list';
				$this->load_page($page, $data);
			}
			else {
				$this->ordenes();
			}
	}

	private function load_page($page, array $pre_data = NULL) {
		$username = $this->membership->is_logged_in();
		if ($username != '')
		{
			if ($pre_data != NULL)
				$data = $pre_data;
			$data['logged'] = $username;
			$data['user_data'] = $this->membership->get_user($username);
			$fullname = $this->membership_model->get_fullname($username);
			$data['user_info'] = json_encode($fullname->row());
			$data['new_consultas'] = $this->membership_model->count_new_consultas();
			$data['new_ventas'] = $this->membership_model->count_new_ventas();
			$data['new_ordenes'] = $this->membership_model->count_new_ordenes();
			$data['main_content'] = $this->load->view("templates/{$page}", $data, true);
			$data['page'] = $page;
			$this->load->view('templates/admin_template', $data);
		}
		else
		{
			redirect('admin/login');
		}

	}

	function check_new() {
		$data['new_consultas'] = $this->membership_model->count_new_consultas();
		$data['new_ventas'] = $this->membership_model->count_new_ventas();
		$data['new_ordenes'] = $this->membership_model->count_new_ordenes();

		echo json_encode($data);

	}

	function count()
	{
		$det = $this->membership_model->get_ventas_detalle('832436');
		print_r($det[0]->productId);
		//print_r($this->membership_model->count_new_ordenes());
	}

	function login() {
		if ($this->membership->is_logged_in())
			redirect('admin');
		else
			$this->load->view('pages/login_form');
	}

	private function get_data($table){
		switch ($table) {
			case 'activities':
				$datares = $this->membership_model->all_activities(null,null,null);
				break;
			case 'downloads':
				$datares = $this->membership_model->get_downloads(null,null,null);
				break;
			case 'planes':
				$datares = $this->membership_model->get_planes();
				break;
		}
		return $datares->result();
		//print_r($datares->result());
		//echo json_encode($datares->result());
	}

	function config() {
		$data['system'] = $this->membership_model->get_system();
		$this->load_page('config', $data);

	}

	function set_config() {
		$features = $this->input->get('features');
		$this->membership_model->update_system($features);
		echo json_encode(array('result' => 'received' ));
	}

	function consultas() {
		$consultas = $this->membership_model->get_consultas();
		$data['html_table'] = build_data_table($consultas,'id="data-ventas" class="display responsive nowrap" style="width:100%"');
		$this->load_page('consultas', $data);

	}

	function depositphoto() {
		//$data['subaccounts'] = $this->providers->depositphoto->getSubaccounts();
		$data['subaccounts'] = $this->providers->depositphoto->subaccounts();
		$data['subscriptions'] = $this->providers->depositphoto->getSubscriptionOffers();
		//header('Content-Type: application/json');
		$this->load_page('depositphoto', $data);
	}

	function get_subaccount_data($subaccountId) {
		//echo json_encode($this->providers->depositphoto->getSubaccountData($subaccountId));
		echo json_encode($this->providers->depositphoto->subaccounts('data', $subaccountId));
	}

	function ordenes()
	{
		$data['ordenes'] = $this->membership_model->get_ordenes();
		$this->load_page('ordenes', $data);
	}

	function ventas()
	{
		$data['ventas'] = $this->membership_model->get_ventas();
		$this->load_page('ventas', $data);
	}

	function get_ventas_detalle($orderId) {
		echo json_encode($this->membership_model->get_ventas_detalle($orderId));
	}

	function ventas_detalle($orderId='', $status='', $username='', $activity='')
	{
		// switch ($status)
		// {
		// 	case 'ord':
		// 		$this->membership_model->change_venta_status($orderId, 'pro');
		// 		break;
		// 	case 'new':
		// 		$this->membership_model->change_venta_status($orderId, '');
		// 		break;
		// }
		// $detalles = $this->membership_model->get_ventas_detalle($orderId);
		$order['orderId'] = $orderId;
		$order['status'] = $status;
		$order['username'] = $username;
		$order['tranType'] = $activity;
		if ( ($status !== '' && $status !== 'pro') && $activity == 'compra_plan') {
			$resp = $this->membership->confirmar_orden($order);
			$detalles = $resp['detalles'];
		}
		else {
			$detalles = $this->membership_model->get_ventas_detalle($orderId);
		}

		$data['html_table'] = build_data_table($detalles, "style='font-size:12px'");
		$data['result'] = $detalles;

		echo json_encode($data);
	}

	function get_buyer($username='')
	{
		$rowname = $this->membership_model->get_fullname($username);
		echo json_encode($rowname->row());
	}

	function fullpath()
	{
		echo "path is:";
		echo realpath("img/Acuerdo de licencia de Fotosearch.pdf");
	}

	function do_upload() {
    $config['upload_path']   = './';
    $config['allowed_types'] = 'csv|txt';
    $config['max_size']      = 100;
		$config['overwrite']		 = TRUE;

    $this->load->library('upload', $config);

    if ( ! $this->upload->do_upload('userfile')) {
      $error = array('error' => $this->upload->display_errors());
      $this->load->view('load_csv', $error);
    }
    else {
      //$data = array('upload_data' => $this->upload->data());
      //$this->load->view('upload_success', $data);
			$this->read_excel();
    }
  }

	function read_excel() {
		$this->load->library('csvreader');

    $result =   $this->csvreader->parse_file('planes.csv');
		$this->membership_model->insert_csv($result);
		redirect('admin?table=planes');
    //$data['datares'] =  $result;
    //$this->load->view('view_csv', $data);
		//$this->index('planes');
	}

}
