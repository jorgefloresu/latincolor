<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getfotosearch extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->library('fotosearch_api');
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
			$form['color_opt'] = array(
                  '1'  => 'Color',
                  '0'  => 'Blanco y Negro'
                );
			$form['orien_opt'] = array(
                  '1'  => 'Vertical',
                  '2'  => 'Horizontal',
                  '3'  => 'Cuadrada' 
                );

			// Adding attributes to the form and filters
            $selectpicker = 'class="selectpicker" form="navform" ';
            $form['color_tag'] = 'id="color" title="Color: Todos" ' . $selectpicker ;
            $form['orien_tag'] = 'id="orientation" title="Orientación: Todas" ' . $selectpicker;
            $form['search_source'] = __CLASS__ ;
            $form['form_action'] = __CLASS__ . '/search/0';       	
      	
        }
        
        function search($page=0)
        {
        	$data_header['title'] = "Fotosearch API";

        	// Setup the form and initializing the XML element as an array to capture the search result      	
        	$this->setupform($data);

        	// Get the values of the search form
       		$data['keywords'] = $this->input->get('keywords');
			$data['color_sel'] = $this->input->get('color');
			$data['orien_sel'] = $this->input->get('orientation');

            // Search only if there´s content on keywords field
        	if ( !empty($data['keywords']) )
        	{
				$data['page'] = $page; //($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        		
                // Set parameters and invoke the search method
	        	$params = array(
	        			FotosearchParams::SEARCH_QUERY  => $data['keywords'],
	        			FotosearchParams::SEARCH_OFFSET => $data['page']
	        		);
	        	
	        	$res = $this->fotosearch_api->search($params);
	        	if ($res)
	        	{	
                foreach ($res->objects as $value) {
                    $data['result'][] = array(
                        'code' => (string)$value->id,
                        'caption' => (string)$value->title,
                        'thumburl' => (string)$value->thumbnail_url
                        );
                }

				// Create the pagination control using the total value from results
				$data['totalrows'] = $res->meta->total_count;
				$this->pagination($data['totalrows']);
				$data['pagination'] = $this->pagination->create_links();
 				}
			}

        	$this->load->view('templates/header', $data_header);
			$this->load->view('templates/image_result', $data);						
			$this->load->view('templates/footer');

        }
        		
		function imgdetail($imagecode)
		{
			//$imagecode = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$res = $this->fotosearch_api->getMediaData($imagecode);
			
			foreach ($res->resolutions as $value) {
				$resolutions[] = $value->name;
			}
			
			$image_data = array(
				'url' => (string)$res->preview_url,
                'prop' => array(
    				'Image Type' => (string)$res->type,
    				'Artist' => (string)$res->artist,
                    'Resolutions' => implode($resolutions,',')
                    )
				);

			echo json_encode($image_data);

		}
		
        function pagination($totalrows)
        {
            //pagination settings
            $config['base_url'] = site_url('Getfotosearch/index/');
            $config['total_rows'] = $totalrows;
            $config['per_page'] = 25;
            $config["uri_segment"] = 3;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = 5; //floor($choice);
            $config['use_page_numbers'] = false;
            $config['first_url'] = site_url('Getfotosearch/index/').'?'.http_build_query($_GET);;
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
	        		//'format' => 'json',
	        		FotosearchParams::SEARCH_QUERY => 'animal avian background biology bird isolated',
	        		FotosearchParams::SEARCH_LIMIT => 25,
	        		FotosearchParams::SEARCH_OFFSET => $offset
	        	);
				$res = $this->fotosearch_api->search($params);

			   	if(isset($res->objects)){
	               	$this->pagination($res->meta->total_count);
	               	$output .= $this->pagination->create_links().'<br/>';

			      	$i = $offset;
			      	foreach($res->objects as $value){
			            $output .= $i."-".$value->id.'<br/>';
                    	++$i;
                        //$output .= ' - '.'<a href="'.$value->webformatURL.'" download="">Download</a>';
			     }
                 $output .= $this->show($res);
			      
			   }
			   else
			      $output .= $this->show('no result<br><br>');
            }

            if ($dot == 'mdata') {
            	$res = $this->fotosearch_api->getMediaData('66459202');
               	//echo $res->hits[0]->pageURL;
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
        	/*
        	$data = array(
        		'keywords' => 'business',
        		'color_sel' => '',
        		'orien_sel' => '',
        		'page' => 0
        	);

        	$params = array(
	        			FotosearchParams::SEARCH_QUERY  => $data['keywords'],
	        			FotosearchParams::SEARCH_OFFSET => $data['page']
	        		);
	        	
	        	$res = $this->fotosearch_api->search($params);
	        	print_r($res);
	        
 			$url = $this->searchURL($data);				
			$res = $this->getAPIresult($url);

			print_r($res);
			echo "<br/>".$res->meta->total_count;
			echo "<br/>".$res->objects[0]->artist;
			*/
			
			//$url = FOTOSEARCH_API_URL . FotosearchParams::SEARCH_CMD;
	        //$string = 'road, open, highway, mountain, street, summer, drive, journey, field, travel, escape, infinity, landscaped';
	        //$string = 'animal avian background biology bird isolated';

			/*$ch = curl_init();

			curl_setopt_array($ch, array(
	            CURLOPT_RETURNTRANSFER  => true,
	            CURLOPT_URL             => $url
	            ));
			$result = json_decode(curl_exec($ch));
			//$res = $this->fotosearch_api->post($url, $data);
			print_r($result);
			echo $result->objects[0]->id;
			*/
			//$res = $this->fotosearch_api->getMediaData('66459202');
			/*$res = $this->fotosearch_api->getDownloadURL('sale', 'testfotosearch', '3', '3842281');*/
			/*$res = $this->fotosearch_api->goDownload('27811490-f2a4-43cf-8bcb-a14ee2fef79c');
			foreach (get_headers($res) as $value) {
				header($value);
			};
			readfile($res);*/
			/*
			foreach ($res->resolutions as $value) {
				echo $value->name;
			}
			list($ancho, $alto) = getimagesize("http://comps.unlistedimages.com/ulcomp/CSP/CSP990/k10546354.jpg");
        	echo "ancho:".$ancho."<br/>";
        	echo "alto:".$alto."<br/>";
			*/
        }

}