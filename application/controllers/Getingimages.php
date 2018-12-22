<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_NUMBER_OF_ROWS', 6);

class Getingimages extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->library('ingimages_api');
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
            $form['form_action'] = __CLASS__ . '/search/1';       	     	
        }

        function search()
        {
        	$data_header['title'] = "Ingimages API";

            // Setup the form  
         	$this->setupform($data);

	        // Get the values of the search form
	       	$data['keywords'] = $this->input->get('keywords');
			$data['color_sel'] = $this->input->get('color');
			$data['orien_sel'] = $this->input->get('orientation');

            // Search only if there´s content on keywords field
        	if ( !empty($data['keywords']) )
        	{
				$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        		// Set parameters and invoke the search method
	        	$params = array(
					IngimagesParams::SEARCH_QUERY => $data['keywords'],
					IngimagesParams::SEARCH_PAGE => $data['page'],
					IngimagesParams::SEARCH_COLOR => $data['color_sel'],
					IngimagesParams::SEARCH_ORIENTATION => $data['orien_sel']
	        		);
	        	$res = $this->ingimages_api->search($params);

	        	foreach ($res as $value) {
		        	$data['result'][] = array(
		        		'code' => (string)$value['code'],
		        		'caption' => (string)$value->imgcaption,
		        		'thumburl' => (string)$value->thumburl
		        		);
	        	}

				// Create the pagination control using the total value from results
				$data['totalrows'] = $res->results['total'];
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

			$res = $this->ingimages_api->getMediaData($imagecode);
			$imagecode .= '.jpg';

			$image_data = array(
				'url' => $this->ingimages_api->getMediaPreview($imagecode),
				'prop' => array(
					'Image Type' => (string)$res->availableAssetTypes,
					'Color Type' => (string)$res->colorType,
					'Orientation' => (string)$res->orientation,
					'Keywords' => (string)$res->keywords
					)
				);
			
			echo json_encode($image_data);
		}
		
        function pagination($totalrows)
        {
			//pagination settings
			$config['base_url'] = site_url('getingimages/search');
			$config['total_rows'] = $totalrows;
			$config['per_page'] = 12;
			$config["uri_segment"] = 3;
			$choice = $config["total_rows"] / $config["per_page"];
			$config["num_links"] = 5; //floor($choice);
			$config['use_page_numbers'] = true;
			$config['first_url'] = site_url('getingimages/search/1').'?'.http_build_query($_GET);;
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

        function index()
        {
        	$dot = $this->input->get('f');
        	$media = $this->input->get('media');
        	$ref = $this->input->get('ref');
        	$tok = $this->input->get('tok');
        	$used = $this->input->get('used');

        	if ($dot == 's'){
	        $string = 'road, open, highway, mountain, street, summer, drive, journey, field, travel, escape, infinity, landscaped';
	        $string = 'business';
	        	$params = array(
					IngimagesParams::SEARCH_QUERY => $string,
					IngimagesParams::SEARCH_OFFSET => 25,
					IngimagesParams::SEARCH_COLOR => '',
					IngimagesParams::SEARCH_ORIENTATION => ''
	        		);
	        	$res = $this->ingimages_api->search($params);
	        	foreach ($res as $value) {
	        		echo $value['code'].'<br/>';
	        	}
	        	//echo "<pre>"; print_r($res->image[0]); echo "</pre>";
	        	echo "<pre>"; print_r($res); echo "</pre>";
	        }
	        if ($dot == 'l'){
		        $res = $this->ingimages_api->getMediaData($media.'.jpg');
		        echo "<pre>"; print_r($res); echo "</pre>";
	        }
	        if ($dot == 'p'){
		        $res = $this->ingimages_api->getMediaPreview($media.'.jpg', 600);
		        echo "<pre>"; print_r($res); echo "</pre>";
	        }
			if ($dot == 'r'){
		        $res = $this->ingimages_api->ReportSales('single', $media, 1, 9, 'jorgef', 1);
		        $ref = $res->downloadreference;
	    	}
	        /*Response:
	        SimpleXMLElement Object
			(
			    [status] => OK
			    [downloadreference] => A17F846C-00DC-A0B4-2E9A1DAE48F9D1DC
			)*/

			// $dot == 't'
			if ($dot == 't') {
				//$this->input->get('ref')
		        $tok = $this->ingimages_api->getDownloadToken($media, 1, $ref);
		        $used = 0;		        
	    	}
	        // Response : A1C21A75-FA34-68D0-2451DE49C74F1664 
	        // $dot == 'd'
	        if ($dot == 'd') {
				//$this->input->get('ref'),
				//$this->input->get('tok')
	           	$res2 = $this->ingimages_api->goDownload($media, $ref, $tok);
	           	/*$params = array(
		            IngimagesParams::MEDIA_IMAGE_CODE => 'ISS_4935_06444',
		            IngimagesParams::DOWNLOAD_REF => 'CD0F58EC-FDA5-D3DD-C0F845732DFE4644',
		            IngimagesParams::DOWNLOAD_TOKEN => 'CD12ECE1-AC23-3101-E8AA6CAB1A192111'
		            );*/

	           	//$url = 'http://www.ingramapi.com/assetDownload.do?imagecode=ISS_4935_06444&downloadreference=CD0F58EC-FDA5-D3DD-C0F845732DFE4644&token=CD12ECE1-AC23-3101-E8AA6CAB1A192111';
	           	if ($used == 0){
					echo '<a href="'.$res2.'" target="">'.$res2.'</a>';
					$used = 1;
				}
				else {
		           	$ch = curl_init();
		           	curl_setopt_array($ch, array(
			            CURLOPT_POST            => true,
			            CURLOPT_RETURNTRANSFER  => true,
			            CURLOPT_URL             => $res2,
			            CURLOPT_POSTFIELDS      => $params
			        	));
		           	$result = curl_exec($ch);
		           	$xml = simplexml_load_string($result);
					if ($xml)
						echo $xml->error;
				}
				//print_r($result);
				//print_r(get_headers($res2));
				//echo "</pre>";
				//foreach (get_headers($res2) as $value) {
				//	header($value);
				//};
				//readfile($res2);
			}
				$attr = array('method'=>'get');
				$hidden = array('used'=>$used);
		        echo form_open(__CLASS__, $attr, $hidden);
		        echo 'Media: '.'<br/>';
		        echo form_input('media', $media) .'<br/>';
		        echo 'Download reference: ' .'<br/>';
		        $aref = array('name'=>'ref', 'value'=>$ref, 'size'=>50);
		        echo form_input($aref) .'<br/>';
		        echo 'Token: ' .'<br/>';
		        $atok = array('name'=>'tok', 'value'=>$tok, 'size'=>50);
		        echo form_input($atok) .'<br/>';
		        echo form_radio('f', 's') .'SearchResult <br/>';
		        echo form_radio('f', 'l') .'Media details <br/>';
		        echo form_radio('f', 'p') .'Media preview <br/>';
		        echo form_radio('f', 'r', true) .'ReportSales <br/>';
		        echo form_radio('f', 't') .'GetToken <br/>';
		        echo form_radio('f', 'd') .'DownloadUrl <br/>';
		        echo form_submit('submit', 'submit');
		        echo form_close();


			//print_r($res->results['total']);
			//print_r($res->image['code']);      	
        }

}