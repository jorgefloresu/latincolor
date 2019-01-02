<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_NUMBER_OF_ROWS', 100);
define('DEF_DPUSER', 'latincolorimages');
define('DEF_DPPASS', 'latincol2016$');

class Main extends CI_Controller {

    private $logo;

    function __construct() {
        parent::__construct();
        //$this->output->cache(60);
        //$this->output->delete_cache();
        //$this->load->library('rawdata/depositclient');
        $this->load->library('membership');
        $this->load->library('language',array('language'=>'es_SV'));
    	  $this->load->driver('providers');
        $this->load->helper(['tag','material']);
        $this->logo = base_url('img/LCI-logo-Hi.png');
        //$this->load->library('session');

    }

    function login_test() {
      $this->load->view('templates/header');
      echo "<a href='#sign-in' class='modal-trigger'>prueba</a>";
      echo "<a href='".base_url('login/logout/noindex')."' id='sign-out'>logout</a>";
      echo "<a href='#' id='status'>status</a>";
      echo "<p>resp:<span></span></p>";
      $this->load->view('templates/sign_in');
      $this->load->view('templates/footer_test');
    }

	  function index() {
      add_js('home');
      $this->load_page('home');
    }

    private function load_page($page, array $pre_data = NULL) {
      if ($pre_data != NULL)
        $data = $pre_data;
      $data['logo'] = $this->logo;
      $data['keyword'] = '';
      $data['orientacion'] = '';
      $data['color'] = '';
      $data['medio'] = 'Fotos';
      $data['icon'] = base_url("img/Fotos-50.png");
      //$data['provider'] = "Economicas";
      $data['tabs'] = '';
      $data['page'] = 1;
      $data['range'] = DEF_NUMBER_OF_ROWS;
      $data['items'] = DEF_NUMBER_OF_ROWS;
      $data['logged'] = $this->membership->is_logged_in();
      $data['user_data'] = (object) array('fname'=>'','email_address'=>'');
      $data['user_info'] = '';

      //$userdata = (object) array('fname'=>'','email_address'=>'');
      if ($data['logged']){
          $data['user_data'] = $this->membership->get_user($data['logged']);
          $fullname = $this->membership_model->get_fullname($data['logged']);
          $data['user_info'] = json_encode($fullname->row());
      }
      //$data['fname'] = ($userdata->fname ?: '');
      //$data['email'] = ($userdata->email_address ?: '');
     //$_SESSION['mysession'] = 'username';

      //$this->load->view('main', $data);
      //add_js();
      $this->load->view($page, $data);
    }

    function viewcart() {
        $data['logo'] = $this->logo;
        //$data['back'] = $this->session->mysession;
        //$data['page'] = $this->load->view('cart', '', true);
        //$this->load->view('viewcart', $data);
        echo json_encode($data);

    }

    function pushpin() {
    	$this->load->view('pages/pushpin');
    }

    function get_system() {
      //print_r($this->membership->get_system('price_comision'));
      //print_r(localeconv());
      //echo BASEPATH;
      //echo sum_downloads($this->membership_model->user_downloads(),'img_price');

      $user = $this->membership_model->get_fullname('jorgefloresu');
      $data['user'] = $user->row();
      $data['orderId'] = '1234567890';
      $data['productId'] = '1234567890';
      $data['description'] = 'Plan de 5 imagenes por 30 dias';
      $data['first_name'] = $data['user']->first_name;
      $data['email_address'] = $data['user']->email_address;
      $data['password'] = '123abc456';
      $email = $this->load->view('email/Compra/mail','', TRUE);
      $email = str_replace('__USUARIO__', strtoupper($data['user']->first_name), $email);
      $email = str_replace('__ORDEN__', $data['orderId'], $email);
      $email = str_replace('__PRODUCTO__', $data['productId'], $email);
      $email = str_replace('__DESCRIPCION__', strtoupper($data['description']), $email);
      //$email = str_replace('__CLAVE__', $data['password'], $email);
      echo $email;

    }

    function dtest($page,$limit,$offset) {
      $query = array('page'=>$page,
                     'prov'=>array('fs'=>array('limit'=>$limit,'offset'=>$offset)),
                     'keyword'=>$this->input->get('keyword'),
                     'medio'=>'Videos',
                     'color'=>'',
                     'orientacion'=>$this->input->get('orientacion')
                    );
      //$res = $this->providers->dreamstime->search($query);
      $res = $this->providers->fotosearch->search($query);
      //$res = $this->providers->depositphoto->search($query);
      header('Content-Type: application/json');
      echo json_encode($res);
    }

    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    function resolve($page) {
      $query = array('page'=>$page,
                     'keyword'=>'cinema',
                     'medio'=>'Fotos',
                     'orientacion'=>'',
                     'color'=>'',
                     'rows' => 100
                    );
      header('Content-Type: application/json');
      //$query['prov'] = $this->providers->resolve($query['page'], $query['rows']);
      echo json_encode($this->providers->search($query));
      //echo json_encode($this->providers->resolve($page,100));
      //print_r($this->providers->resolve($page,100));

    }

