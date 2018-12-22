<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_NUMBER_OF_ROWS', 5);
define('DEF_PROVIDER', 'Depositphoto');
define('DEF_LOGIN', 'latincolorimages');
define('DEF_PASSWD', 'latincol2016$');

class Deposit extends CI_Controller {

        private $sessionid;

        function __construct()
        {
            parent::__construct();
            $sessionid = array('sessionid' => $this->input->get('sessionid'));
            $this->load->library('depositclient', $sessionid);
            //$this->load->library('rawdata/depositclient');
            
        }

        function index()
        {
            $data['provider'] = DEF_PROVIDER;
            $data['pags'] = '';
            $data['items'] = DEF_NUMBER_OF_ROWS;
            $data['sessionid'] = $this->input->post('sessionid') ?: '';
            $data['subaccountid'] = $this->input->post('subaccountid') ?: '';
            $data['subsPeriodId'] = $this->input->get('subsPeriodId') ?: '';
            $data['subsCountId'] = $this->input->get('subsCountId');

            $data['header'] = $this->load->view('providers/header', $data, true);  
            $data['footer'] = $this->load->view('providers/footer', $data, true);
            $data['tabsearch'] = $this->load->view('providers/tab_search', $data, true);
            $data['tabmedia'] = $this->load->view('providers/tab_media', $data, true);
            $data['tabsubaccounts'] = $this->load->view('providers/tab_subaccounts', $data, true);
            $data['tabsubscriptions'] = $this->load->view('providers/tab_subscriptions', $data, true);
            
            $data['login'] = $this->input->post('login') ?: DEF_LOGIN;
            $data['password'] = $this->input->post('password') ?: DEF_PASSWD;
            $data['loglabel'] = $data['sessionid'] ? 'Logout' : 'Login' ;
            $data['tablogin'] = $this->load->view('providers/tab_login', $data, true);
            
            $this->load->view('providers/main', $data);
        }

        function login()
        {
            $data['login'] = $this->input->post('login');
            $data['password'] = $this->input->post('password');             
            $res = $this->depositclient->login($data['login'], $data['password']);
            $data['source'] = $res;
            $this->sessionid = $res->sessionid;
            $data['sessionid'] = $res->sessionid;
            $data['loglabel'] = $res->sessionid ? 'logout' : 'login' ;
            $data['action'] = site_url('deposit/'.$data['loglabel']);

            echo json_encode($data);
        }

        function logout()
        {
            $res = $this->depositclient->logout();
            $data['source'] = $res;
            $data['loglabel'] = 'login';
            $data['action'] = site_url('deposit/'.$data['loglabel']);
            $data['sessionid'] = '';
            $this->sessionid = '';

            echo json_encode($data);
        }
 
        function search($page)
        {
            $data['provider'] = DEF_PROVIDER;
            $data['keyword'] = $this->input->get('keyword');
            $data['items'] = $this->input->get('items');
            // 'road, open, highway, mountain, street, summer, drive, journey, field, travel, escape, infinity, landscaped',
                //RpcParams::SEARCH_QUERY  => 'animal avian background biology bird isolated', '69916121'
            $params = array(
                    RpcParams::SEARCH_QUERY  => $data['keyword'],
                    RpcParams::SEARCH_LIMIT  => $data['items'],
                    RpcParams::SEARCH_OFFSET => $page
                );
                //RpcParams::SEARCH_USERNAME => 'Kama'
            $res = $this->depositclient->search($params);
            $data['source'] = $res;
            $data['fullurl'] = $this->depositclient->fullurl;
                
            $offset = ($this->uri->segment(3) ?: 0);
            $this->pagination($res->count, $data['items']);
            $data['pags'] = $this->pagination->create_links();
            
            echo json_encode($data);

        }

