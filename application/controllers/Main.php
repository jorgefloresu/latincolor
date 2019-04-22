<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_NUMBER_OF_ROWS', 100);

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
        $this->load->library('taxes');

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
      $data['productId'] = '1234567890<br>0987654321<br>1122334455<br>';
      $data['description'] = 'Plan de 5 imagenes por 30 dias';
      $data['first_name'] = $data['user']->first_name;
      $data['email_address'] = $data['user']->email_address;
      $data['password'] = '123abc456';
      $data['plan']['provider'] = 'Dreamstime';
      $data['plan']['username'] = 'jafu';
      $data['plan']['password'] = '123abc456';
      $email = $this->load->view('email/Compra/mail','', TRUE);
      $email = str_replace('__USUARIO__', strtoupper($data['user']->first_name), $email);
      $email = str_replace('__ORDEN__', $data['orderId'], $email);
      $email = str_replace('__PRODUCTO__', $data['productId'], $email);
      $email = str_replace('__DESCRIPCION__', strtoupper($data['description']), $email);
      $email = str_replace('__PROVEEDOR__', $data['plan']['provider'], $email);
      $email = str_replace('__PLANUSER__', $data['plan']['username'], $email);
      $email = str_replace('__PLANPASS__', $data['plan']['password'], $email);
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
              header('Access-Control-Allow-Origin: *');
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

    function paquete_button($plan_id) {
      $info['data'] = $this->membership_model->get_planes($plan_id)->row();
      $info['iva'] = $this->taxes->set_iva($info['data']->valor);
      $info['tco'] = $this->taxes->set_tco($info['data']->valor + $info['iva'], true);

      return json_decode(json_encode($info));
    }

    function planes() {
      $data['plan'] = [
                        'small' => $this->paquete_button('DP049'),
                        'medium'=> $this->paquete_button('IN246'),
                        'large' => $this->paquete_button('DT200'),
                        'xlarge'=> $this->paquete_button('AS001')
                      ];
      add_css('planes');
      add_js(['common','planesprom']);
      $this->load_page('planes', $data);
      // echo "<pre>"; print_r($data); echo "</pre>";
      // echo $data['plan']['small']->data->provider;

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

    function nosotros()
    {
      add_js(['common','nosotros']);
      $this->load_page('nosotros');
    }

    function user(){
      $username = $this->membership->is_logged_in();
      if ($username != '') {
        $fullname = $this->membership_model->get_fullname($username);
        $data['user_data'] = $fullname->row();
        $data['user_info'] = json_encode($fullname->row());
        $download_list = $this->membership_model->get_downloads($username);
        $data['download_list'] = $download_list->result();
        $data['download_active'] = count($data['download_list'])>0 ? "active" : "";
        $data['planes_list'] = $this->membership_model->user_planes($username);
        $data['planes_active'] = count($data['download_list'])==0 && count($data['planes_list'])>0 ? "active" : "";
        //$data['sum_downloads'] = sumar_valores($data['download_list'],'img_price');
        $data['sum_downloads'] = $this->membership_model->sum_price('downloads', 'img_price', $username);
        //$data['sum_planes'] = sumar_valores($data['planes_list'],'valor');
        $data['sum_planes'] = $this->membership_model->sum_planes($username);
        
        $offset = 4;
        $this->pagination(count($data['planes_list']), $offset);
        $data['pags'] = $this->pagination->create_links();
        
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
      //$data['sum_downloads'] = $this->membership_model->sum_planes('jorgefloresu');
      //echo $data['sum_downloads'];
      $data['planes'] = $this->membership_model->user_planes('jafu');
      highlight_string("<?php\n\$data =\n" .var_export($data['planes'], true).";\n?>");
    }

    function get_plan_info() {
      $subaccountId = $this->input->post('subaccountId');
      $res = $this->membership_model->get_planes($this->input->post('planId'));
      $plan = $res->row();
      $plan_name = $this->membership_model->get_plan_dp($plan->offerId);
      $subaccountData = $this->providers->depositphoto->subaccounts('data', $subaccountId, '', 'unix');
      $data['result'] = '';
      foreach ($subaccountData->subscriptions as $value) {
        if ($value->name == $plan_name) {
          $data['estado'] = $value->status;
          $data['cantidad'] = $value->amount;
          $data['periodo'] = $value->period;
          $data['buyPeriod'] = $value->buyPeriod;
          $dateBegin = new DateTime("@$value->dateBegin");
          $dateEnd = new DateTime("@$value->dateEnd");
          $data['fecha_ini'] = $dateBegin->format('d-m-Y H:i:s');
          $data['fecha_fin'] = $dateEnd->format('d-m-Y H:i:s');
          
          $data['result'] = 'OK';
        }
      }
      echo json_encode($data);
    }

    function check_subscriptions($subaccountId) {
      
      //$res = $this->membership_model->get_planes($this->input->post('planId'));
      //$plan = $res->row();
      //$plan_name = $this->membership_model->get_plan_dp($plan->offerId);
      $subaccountData = $this->providers->depositphoto->subaccounts('data', $subaccountId, '', 'unix');
      $data['subscriptionAmount'] = $subaccountData->subscriptionAmount;
      $data['filesAmount'] = $subaccountData->filesAmount;
      foreach ($subaccountData->subscriptions as $key => $value) {
        if ($value->status == 'active') {
          $data['subscriptions'][$key]['id'] = $value->id;
          $data['subscriptions'][$key]['amount'] = $value->amount;
          $data['subscriptions'][$key]['name'] = $value->amount.' por '.($value->buyPeriod==1?'dia':'mes');
        }
      }
      echo json_encode($data);
    }

    function planes_params($medio) {
      echo json_encode($this->membership->planes_params($medio));
    }

    function calc_plan() {
      //$provider = $this->input->post('provider');
      $frecuencia = $this->input->post('frecuencia');
      $cantidad = $this->input->post('cantidad');
      $tiempo = $this->input->post('tiempo');
      $medio = $this->input->post('medio');
      //header('Content-Type: application/json');

      echo json_encode($this->membership->get_plan($medio, $frecuencia, $cantidad, $tiempo));
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
        $items = $this->input->post('items');
        foreach ($items as $key => $value) {
          $item = json_decode($value, true);
          $provider = $item['provider'];
          //$res[$key] = ['url'=>'http://depositphotos.com', 'licenseid'=>'1234455'];
          $res[$key] = $this->providers->{strtolower($provider)}->download($item);
          if ($res[$key]['url'] == '') {
            $item['result'] = 'failed';
          } else {
            $item['result'] = 'success';
            if (count($items) > 1) {
              $this->downloadUrlToFile($res[$key]['url'], $item);
            }
          }
          $res[$key] = array_merge($res[$key], $item);
        }

        if (count($res) > 1) {
          $zip_name = $this->files2zip($res);
          header('Content-type: application/zip');
          header('Content-Disposition: attachment; filename="'.$zip_name.'"');
          $zip_resp[0] = ['url'=>base_url('zip/'.$zip_name), 'result'=>'success', 'licenseid'=>''];
          echo json_encode($zip_resp);
        } else {
          echo json_encode($res);
        }

    }

    function reDownload()
    {
      $license_id = $this->input->post('lid');
      $provider = $this->input->post('provider');
      $res = $this->providers->{strtolower($provider)}->reDownload($license_id);
      /* header('Connection: Keep-Alive');
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="imagen.jpg"');
        setcookie("fileDownloadToken", "fileDownloadToken", time()+360, "/"); // 10 minutes
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        flush();
        readfile($res->downloadLink);
        exit; */
      echo json_encode($res);
    }

    function downloadUrlToFile($url, $item)
    {
      $outFileName = APPPATH . 'downloads/' . $item['provider'] . '_' . $item['id'] . '.jpg';   
      if(is_file($url)) {
          copy($url, $outFileName); 
      } else {
          $options = array(
            CURLOPT_FILE    => fopen($outFileName, 'w'),
            CURLOPT_TIMEOUT =>  28800, // set this to 8 hours so we dont timeout on big files
            CURLOPT_URL     => $url
          );

          $ch = curl_init();
          curl_setopt_array($ch, $options);
          curl_exec($ch);
          curl_close($ch);
      }
    }

    function files2zip($items) {
      $zip = new ZipArchive(); // Load zip library 
      $zip_name = FCPATH .'zip/'. time() . ".zip"; // Zip name
      if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
      { 
        // Opening zip file to load files
        $error .= "* Sorry ZIP creation failed at this time";
      }
      foreach($items as $item)
      { 
        $file = APPPATH . 'downloads/' . $item['provider'] . '_' . $item['id'] . '.jpg';
        $zip->addFile($file, basename($file)); // Adding files into zip
      }
      $zip->close();
      return basename($zip_name);
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

		//config for materialize pagination class integration
		//$config['full_tag_open'] = '<p class="current-page" style="display:inline;border: 1px solid;padding: 6px 10px;"></p> de <p class="num-pages" style="display:inline;""></p><ul class="pagination" style="margin-top:0; margin-bottom:0;display:inline">';
		$config['full_tag_open'] = '<input type="text" value="0" class="current-page" style="display:inline;border: 1px solid #CCC;width:50px;margin:0;height:1.3rem;text-align:right;padding:2px 5px;"> de <p class="num-pages" style="display:inline;""></p><ul class="pagination" style="margin-top:0; margin-bottom:0;display:inline">';
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
