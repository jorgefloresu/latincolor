<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getpixabay extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->library('pixabay_api');
        }

        function pagination($totalrows)
        {
            //pagination settings
            $config['base_url'] = site_url('Getpixabay/index/');
            $config['total_rows'] = $totalrows;
            $config['per_page'] = 25;
            $config["uri_segment"] = 3;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = 5; //floor($choice);
            $config['use_page_numbers'] = true;
            $config['first_url'] = site_url('Getpixabay/index/').'?'.http_build_query($_GET);;
            if (count($_GET) > 0) 
            {
                $config['suffix'] = '?' . http_build_query($_GET, '', "&");
            }               

            //config for bootstrap pagination class integration
            $config['full_tag_open'] = '<ul class="pagination">';
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

        function show($info)
        {
            return '<pre>'.print_r($info, true).'</pre>';
        }

        function index()
        {
            $dot = $this->input->get('f');
            if (!$dot)
                $res = '';

            $id = $this->input->get('id');
            $offset = ($this->uri->segment(3) ?: 1);

            $output = '<link rel="stylesheet" href="'.base_url("materialize/css/materialize.min.css").'" />';

            $output .= '<div style="margin-left:240px">';

            if ($dot == 'search'){
	            $params = array(
	                PixabayParams::SEARCH_QUERY => 'business',
	                PixabayParams::SEARCH_PAGE => $offset,
	                PixabayParams::SEARCH_LIMIT => 25 
	                );
			   $res = $this->pixabay_api->search($params);
               //$output .= $this->pixabay_api->fullurl;

               $this->pagination($res->total);
                $output .= $this->pagination->create_links().'<br/>';
		      
			   if(isset($res->hits)){
			     
			      foreach($res->hits as $key => $value){
			            $output .= $value->id;
                        $output .= ' - '.'<a href="'.$value->webformatURL.'" download="">Download</a>';
			      }
                  $output .= $this->show($res);
			      
			   }
			   else
			      $output .= $this->show('no result<br><br>');
            }

            if ($dot == 'mdata') {
                $params = array(
                    PixabayParams::MEDIA_ID => $id
                    );
               $res = $this->pixabay_api->search($params);
               echo $res->hits[0]->pageURL;
               $output .= $this->show($res);
            }   

            $output .= '</div>';

            $output .= '<div style="z-index: 1;position: absolute;left: 0;top: 0;">';
            $attr = array('method'=>'get');
            $output .= form_open(__CLASS__, $attr);
            $output .= form_radio('f', 'search', true, 'id="r1"');
            $output .= '<label for="r1">Search Result</label>';
            $output .= form_fieldset(form_radio('f', 'mdata', false, 'id="r2"') .
                        '<label for="r2">Single image</label>');
            $output .= 'Id: '.'<br/>';
            $output .= form_input('id', $id) .'<br/>';
            $output .= form_fieldset_close();
            $output .= form_submit('submit', 'submit');
            $output .= form_close();
            $output .= "</div>";
            echo $output;

        }
}