    function cut(){
      $source = array('dp'=>10,'dt'=>79,'fs'=>10);
      $cut = $this->providers->cutres($source,100);
      print_r($cut);
    }

    function search($page) {
        $data['logo'] = $this->logo;
        $data['keyword'] = ($this->input->get('keyword') ?: "");

        $data['orientacion'] = ($this->input->get('orientacion') ?: '');
        $data['color'] = ($this->input->get('color') ?: '');
        $data['medio'] = ($this->input->get('medio') ?: 'Fotos');
        $data['icon'] = base_url("img/".$data['medio']."-50.png");

        //$data['provider'] = ($this->input->get('provider') ?: "Economicas");
        $data['range'] = ($this->input->get('range') ?: DEF_NUMBER_OF_ROWS);
        $data['tabs'] = '';

        $data['logged'] = $this->membership->is_logged_in();
        $data['user_data'] = (object) array('fname'=>'','email_address'=>'');
        $data['user_info'] = '';

        if ($data['logged']){
            $data['user_data'] = $this->membership->get_user($data['logged']);
            $fullname = $this->membership_model->get_fullname($data['logged']);
            $data['user_info'] = json_encode($fullname->row());
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            $query = array('page'=>$page,
                           'keyword'=>$data['keyword'],
                           'orientacion'=>$data['orientacion'],
                           'color'=>$data['color'],
                           'medio'=>$data['medio'],
                           'rows' => $data['range']
                          );

            //if ($data['provider'] == 'Economicas') {
              $time_start = $this->microtime_float();

              $res = $this->providers->search($query);

              $data['time'] = round($this->microtime_float() - $time_start,1);

              $data['result'] = $res['images'];
              $data['preview'] = base_url('main/preview').'/';
              $data['total'] = $res['total'];
              $data['count'] = $res['count'];
              $data['cur_page'] = $page;
              $data['num_pages'] = round($data['total']/$data['range']);
              $data['totalProv'] = $res['totalProv'];

              $offset = $data['range'];
              $this->pagination($res['total'], $offset );
              $data['pags'] = $this->pagination->create_links();

            //} else {
              //$data['result'] = [];
            //}

              //header('Content-Type: application/json');
              echo json_encode($data);
        }
        else {
            $data['page'] = $page;
            add_css(['justifiedGallery.min','magnific-popup','main']);
            add_js(['api','chart']);
            $this->load->view('main', $data);
        }
    }

    function previewPage() {
        return $this->load->view('preview', '', true);
    }

    function preview($imagecode){
        $data['logo'] = $this->logo;
        //$data['back'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //$_SESSION['mysession'] = $data['back'];
        //$data['page'] = $this->load->view('preview', '', true);
        $res = $this->providers->{strtolower($this->input->get('provider'))}->preview($imagecode);
        if (count($res)==0)
          header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

        $data = array_merge($data, $res);
        echo json_encode($data);
    }

    function instant($imagecode) {
        $data['logo'] = $this->logo;
        $res = $this->providers->{strtolower($this->input->get('provider'))}->instant($imagecode);
        if (count($res)==0)
          header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

        $data = array_merge($data, $res);
        echo json_encode($data);
    }

    function find_similar ($imagecode,$provider) {
      $data['thumb'] = $this->providers->{strtolower($provider)}->similar($imagecode);
      echo json_encode($data);
    }

    function planes() {
      add_css('planes');
      add_js(['common','planesprom']);
      $this->load_page('planes');

    }

    function servicios() {
      add_css('servicios');
      add_js(['common','servicios']);
      $this->load_page('servicios');

    }

    function consultor()
    {
      add_css('consultor');
      add_js(['common','miconsultor']);
      $data['pre_consulta'] = $this->input->get('cons');
      $data['pre_detalle_label'] = ($this->input->get('cons')?
        'Ingresa aquí los codigos de las imagenes que no recibiste':
        'Detalle de la consulta');
      $this->load_page('consultor', $data);
    }

    function send_consulta()
    {
      $this->membership_model->add_consulta();
      echo json_encode(['response'=>'OK']);
    }

    function contactanos()
    {
      add_css('contactanos');
      add_js(['common','contactanos']);
      $this->load_page('contactanos');
    }

