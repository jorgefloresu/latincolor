<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getfotolia extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->library('fotolia_api');
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
                'FFFFFF,000000'  => 'Blanco y Negro'
                );
                
            $form['orien_opt'] = array(
                'vertical'  => 'Vertical',
                'horizontal'  => 'Horizontal'
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
            $data_header['title'] = "Fotolia API";

            // Setup the form  
            $this->setupform($data);
            
            // Get the rest values of the search form
            $data['keywords'] = $this->input->get('keywords');
            $data['color_sel'] = $this->input->get('color');
            $data['orien_sel'] = $this->input->get('orientation');

            if ( !empty($data['keywords']) )
            {
                $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                
                // Set parameters and invoke the search method
                $params = array (
                    FotoliaParams::SEARCH_QUERY => $data['keywords'],
                    FotoliaParams::SEARCH_LIMIT => '12',
                    FotoliaParams::SEARCH_OFFSET => $data['page'],
                    FotoliaParams::SEARCH_FILTERS => array(
                        FotoliaParams::SEARCH_ORIENTATION => $data['orien_sel'],
                        FotoliaParams::SEARCH_COLOR => $data['color_sel']
                        )
                   );

                $res = $this->fotolia_api->getSearchResults($params);
                $totalrows = array_shift($res);

                foreach ($res as $value) {
                    $data['result'][] = array(
                        'code' => $value['id'],
                        'caption' => $value['title'],
                        'thumburl' => $value['thumbnail_160_url']
                        );
                }

                // Create paginator control
                $data['totalrows'] = $totalrows;
                $this->pagination($data['totalrows']);
                $data['pagination'] = $this->pagination->create_links();

            }

            $this->load->view('templates/header', $data_header);
            $this->load->view('templates/image_result', $data);                       
            $this->load->view('templates/footer');

            //print_r($response);

        }

        function imgdetail()
        {

            $imagecode = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $size = "400";
            $res = $this->fotolia_api->getMediaData($imagecode, $size);

            $image_data = array(
                'url' => $res['thumbnail_url'],
                'prop' => array(
                    'Image Type' => $res['media_type_id'],
                    'Width' => $res['thumbnail_width'],
                    'Height' => $res['thumbnail_height']
                    )
                );

            echo json_encode($image_data);
        }

        function pagination($totalrows)
        {
            //pagination settings
            $config['base_url'] = site_url('getfotolia/search');
            $config['total_rows'] = $totalrows;
            $config['per_page'] = 12;
            $config["uri_segment"] = 3;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = 5; //floor($choice);
            $config['use_page_numbers'] = false;
            $config['first_url'] = site_url('getfotolia/search/1').'?'.http_build_query($_GET);;
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
            $media = $this->input->get('media');
            /*
            $imagecode = "96191614";
            $size = "1000";
            $response = $this->fotolia_api->getMediaData($imagecode, $size);
            //$response = $this->fotolia_api->getMediaComp($imagecode);
            echo "<pre>";
            print_r($response);
            echo "</pre>";
            //print_r( $response['thumbnail_url']);
            */       
            $output = '<div style="margin-left:190px">';
            if ($dot == 'search'){
                $params = array (
                    FotoliaParams::SEARCH_QUERY => 'business',
                    FotoliaParams::SEARCH_LIMIT => 5,
                    FotoliaParams::SEARCH_OFFSET => 0);

                $res = $this->fotolia_api->getSearchResults($params);
                foreach ($res as $value) {
                    $output .= $value['id'].'<br/>';
                }
                $output .= "Total found: " . $res['nb_results'] . "<br/>";
            }

            if ($dot == 'mdata'){
                $res = $this->fotolia_api->getMediaData($media, 1000);
                $arr = array_map(function($el){ return $el['name']; }, $res['keywords']);
                $output .= $this->show(implode(', ', $arr));
            }
            
            $output .= $this->show($res);

            $output .= '</div>';


            $output .= '<div style="z-index: 1;position: absolute;left: 0;top: 0;">';
            $attr = array('method'=>'get');
            $output .= form_open(__CLASS__, $attr);
            $output .= form_radio('f', 'search', true) .'SearchResult <br/>';
            $output .= form_fieldset(form_radio('f', 'mdata') .'Media details');
            $output .= 'Media: '.'<br/>';
            $output .= form_input('media', $media) .'<br/>';
            $output .= form_fieldset_close();
            $output .= form_submit('submit', 'submit');
            $output .= form_close();
            $output .= "</div>";

            echo $output;

        }
        
        function index_s()
        {
            /*
            $data['result'] = array(
                'Fotolia' => array(
                    'rows' => 100,
                    'data' => array()
                    )
               );
            $data['result'] = array(
                'Deposit' => array(
                    'rows' => 200,
                    'data' => array()
                    )
               );
               */
            $data['result']['Fotolia'] = array(
                    'rows' => 100,
                    'data' => array()
               );
            $data['result']['Deposit'] = array(
                    'rows' => 200,
                    'data' => array()
               );
               
            $data['result']['Fotolia']['data'][] = ['code' => 'code1', 'caption' => 'cap1', 'thumburl' => 'thumburl1'];
            $data['result']['Fotolia']['data'][] = ['code' => 'code2', 'caption' => 'cap2', 'thumburl' => 'thumburl2'];
            $data['result']['Deposit']['data'][] = ['code' => 'code3', 'caption' => 'cap3', 'thumburl' => 'thumburl3'];
            $data['result']['Ingimages']['rows'] = 150;

            foreach ($data['result'] as $key => $value) {
                    echo $key.':<br/>';
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            //echo $key2.':<br/>';
                            //print_r($value2).'<br/>';
                            foreach ($value2 as $key3 => $value3) {
                                if (is_array($value3)) {
                                    //echo $key3.':<br/>';
                                    echo $value3['code'].'<br/>';
                                    //foreach ($value3 as $key4 => $value4) {
                                    //    echo $key4.'=>'.$value4.'<br/>';
                                    //}
                                }
                                //else
                                //    echo $key3.'=>'.$value3.'<br/>';
                            }
                        }
                        //else
                        // echo $key2.'=>'.$value2.'<br/>';
                    }
            }
            /*
            $data['result']['provider'] = 'Fotolia';
            $data['result']['rows'] = 100;
            $data['result']['data'] = array('atrib1' => 'value1', 'atrib2' => 'value2');
            $data['result']['provider'] = 'Deposit';
            $data['result']['rows'] = 200;
            $data['result']['data'] = array('atrib3' => 'value3', 'atrib4' => 'value4');
            */

            print_r($data);
        	//echo $this->fotolia_api->getApiKey();
            /*
        	$params = array (
        		'words' => 'business',
        		'limit' => '2'
        		);
        	$response = $this->fotolia_api->getSearchResults($params);
            print_r($response);
            //$shifted = array_shift($response);
            echo "<br/><br/>";
        	
        	foreach ($response as $response_item => $response_content) 
        	{
        		if (is_array($response_content)) 
        		{
        			echo $response_item.":<br/>"; //image array
        			foreach ($response_content as $image_data => $image_value) 
        			{
        				if (is_array($image_value)) 
        				{
        					echo $image_data.":<br/>"; //licenses or categories array
        					foreach ($image_value as $image_more_data => $image_more_value) 
        					{
        						if (is_array($image_more_value)) 
        						{
        							echo $image_more_data.":<br/>"; //licenses prices or more categories
        							foreach ($image_more_value as $image_more_detail => $image_more_det_value) 
        							{
        								print_r($image_more_detail."=>".$image_more_det_value."<br/>");
        							}
        						}
        						else
        							print_r($image_more_data."=>".$image_more_value."<br/>");
        					}
        				}
        				else
        					print_r($image_data."=>".$image_value."<br/>");
        			}
        		}
        		else
        			print_r($response_item."=>".$response_content."<br/>");
        	}
        	
        	//echo "Precio de: ".$response[0]['id'] ." en: ".$response[0]['licenses'][0]['name'] ." es: ".$response[0]['licenses'][0]['price'];
        	//echo "<br/>";
            //print_r($response);
            //print_r($shifted);
            */

        }

}