		function imgdetail()
		{
            $data['mediaid'] = $this->input->get('mediaid');
            $res = $this->depositclient->getMediaData($data['mediaid']);
            $data['source'] = $res;
            $data['fullurl'] = $this->depositclient->fullurl;
            $sizestxt = array('s'=>'Small',
                            'm'=>'Medium',
                            'l'=>'Large',
                            'xl'=>'Extra Large',
                            'el0'=>'Extended');
            foreach ($res->sizes as $key => $value) {
                $sizes[$key.'-'.$value->credits] = $sizestxt[$key];
            }
            $data['sizes'] = form_dropdown('media-sizes', $sizes, array(), array('id'=>'media-sizes'))
                            .form_label('Media Sizes');

            echo json_encode($data);
		}

        function subaccounts()
        {
            $data['sessionid'] = $this->input->get('sessionid') ?: '';
            $res = $this->depositclient->getSubaccounts();
            $data['source'] = $res;                
            foreach ($res->subaccounts as $value) {
                $subaccounts[$value] = $value; 
            }
            $subaccountid = array();
            $data['subaccounts'] = form_dropdown('subaccounts', $subaccounts)
                                  .form_label('Subaccounts ID');

            //$data['subaccountid'] = $subaccountid;
            reset($subaccounts);
            $this->session->subaccountid = $subaccounts[key($subaccounts)];

            echo json_encode($data);   
        }

        function subscriptions()
        {
            $data['subaccountid'] = $this->input->get('subaccountid') ?: '';
            $res = $this->depositclient->getSubscriptionOffers($data['subaccountid']);
            $data['source'] = $res;
            $data['subscriptions'] = $res->offers;
            $data['subsPeriodId'] = $this->input->get('subsPeriod') ?: $res->offers[0]->period;
            $data['subsCountId'] = $this->input->get('subsCount') ?: $res->offers[0]->count;
            foreach ($res->offers as $key => $value) {
                $subsperiod[$value->period] = $value->period; 
                $subscount[$value->count] = $value->count;

                if ($value->period != $data['subsPeriodId'] || $value->count != $data['subsCountId'])
                    unset($data['source']->offers[$key]);                
            }

            ksort($subscount);
            $data['subsPeriod'] = form_dropdown('subsPeriod', $subsperiod, $data['subsPeriodId'])
                                  .form_label('Subscriptions period');
            $data['subsCount'] = form_dropdown('subsCount', $subscount, $data['subsCountId'])
                                 .form_label('Images on Subscriptions');

            foreach ($data['source']->offers as $key => $value) {
                $subsPlans[$value->offerId] = $value->description;
            }
            $data['subsPlans'] = form_dropdown('subsPlans', $subsPlans)
                                 .form_label('Subscriptions plans');
    
            echo json_encode($data);

        }

        function viewcart() 
        {
            $data['provider'] = DEF_PROVIDER;
            $data['sessionid'] = $this->input->post('sessionid') ?: '';
            $data['subaccountid'] = $this->input->get('subaccountid') ?: $this->session->subaccountid;
            $data['header'] = $this->load->view('providers/header', $data, true);  
            $data['footer'] = $this->load->view('providers/footer', $data, true);

            $this->load->view('providers/viewcart', $data);

        }

        function pagination($totalrows, $offset)
        {
			//pagination settings
			$config['base_url'] = site_url('deposit/search/');
			$config['total_rows'] = $totalrows;
			$config['per_page'] = $offset;
			$config["uri_segment"] = 3;
			$choice = $config["total_rows"] / $config["per_page"];
			$config["num_links"] = 2; //floor($choice);
			$config['use_page_numbers'] = false;
			$config['first_url'] = site_url('deposit/search/0').'?'.http_build_query($_GET);;
			if (count($_GET) > 0) 
			{
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
			}				

			//config for bootstrap pagination class integration
			$config['full_tag_open'] = '<ul class="pagination" style="margin-top:0; margin-bottom:0">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';

			$this->pagination->initialize($config);
        
        }

        function account_test()
        {
            $res = $this->depositclient->getSubaccountData('6911886');
            print_r($res);   
        }

        function show($info)
        {
            return '<pre>'.print_r($info, true).'</pre>';
        }
        
        function test()
        {
            $data['resp'] = "Hello ".$this->input->get('keyword');
            echo json_encode($data);
        }

}