    function user(){
      $username = $this->membership->is_logged_in();
      if ($username != '') {
        $fullname = $this->membership_model->get_fullname($username);
        $data['user_data'] = $fullname->row();
        $data['user_info'] = json_encode($fullname->row());
        $download_list = $this->membership_model->get_downloads($username);
        $data['download_list'] = $download_list->result();
        $data['planes_list'] = $this->membership_model->user_planes();
        //$data['sum_downloads'] = sumar_valores($data['download_list'],'img_price');
        $data['sum_downloads'] = $this->membership_model->sum_price('downloads', 'img_price', $username);
        //$data['sum_planes'] = sumar_valores($data['planes_list'],'valor');
        $data['sum_planes'] = $this->membership_model->sum_planes($username);
        add_css(['perfect-scrollbar','user']);
        add_js(['user']);
        $this->load_page('user', $data);
      }
      else
        redirect();
    }

    function getPlan() {
      //header('Content-Type: application/json');
      //echo json_encode($this->providers->depositphoto->getSubscriptionOffers());
      //print_r($this->membership->planes_params());
      //print_r($this->membership->get_plan('Depositphoto','Diaria',50,1));
      $data['sum_downloads'] = $this->membership_model->sum_planes('jorgefloresu');
      echo $data['sum_downloads'];
    }

    function planes_params() {
      echo json_encode($this->membership->planes_params());
    }

    function calc_plan() {
      $provider = $this->input->post('provider');
      $frecuencia = $this->input->post('frecuencia');
      $cantidad = $this->input->post('cantidad');
      $tiempo = $this->input->post('tiempo');
      //header('Content-Type: application/json');

      echo json_encode($this->membership->get_plan($provider, $frecuencia, $cantidad, $tiempo));
    }

    function ptest ($id=NULL){
      $query = array('page'=>1,
                     'keyword'=>'woman',
                     'orientacion'=>'',
                     'color'=>'',
                     'medio'=>'Videos',
                     'rows' => 100,
                     'prov'=>array('dt'=>array('limit'=>33,'offset'=>0))
                    );
      //$res = $this->providers->fotosearch->search($query);
      //$res = $this->providers->depositphoto->preview("99393646");
      $res = $this->providers->dreamstime->preview($id);
      //$res = $this->providers->dreamstime->search($query);
      //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
      //echo "count:".count($res);
      print_r($res);
    }

    function down() {
      $res = $this->providers->dreamstime->download();
      print_r($res);
    }

    function download() {

        switch ($this->input->post('provider')) {
        	case 'Fotosearch':
        		$res = $this->providers->fotosearch->download();
        		echo json_encode($res);
        		break;
          case 'Dreamstime':
        		$res = $this->providers->dreamstime->download();
        		echo json_encode($res);
        		break;
        	default:
        		$res = $this->providers->depositphoto->download();
        		echo json_encode($res);
        }
    }

    function reDownload()
    {
      $license_id = $this->input->post('lid');
      $provider = $this->input->post('provider');
      $res = $this->providers->{strtolower($provider)}->reDownload($license_id);
      echo json_encode($res);
    }

    function pagination($totalrows, $offset) {
		//pagination settings
		$config['base_url'] = site_url('main/search/');
		$config['total_rows'] = $totalrows;
		$config['per_page'] = $offset;
		$config["uri_segment"] = 3;
		//$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = 2; //floor($choice);
		$config['use_page_numbers'] = TRUE;
    $config['display_pages'] = FALSE;

    // $parsed = parse_url(site_url('main/search/1').'?'.http_build_query($_GET));
    // $query = $parsed['query'];
    // parse_str($query, $params);
    // unset($params['np']);
    //
		// $config['first_url'] = site_url('main/search/1').'?'.http_build_query($params);
    $config['first_url'] = site_url('main/search/1').'?'.http_build_query($_GET);
		if (count($_GET) > 0)
		{
			$config['suffix'] = '?' . http_build_query($_GET, '', "&");
      //$config['suffix'] = '?' . http_build_query($params, '', "&");
		}

		//config for bootstrap pagination class integration
		$config['full_tag_open'] = '<p class="current-page" style="display:inline;border: 1px solid;padding: 6px 10px;"></p> de <p class="num-pages" style="display:inline;""></p><ul class="pagination" style="margin-top:0; margin-bottom:0;display:inline">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<i class="material-icons">first_page</i>';
		//$config['last_link'] = '<i class="material-icons">last_page</i>';
    $config['last_link'] = FALSE;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		//$config['prev_link'] = '&laquo';
    $config['prev_link'] = '<i class="material-icons">chevron_left</i>';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		//$config['next_link'] = '&raquo';
    $config['next_link'] = '<i class="material-icons">chevron_right</i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

    }


}
