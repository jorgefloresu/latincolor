<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("NO_XML", true);

class Getexdata extends CI_Controller {

		const API_KEY = "18584";

		const API_PASSWORD = "ANeqpTJEyAXKom0";

		const API_LANGUAGE = "es";

		const API_RESULT_PER_PAGE = 12;

		const API_IMAGE_SIZE = "450";

		const API_SEARCH = "http://www.ingramapi.com/assetSearch.do?";

		const API_IMAGE_PREVIEW = "http://www.ingramapi.com/assetPreview/";

		const API_IMAGE_DETAILS = "http://www.ingramapi.com/assetDetails.do?";


        function __construct()
        {
            parent::__construct();
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
            $js = 'onChange="this.form.submit();"';
            $form['color_tag'] = 'id="color" title="Color: Todos" ' . $selectpicker ;
            $form['orien_tag'] = 'id="orientation" title="Orientación: Todas" ' . $selectpicker;
       	
        }

        function searchURL($data)
        {
			// Add the method, keywords search and pagenumber to the string
			$url  = Getexdata::API_SEARCH;
			$url .= "keywords=".urlencode($data['keywords']);
			$url .= "&page=".$data['page'];

			/* 	Get and add the value selected from filters. If it´s required multiple select, 
				replace that two lines by the next below: 
				$url .= ($data['color_sel'][0] === '') ? '' : "&color=".$data['color_sel'][0];
				$url .= ($data['orien_sel'][0] === '') ? '' : "&orientation=".$data['orien_sel'][0]; */
				
			$url .= ($data['color_sel'] === '') ? '' : "&color=".$data['color_sel'];
			$url .= ($data['orien_sel'] === '') ? '' : "&orientation=".$data['orien_sel'];

			$url .= "&language=" . Getexdata::API_LANGUAGE;
			$url .= "&pagesize=" . Getexdata::API_RESULT_PER_PAGE;
			$url .= "&apikey=" . Getexdata::API_KEY;
			$url .= "&apipwd=" . Getexdata::API_PASSWORD;

			return $url;

        }
        
        function search()
        {
        	$data_header['title'] = "Ingimages API";

        	// Setup the form and initializing the XML element as an array to capture the search result      	
        	$data['xml'] = array();
        	$this->setupform($data);

        	// Get the values of the search form
       		$data['keywords'] = $this->input->get('keywords');
			$data['color_sel'] = $this->input->get('color');
			$data['orien_sel'] = $this->input->get('orientation');

			$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            // Search only if there´s content on keywords field
        	if ( !empty($data['keywords']) )
        	{
        		// Do search and get API result
  				$url = $this->searchURL($data);				
				$data['xml'] = $this->getAPIresult($url);

				// Create the pagination control using the total value from results
				$data['totalrows'] = $data['xml']->results['total'];
				$this->pagination($data['totalrows']);
				$data['pagination'] = $this->pagination->create_links();

				// Set the link for image preview
				$data['img_preview'] = Getexdata::API_IMAGE_PREVIEW . Getexdata::API_IMAGE_SIZE . "/";

				// Load the template to show the image preview details
				$modal = $this->load->view('pages/viewimage_details','', true);
				$data['img_details'] = str_replace('LOADER', base_url('images/ajax-loader.gif'), $modal); 
 
			}

        	$this->load->view('templates/header', $data_header);
			$this->load->view('pages/viewimages', $data);						
			$this->load->view('templates/footer');

        }
        		
		function imgdetail()
		{
			$xml = array();
			$imagecode = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$url  = Getexdata::API_IMAGE_DETAILS;
			$url .= "imagecode=".$imagecode;
			$url .= "&apikey=" . Getexdata::API_KEY;
			$url .= "&apipwd=" . Getexdata::API_PASSWORD;
			
			$xml = $this->getAPIresult($url);
			echo $xml->asXML();
		}
		
		function getAPIresult($url, $noXML=false)
		{
			$ch = curl_init();
			
			// Set URL to download
			curl_setopt($ch, CURLOPT_URL, $url);

			// User agent
			//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.1 Safari/537.36");
			// Timeout in seconds
			//curl_setopt($ch, CURLOPT_TIMEOUT, 10);

			// Should cURL return or print out the data? (true = return, false = print)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Download the given URL, and return output
			$output = curl_exec($ch);
			$res = $noXML ? $output : simplexml_load_string($output);
			
			// Close the cURL resource, and free system resources
			curl_close($ch);
			
			return $res;
		
		}

        function pagination($totalrows)
        {
			//pagination settings
			$config['base_url'] = site_url('getexdata/search');
			$config['total_rows'] = $totalrows;
			$config['per_page'] = 12;
			$config["uri_segment"] = 3;
			$choice = $config["total_rows"] / $config["per_page"];
			$config["num_links"] = 5; //floor($choice);
			$config['use_page_numbers'] = true;
			$config['first_url'] = site_url('getexdata/search/1').'?'.http_build_query($_GET);;
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

        function index_s()
        {
        	$data = array(
        		'keywords' => 'business',
        		'page' => 1,
        		'color_sel' => '',
        		'orien_sel' => ''
        	);

 			$url = $this->searchURL($data);				
			$res = $this->getAPIresult($url, NO_XML);

			echo htmlentities($res);       	
        }

        function index()
        {
        	list($ancho, $alto) = getimagesize("http://stockmediaweb.com/smsimg44/th170/Ingram/03C06184.jpg");
        	echo "ancho:".$ancho."<br/>";
        	echo "alto:".$alto."<br/>";
        	echo "ratio:".bcdiv($ancho, $alto, 16)."<br/>";

        	$var = 1;
        	$var2 = 'var';
        	$var_otro = 2;
        	$str = "El valor de $var_otro es igual que $$var2"."_otro";
        	echo $str . "\n";
        	eval("\$str = \"$str\";");
        	echo $str . "\n";

     		$rows['Fotosearch']   = 10;        		
            $rows['Depositphoto'] = 20;
    		$rows['Fotolia']      = 15;
            $rows['Ingimages']    = 5;

            foreach ($rows as $key => $value) {
                    if ($value < 6) {
                        $toComplete = (6*4) - $value;
                        switch ($key) {
                            case 'Fotosearch':
                                $rows['Depositphoto'] = 9;
                                break;
                            case 'Depositphoto':
                                $rows['Fotolia'] = 19;
                                break;
                            case 'Fotolia':
                                $rows['Ingimages'] = 14;
                                break;
                            case 'Ingimages':
                                $rows['Fotosearch'] = 11;
                                break;
                        }
                    }
            }
       		foreach ($rows as $key => $value) {
       			echo $key . "=>" . $value . "\n";
       		}

        }

        function hello_world($name)
        {
        	return "Hi ";
        }


}