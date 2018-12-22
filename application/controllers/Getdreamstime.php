<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_NUMBER_OF_ROWS', 50);

class Getdreamstime extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $params = array('sessionid' => $this->input->get('sessionid'));
            $this->load->library('dreamstime_api', $params);
        }

        function setupform(&$form)
        {
        	//Set attributes of the search form
         	$form['formattributes'] = array(
        		'class' => 'navbar-form navbar-left', 
        		'role' => 'search',
        		'method' => 'get',
        		'id' => 'navform'
        		);

			// Set filters in the search options
			for ($i=1; $i < 18 ; $i++) { 
				$form['color_opt'][$i] = 'Color '.$i;
			}
			/*
			$form['color_opt'] = array(
                  '1'  => 'Color 1',
                  '2'  => 'Color 2',
                  '3'  => 'Color 3'
                );
			*/
			$form['orien_opt'] = array(
                  'vertical'  => 'Vertical',
                  'horizontal'  => 'Horizontal',
                  'square' => 'Cuadrada'
                );

			// Adding attributes to the form and filters
            $selectpicker = 'class="selectpicker show-tick" form="navform" ';
            $form['color_tag'] = 'id="color" title="Color: Todos" ' . $selectpicker ;
            $form['orien_tag'] = 'id="orientation" title="Orientación: Todas" ' . $selectpicker;
            
            $form['search_source'] = __CLASS__ ;
            $form['form_action'] = __CLASS__ . '/search/0';       	
        }

        function search()
        {
        	$data_header['title'] = "Dreamstime API";

        	// Setup the form  
        	$this->setupform($data);

         	// Get the values of the search form
       		$data['keywords'] = $this->input->get('keywords');

        	if ( !empty($data['keywords']) )
        	{
                $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        		
                // Set parameters and invoke the search method
	        	$params = array(
	        			DreamstimeParams::SEARCH_QUERY  => $data['keywords']
	        		);

	        	// Convert search result from Object to Array (only the first result level)
                $res = $this->dreamstime_api->search($params);

                foreach ($res[result][items] as $value) {
                    $data['result'][] = array(
                        'code' => (string)$value[item][imageID],
                        'caption' => (string)$value[item][title],
                        'thumburl' => (string)$value[item][mediumThumb]
                        );
                }

	        	// Create paginator control
        		$data['totalrows'] = $res[result][totalResults];
				$this->pagination($data['totalrows']);
				$data['pagination'] = $this->pagination->create_links();

       		}

        	$this->load->view('templates/header', $data_header);
			$this->load->view('templates/image_result', $data);						
			$this->load->view('templates/footer');

        }

		function imgdetail()
		{
			$imagecode = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $res = $this->dreamstime_api->getMediaData($imagecode);

			$image_data = array(
				'url' => $res->huge_thumb,
                'prop' => array(
    				'Image Type' => (string)$res->itype,
    				'Orientation' => (string)$res->level,
                    'Width' => (string)$res->width,
                    'Height' => (string)$res->height
                    )
				);

			echo json_encode($image_data);
		}

        function pagination($totalrows)
        {
			//pagination settings
			$config['base_url'] = site_url('Getdreamstime/index/');
			$config['total_rows'] = $totalrows;
			$config['per_page'] = 5;
			$config["uri_segment"] = 3;
			$choice = $config["total_rows"] / $config["per_page"];
			$config["num_links"] = 5; //floor($choice);
			$config['use_page_numbers'] = false;
			$config['first_url'] = site_url('Getdreamstime/index/').'?'.http_build_query($_GET);;
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

            $offset = ($this->uri->segment(3) ?: 0);

            $output = '<link rel="stylesheet" href="'.base_url("materialize/css/materialize.min.css").'" />';
            $output .= '<div style="margin-left:240px">';

            if ($dot == 'search'){
            // 'road, open, highway, mountain, street, summer, drive, journey, field, travel, escape, infinity, landscaped',
                $params = array(
                        DreamstimeParams::SEARCH_OFFSET => $offset+1,
                        DreamstimeParams::SEARCH_LIMIT  => 5,
                        DreamstimeParams::SEARCH_QUERY  => 'woman'
                    );
                $res = $this->dreamstime_api->search($params);
                
                $this->pagination($res->totalResults);
                $output .= $this->pagination->create_links().'<br/>';
                $i = $offset;
                foreach ($res->items as $value) {
                    foreach ($value->item as $item){
                        ++$i;
                        $output .= $i."-".$item->imageID.'<br/>';
                    }
                }
                $output .= empty($res->totalResults) ? 'No results' : $this->show($res[0]);
                $output .= "Total found: " . $res->totalResults . "<br/>";
                $sizes = array();
                $subaccounts = array();

            }
            
            if ($dot != 'search')
                $output .= $this->show($res);

            $output .= '</div>';
        	
            $output .= '<div style="z-index: 1;position: absolute;left: 0;top: 0;">';
            $attr = array('method'=>'get');
            $output .= form_open(__CLASS__, $attr);
            $output .= form_radio('f', 'search', true) .'SearchResult <br/>';
            $output .= form_submit('submit', 'submit');
            $output .= form_close();
            $output .= "</div>";
            echo $output;

        }

}