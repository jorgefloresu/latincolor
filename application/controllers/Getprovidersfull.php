<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('NO_XML', true);
define('DEF_NUMBER_OF_ROWS', 25);
define('DEF_MEDIA_SIZE', 600);
define('DP_USER', 'latincolorimages');
define('DP_PASS', 'latincol2016$');


class Getprovidersfull extends CI_Controller {

        const PROVIDERS = array('Fotolia', 'Fotosearch', 'Ingimages', 'Depositphoto', 'Panthermedia');
        //const PROVIDERS = array('Fotolia', 'Pixabay', 'Fotosearch', 'Ingimages', 'Depositphoto', 'Panthermedia');

        function x__construct()
        {
            parent::__construct();
            $this->load->library('membership');
            if (!$this->membership->is_logged_in())
                redirect('pages/view');
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
            $form['form_action'] = site_url(__CLASS__ . '/search');       	
      	
        }

        function index()
        {
            //$data_header['title'] = "All API Providers";
            $data['keywords'] = $this->input->get('keywords');
            $data['color_sel'] = '';
            $data['orien_sel'] = '';
            //$data['limit'] = $this->input->get('per_page') ? $this->input->get('per_page') : 0;
            $this->setupform($data);

            $this->load->library('membership');
            $data['logged'] = $this->membership->is_logged_in();
            $this->load->model('membership_model');
            $data['incart'] = $this->membership_model->count_cart_items($data['logged']);

            //$this->load->view('templates/header', $data_header);
            //$this->load->view('templates/image_result_galic', $data);            
            //$this->load->view('templates/footer');

            $data['title'] = 'Search';
            $data['result_topbar'] = $this->load->view('templates/result_topbar', $data, true);
            $data['result_searchbar'] = $this->load->view('templates/result_searchbar', $data, true);
            $data['result_optionsbar'] = $this->load->view('templates/result_optionsbar', '', true);
            $data['result_breadcrumb'] = $this->load->view('templates/result_breadcrumb', '', true);
            $data['result_imagedetail'] = $this->load->view('templates/result_imagedetail', '', true);
            $data['sign_in'] = $this->load->view('templates/sign_in', '', true);
            $data['view_cart'] = $this->load->view('templates/view_cart', '', true);
            $data['right_sidebar'] = $this->load->view('templates/right_sidebar', '', true);
            $data['pay_window'] = $this->load->view('pages/payment_form', '', true);
            $data['main_content'] = 'templates/image_result_isotope';
            $this->load->view('templates/main_template', $data); 
        }

        function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
        }

        function search($page=0, $provider='', $restart=0)
        {
            $time_start = $this->microtime_float();

        	// Get the values of the search form
            $data_header['title'] = 'All API Providers';
       		$data['keywords'] = $this->input->get('keywords');
			$data['color_sel'] = $this->input->get('color');
			$data['orien_sel'] = $this->input->get('orientation');
            $data['next_limit'] = $page + DEF_NUMBER_OF_ROWS;

            //$data['search_source'] = __CLASS__;
            $this->setupform($data);

            // Search only if there´s content on keywords field
        	if ( !empty($data['keywords']) )
        	{
                $data['result'] = array();
                //$keyp = self::PROVIDERS;
                //$keyp = $provider;
                $totalrows = 0;
                if ( $provider == '')
                    foreach (self::PROVIDERS as $keyp) {
                        if ($restart>0)
                                $totalrows  += $this->SearchOn($keyp, $data, 0, $page);
                            //$rows[$keyp]  = $this->SearchOn($keyp, $data, 0, $page);
                        else
                                $totalrows  += $this->SearchOn($keyp, $data, $page);
                            //$rows[$keyp]  = $this->SearchOn($keyp, $data, $page);
                        /*echo json_encode($data);
                        $data['result'] = array();
                        $time_end = $this->microtime_float();
                        $time = $time_end - $time_start;

                        echo "<br/>La busqueda tomo $time segundos<br/>";*/

                    }
                else
                {
                    if ($restart>0)
                        $totalrows  = $this->SearchOn($provider, $data, 0, $page);
                        //$rows[$keyp]  = $this->SearchOn($keyp, $data, 0, $page);
                    else
                        $totalrows  = $this->SearchOn($provider, $data, $page);
                        //$rows[$keyp]  = $this->SearchOn($keyp, $data, $page);
                    /*echo json_encode($data);
                    $data['result'] = array();
                    $time_end = $this->microtime_float();
                    $time = $time_end - $time_start;

                    echo "<br/>La busqueda tomo $time segundos<br/>";*/
                }
                //$totalrows = array_sum($rows);
                $data['totalrows'] = $totalrows;
                
                //$this->pagination($data['totalrows']);
				//$data['pagination'] = $this->pagination->create_links();
                //$data['pagination'] = $this->setpagination($totalrows);
                //$data['pagination'] = '';
                
                //$data['html'] = $this->load->view('templates/search_result_fwall', $data, true);
                //if ($totalrows > 0)
                //    $data['html'] = $this->result_isotope($data['result'], $page);
                //else
                //    $data['html'] = '';

                echo json_encode($data);
                //$time_end = $this->microtime_float();
                //$time = $time_end - $time_start;

                //echo "La busqueda tomo $time segundos\n";
                //echo "<pre>";print_r($data);echo "</pre>";
                //echo htmlspecialchars($data['html']) ;
                //echo json_encode($data['result'][$keyp]);
            }

            //$this->load->view('templates/header', $data_header);
            //$this->load->view('templates/image_result', $data);            
            //$this->load->view('templates/footer'); 
        }

        function result_water($result, $page)
        {
            $res = '';
            foreach ($result as $provider => $content)              
                foreach ($content as $item => $data) 
                    if (is_array($data)) 
                        foreach ($data as $key => $images) 
                            if( !empty($images['thumburl']) ) 
                                $res .= '<img src="'.$images['thumburl'].'" id="page'.$page.'"/>';
                                       /* 'data-toggle="modal" data-target="#myModal" '.                  
                                        'data-url="'.site_url(__CLASS__.'/imgdetail').'/'.$provider.'/'. $images['code'].'" '.
                                        'data-img="'.$images['code'].'" '.
                                        'data-caption="'.$images['caption'].'" /></div>';*/
            return $res;
        }

        function result_grida($result, $page)
        {
            $res = '';
            foreach ($result as $provider => $content)              
                foreach ($content as $item => $data) 
                    if (is_array($data)) 
                        foreach ($data as $key => $images) 
                            if( !empty($images['thumburl']) ) 
                                $res[] = '<div class="item not-responsive">' . 
                                        '<a class="modal-trigger" href="#modal1" '.
                                        'data-url="'.site_url(__CLASS__.'/imgdetail').'/'.$provider.'/'. $images['code'].'" '.
                                        'data-img="'.$images['code'].'" '.
                                        'data-caption="'.$images['caption'].'" '.
                                        'data-thumb="'.$images['thumburl'].'" '.
                                        'data-provider="'.$provider.'">'.                                     
                                        '<img class="z-depth-1 hoverable" src="'.$images['thumburl'].'" '.
                                        '/></a></div>';
            return $res;
        }

        function result_mason($result, $page)
        {
            $res = '';
            foreach ($result as $provider => $content)              
                foreach ($content as $item => $data) 
                    if (is_array($data)) 
                        foreach ($data as $key => $images) 
                            if( !empty($images['thumburl']) ) 
                                $res .= "<div class='grid-item'>" .
                                        '<img src="'.$images['thumburl'].'" id="page'.$page.'"/></div>';
                                       /* 'data-toggle="modal" data-target="#myModal" '.                  
                                        'data-url="'.site_url(__CLASS__.'/imgdetail').'/'.$provider.'/'. $images['code'].'" '.
                                        'data-img="'.$images['code'].'" '.
                                        'data-caption="'.$images['caption'].'" /></div>';*/
            return $res;
        }

        function result_isotope($result, $page)
        {
            $res = array();
            foreach ($result as $provider => $content)              
                foreach ($content as $item => $data) 
                    if (is_array($data)) 
                        foreach ($data as $key => $images) 
                            if( !empty($images['thumburl']) )
                                $res[] = '<div class="grid-item">'.
                                        '<img src="'.$images['thumburl'].'"/></div>';
                                        //'<a class="modal-trigger" href="#modal1" '.                                        
                                        //'data-toggle="modal" data-target="#myModal" '.                  
                                        //'data-url="'.site_url(__CLASS__.'/imgdetail').'/'.$provider.'/'. $images['code'].'" '.
                                        //'data-img="'.$images['code'].'" '.
                                        //'data-caption="'.$images['caption'].'" '.
                                        //'data-thumb="'.$images['thumburl'].'" '.
                                        //'data-provider="'.$provider.'">'. 
                                        //"<span class='centerer'></span>".
                                        
            return $res;
        }        

        function result_coll($result, $page)
        {
            $res = '';
            foreach ($result as $provider => $content)              
                foreach ($content as $item => $data) 
                    if (is_array($data)) 
                        foreach ($data as $key => $images) 
                            if( !empty($images['thumburl']) ) 
                                $res[] = "<div class='Image_Wrapper'>" .
                                        '<img src="'.$images['thumburl'].'" id="page'.$page.'"/></div>';
                                       /* 'data-toggle="modal" data-target="#myModal" '.                  
                                        'data-url="'.site_url(__CLASS__.'/imgdetail').'/'.$provider.'/'. $images['code'].'" '.
                                        'data-img="'.$images['code'].'" '.
                                        'data-caption="'.$images['caption'].'" /></div>';*/
            return $res;
        }

        function result_fwall($result, $page)
        {
            $res = '';
            foreach ($result as $provider => $content)              
                foreach ($content as $item => $data) 
                    if (is_array($data)) 
                        foreach ($data as $key => $images) 
                            if( !empty($images['thumburl']) ) 
                                $res[] = "<div class='brick' style='width:{width}px;'>" .
                                        '<img src="'.$images['thumburl'].'" width="100%" id="page'.$page.'"/></div>';
                                       /* 'data-toggle="modal" data-target="#myModal" '.                  
                                        'data-url="'.site_url(__CLASS__.'/imgdetail').'/'.$provider.'/'. $images['code'].'" '.
                                        'data-img="'.$images['code'].'" '.
                                        'data-caption="'.$images['caption'].'" /></div>';*/
            return $res;
        }

        function SearchOn($provider, &$data, $page, $initfrom=0)
        {
            $search_limit = ($initfrom>0 ? $initfrom : DEF_NUMBER_OF_ROWS);

            switch ($provider) {

            case 'Fotosearch':
                $rows = 0;
                $this->load->library('fotosearch_api');
                $params = array(
                        FotosearchParams::SEARCH_QUERY  => $data['keywords'],
                        FotosearchParams::SEARCH_LIMIT  => $search_limit,
                        FotosearchParams::SEARCH_OFFSET => $page
                    );
                
                $res = $this->fotosearch_api->search($params);
                if ($res)
                {   
                    $rows = $res->meta->total_count;
                    $data['result']['Fotosearch'] = array(
                            'rows' => $rows,
                            'data' => array()
                       );

                    foreach ($res->objects as $value) {
                        $data['result']['Fotosearch']['data'][] = [
                                'code'     => (string)$value->id,
                                'caption'  => (string)$value->title,
                                'thumburl' => (string)$value->thumbnail_url
                            ];
                    }
                }
                else {
                    $data['result']['Fotosearch'] = array(
                            'rows' => 0,
                            'data' => array()
                       );
                }

                return $rows;
                break;

            case 'Depositphoto':
                $initparams = array('sessionid' => null);
                $this->load->library('depositclient', $initparams);
                //$this->load->library('depositclient');
                $params = array(
                        RpcParams::SEARCH_QUERY       => $data['keywords'],
                        RpcParams::SEARCH_ORIENTATION => $data['orien_sel'],
                        //RpcParams::SEARCH_USERNAME => 'Kama',
                        RpcParams::SEARCH_LIMIT       => $search_limit,
                        RpcParams::SEARCH_OFFSET      => $page,
                        RpcParams::SEARCH_COLOR       => urlencode($data['color_sel'])
                    );

                // Convert search result from Object to Array (only the first result level)
                $res = $this->depositclient->search($params);
                $rows = 0;
                //print_r($res);
                if ($res)
                {   
                    $rows = empty($res->result) ? 0 : $res->count;
                    $data['result']['Depositphoto'] = array(
                            'rows' => $rows,
                            'data' => array()
                       );

                    foreach ($res->result as $value) {
                        $data['result']['Depositphoto']['data'][] = [
                            'code'     => (string)$value->id,
                            'caption'  => (string)$value->title,
                            'thumburl' => (string)$value->medium_thumbnail
                        ];
                    }
                }
                return $rows;
                break;

            case 'Fotolia':
                $this->load->library('fotolia_api');
                $params = array (
                        FotoliaParams::SEARCH_QUERY   => $data['keywords'],
                        FotoliaParams::SEARCH_LIMIT   => $search_limit,
                        FotoliaParams::SEARCH_OFFSET  => $page,
                        FotoliaParams::SEARCH_FILTERS => array(
                            FotoliaParams::SEARCH_ORIENTATION => $data['orien_sel'],
                            FotoliaParams::SEARCH_COLOR       => $data['color_sel']
                        )
                   );

                $res = $this->fotolia_api->getSearchResults($params);
                $totalrows = array_shift($res); //Quitamos el primer elemento ya que contiene el conteo de rows

                $data['result']['Fotolia'] = array(
                        'rows' => $totalrows,
                        'data' => array()
                     );

                foreach ($res as $value) {
                    $data['result']['Fotolia']['data'][] = [
                        'code'     => $value['id'],
                        'caption'  => $value['title'],
                        'thumburl' => $value['thumbnail_160_url']
                    ];
                }
                return $totalrows;
                break;

            case 'Ingimages':
                // Search in Ingimages
                $this->load->library('ingimages_api');
                $params = array(
                        IngimagesParams::SEARCH_QUERY       => $data['keywords'],
                        IngimagesParams::SEARCH_OFFSET      => $page + $search_limit,
                        IngimagesParams::SEARCH_COLOR       => $data['color_sel'],
                        IngimagesParams::SEARCH_ORIENTATION => $data['orien_sel']
                    );

                $res = $this->ingimages_api->search($params);
                $rows = 0;
                if ($res)
                {   
                    $rows = (empty($res->image) ? 0 : (string)$res->results['total']);
                    $data['result']['Ingimages'] = array(
                            'rows' => $rows,
                            'data' => array()
                        );
                    $width = $height = 0;
                    foreach ($res as $value) {
                        $thumb = (string)$value->thumburl;
                        if ($thumb != '') {
                            $data['result']['Ingimages']['data'][] = [
                                    'code'     => (string)$value['code'],
                                    'caption'  => (string)$value->imgcaption,
                                    'thumburl' => $thumb
                                ];
                        }
                    }

                }
                return $rows;
                break;

            case 'Panthermedia':
                //Search in PantherMedia
                $this->load->library('panthermedia_api');
                $params = array(
                        PantherParams::SEARCH_QUERY => $data['keywords'],
                        PantherParams::SEARCH_PAGE  => $page / DEF_NUMBER_OF_ROWS,
                        PantherParams::SEARCH_LIMIT => $search_limit 
                    );

                $res = $this->panthermedia_api->search($params, 'title');
                $rows = 0;
                if ($res)
                {
                    $rows = ($res['items']['items'] == 0 ? 0 : (string)$res['items']['total']);
                    $data['result']['Panthermedia'] = array(
                        'rows' => $rows,
                        'data' => array()
                        );
                    if ($res['items']['items'] > 1)
                        foreach ($res['items']['media'] as $key => $value) {
                            $data['result']['Panthermedia']['data'][] = [
                                    'code'     => (string)$value['id'],
                                    'caption'  => $value['title'],
                                    'thumburl' => (string)$value['thumb']
                                ];
                        }
                    elseif ($res['items']['items'] == 1) {
                            $data['result']['Panthermedia']['data'][] = [
                                    'code'     => (string)$res['items']['media']['id'],
                                    'caption'  => $res['items']['media']['title'],
                                    'thumburl' => (string)$res['items']['media']['thumb']
                                ];
                    }

                }
                return $rows;
                break;

            case 'Pixabay':
                //Search in Pixabay (Free images)
                $page += 1;

                $this->load->library('pixabay_api');
                $params = array(
                       PixabayParams::SEARCH_QUERY => $data['keywords'],
                       PixabayParams::SEARCH_PAGE  => $page,
                       PixabayParams::SEARCH_LIMIT => $search_limit 
                    );

                $res = $this->pixabay_api->search($params);
                $rows = 0;
                if ($res)
                {
                    $rows = (string)$res->total;
                    $data['result']['Pixabay'] = array(
                            'rows' => $rows,
                            'data' => array()
                        );
                    if (count($res->hits)>0)
                        foreach ($res->hits as $value) {
                            $data['result']['Pixabay']['data'][] = [
                                'code'      => (string)$value->id,
                                'caption'   => 'Pixabay-'.$value->id,
                                'thumburl'  => (string)$value->previewURL
                            ];
                        }
                }
                return $rows;
                break;

            }
        }

		function imgdetail($provider, $imagecode)
		{
            switch ($provider) 
            {
				case 'Fotosearch':
					$image_data = $this->Fotosearch_Details($provider, $imagecode);           
					break;

				case 'Ingimages':
					$image_data = $this->Ingimages_Details($provider, $imagecode);
					break;

				case 'Fotolia':
					$image_data = $this->Fotolia_Details($provider, $imagecode);
					break;

 				case 'Depositphoto':
					$image_data = $this->Depositphoto_Details($provider, $imagecode);
					break;

                case 'Panthermedia':
                    $image_data = $this->Panthermedia_Details($provider, $imagecode);
                    break;

                case 'Pixabay':
                    $image_data = $this->Pixabay_Details($provider, $imagecode);
                    break;

            }			

			echo json_encode($image_data);

		}

        //function imgdownload($provider, $imagecode, $usage, $resolution, $saveAs='photosale_download')
        function imgdownload($provider, $imagecode)
        {
            $this->load->library('membership');
            if (!$this->membership->is_logged_in())
                $image_data = 'notlogged'; // array('url' => 'notlogged');
            else
                switch ($provider) 
                {
                    case 'Fotosearch':
                        //$image_data = $this->Fotosearch_Download($usage, $saveAs, $resolution, $imagecode);           
                        $image_data = $this->Fotosearch_Download(
                                        $this->input->get('usage'), 
                                        $this->input->get('saveas'), 
                                        $this->input->get('res'), 
                                        $imagecode
                                        );           
                        break;

                    case 'Ingimages':
                        $image_data = $this->Ingimages_Download(
                                        $imagecode, 
                                        $this->input->get('plan'), 
                                        $this->input->get('type'), 
                                        $this->input->get('orderid'), 
                                        $this->input->get('userid'), 
                                        $this->input->get('price')
                                        );
                        break;

                    case 'Fotolia':
                        $image_data = $this->Fotolia_Download($imagecode);
                        break;

                    case 'Depositphoto':
                        $image_data = $this->Depositphoto_Download($imagecode);
                        break;

                }           

            echo json_encode($image_data);

        }

        function get_download_ref($provider, $media, $plan, $type, $userId, $price, $orderId)
        {
            if ( $provider == 'Ingimages' ){
                $this->load->library('ingimages_api');
                $res = $this->ingimages_api->ReportSales($plan, $media, $type, $orderId, $userId, $price);
                //echo $res->downloadreference;
                //$res = array('status'=>'OK', 'downloadreference'=>'12345');
                echo json_encode($res);
            }
        }

        function Fotosearch_Download($imagecode, $usage, $resolution, $saveAs)
        {
            $this->load->library('fotosearch_api');
            $res = $this->fotosearch_api->getDownloadURL($usage, $saveAs, $resolution, $imagecode);
            $imgFile = $this->fotosearch_api->goDownload($res->download_id);
            foreach (get_headers($imgFile) as $value) {
                header($value);
            };
            readfile($imgFile);
        }

        function Ingimages_Download($media, $type, $downloadRef, $id, $orderId)
        {
            $this->load->model('membership_model');
            $this->membership_model->set_download_ref($id, $downloadRef, $orderId);

            $this->load->library('ingimages_api');
            $token = $this->ingimages_api->getDownloadToken($media, $type, $downloadRef);
            //$tokenUsed = 0;              

            $downloadLink = $this->ingimages_api->goDownload($media, $downloadRef, $token);
            //if ($tokenUsed == 0){
            //    $tokenUsed = 1;
                $res['url'] = $downloadLink;
            //$res = array('result'=>'OK', 'url'=>'http://www.google.com');
                echo json_encode($res);
            //}
        }

        function Depositphoto_Download($media, $size, $license, $username)
        {
            $this->load->model('membership_model');
            $subaccountid = $this->membership_model->other_member_id($username);

            $initparams = array('sessionid' => null);
            $this->load->library('depositclient', $initparams);

            $id = $this->depositclient->login(DP_USER, DP_PASS);
            $this->depositclient->setSessionId($id->sessionid);

            $mediaInfo = $this->depositclient->getMedia($media, $license, $size, $subaccountid);

            $res['url'] = $mediaInfo->downloadLink;
            $res['licenseid'] = $mediaInfo->licenseId;

            echo json_encode($res);
            
        }

        function Panthermedia_Download($media, $article, $id_download=null)
        {
            $this->load->library('panthermedia_api');
            //$res = $this->panthermedia_api->download_image($media, $id_article);
            $dRes = $this->panthermedia_api->download_image_preview($media);
            $res['url'] =  'data:'.$dRes['media']['mimetype'].';charset=utf-8;base64,'.$dRes['media']['base64'];            
            echo json_encode($res);
        }

        function Fotosearch_Details($provider, $imagecode)
        {
			$this->load->library('fotosearch_api');
			$res = $this->fotosearch_api->getMediaData($imagecode);
			
			foreach ($res->resolutions as $key => $value) {
                switch ($value->name) {
                    case 'lowres':
                        $fullname = 'Low';
                        break;
                    case 'medres':
                        $fullname = 'Medium';
                        break;
                    case 'hires':
                        $fullname = 'High';
                        break;                    
                    default:
                        $fullname = 'Ultra High';
                        break;
                }

				$resolutions[] = array(
                    'code' => $key,
                    'name' => $fullname,
                    'dimension' => $value->phys_dims,
                    'license' => 'Standard',
                    'price_type' => "$ ",
                    'price' => $value->price,
                    'pixels' => $value->px_dims
                    );
			}
			
			$det = array(
				'url' => (string)$res->preview_url, //(string)$res->xl_preview_url,
                'prop' => array(
                	'Source' => $provider,
                    'Code' => (string)$res->id,
                    'Title' => $res->title,
                    'Thumbnail' => (string)$res->thumbnail_url,
                    'Keywords' => implode(', ', $res->keywords),
                    'More' => array(
                        'Provider' => $provider,
                        'Buy method' => 'cash',
                        'Contributor ID' => (string)$res->artist,
        				'Media Type' => (string)$res->type,
        				'Model Release' => ($res->model_release==1 ? 'Si' : 'No'),
                        'Property Release' => ($res->property_release==1 ? 'Si' : 'No'),
                        'Subscription' => ($res->subscription_allowed ? 'Disponible' : 'No Disponible')
                        )
                    ),
                'resolutions' => $resolutions
				);

			return $det;
        }


        function Depositphoto_Details($provider, $imagecode)
        {
            $initparams = array('sessionid' => null);
            $this->load->library('depositclient', $initparams);
        	//$this->load->library('depositclient');
            $res = $this->depositclient->getMediaData($imagecode);

            foreach ($res->sizes as $key => $value) {
                switch ($key) {
                    case 'xs':
                        $fullname = 'Very Low';
                        break;
                    case 's':
                        $fullname = 'Low';
                        break;
                    case 'm':
                        $fullname = 'Medium';
                        break;
                    case 'l':
                        $fullname = 'High';
                        break;                    
                    case 'xl':
                        $fullname = 'Ultra High';
                        break;                    
                    case 'vect':
                        $fullname = 'Vector';
                        break;                    
                    default:
                        $fullname = 'Extended';
                        break;
                }

                $resolutions[] = array(
                    'code' => $key,
                    'name' => $fullname,
                    'dimension' => $value->width.' x '.$value->height,
                    'license' => ($fullname=='Extended' ? $fullname : 'Standard'),
                    'price_type' => "credits",
                    'price' => $value->credits,
                    'pixels' => $value->width.' x '.$value->height
                    );
            }

			$det = array(
				'url' => $res->url_big,
                'prop' => array(
                	'Source' => $provider,
                    'Code' => (string)$res->id,
                    'Title' => (string)$res->title,
                    'Thumbnail' => $res->huge_thumb,
                    'Keywords' => implode(', ', $res->tags),
                    'More' => array(
                        'Provider' => $provider,
                        'Buy method' => 'credits',
                        'Contributor ID' => (string)$res->username,
                        'Editorial' => ($res->iseditorial == 1 ? 'Si' : 'No'),
        				'Image Type' => (string)$res->itype,
                        'Published' => date_format(date_create($res->published),'d-m-Y'),
        				'Categories' => implode(', ', (array)$res->categories)
                        )
                   ),
                'resolutions' => $resolutions
				);

			return $det;
        }


        function Fotolia_Details($provider, $imagecode)
        {
            function media_type($id){
                if ($id=='1')
                    $med = 'Photo';
                elseif ($id=='2')
                    $med = 'Illustration';
                else
                    $med = 'Vector';
                return $med;
            }

        	$this->load->library('fotolia_api');
            $size = "1000";

            $res = $this->fotolia_api->getMediaData($imagecode, $size);

            foreach ($res['licenses'] as $value) {
                $license = $value['name'];
                switch ($license) {
                    case 'XS':
                        $fullname = 'Very Low';
                        break;
                    case 'S':
                        $fullname = 'Low';
                        break;
                    case 'M':
                        $fullname = 'Medium';
                        break;
                    case 'L':
                        $fullname = 'High';
                        break;                    
                    case 'XL':
                        $fullname = 'Ultra High';
                        break;                    
                    case 'XXL':
                        $fullname = 'Super High';
                        break;                    
                    case 'X':
                        $fullname = 'Extended';
                        break;                    
                    default:
                        $fullname = 'Vector';
                        break;
                }

                $resolutions[] = array(
                    'code' => $license,
                    'name' => $fullname,
                    'dimension' => $res['licenses_details'][$license]['phrase'],
                    'license' => ($fullname=='Extended' ? $fullname : 'Standard'),
                    'price_type' => "credits",
                    'price' => $value['price'],
                    'pixels' => $res['licenses_details'][$license]['width'].'x'.$res['licenses_details'][$license]['height']
                    );
            }

            $det = array(
                'url' => $res['thumbnail_url'],
                'prop' => array(
                	'Source' => $provider,
                    'Code' => $res['id'],
                    'Title' => $res['title'],
                    'Thumbnail' => $res['thumbnail_url'],
                    'Keywords' => implode(', ', array_map(function($el){ return $el['name']; }, $res['keywords'])),
                    'More' => array(
                        'Provider' => $provider,
                        'Buy method' => 'credits',
                        'Contributor ID' => $res['creator_name'],
                        'Media Type' => media_type((string)$res['media_type_id']),
                        'Creation Date' => date_format(date_create($res['creation_date']),'d-m-Y'),
                        'Has Releases' => ((string)$res['has_releases']=='1' ? 'Si' : 'No'),
                        'Subscription' => ((string)$res['available_for_subscription']=='1' ? 'Disponible' : 'No Disponible')
                        )
                    ),
                'resolutions' => $resolutions
                );

            return $det;
        }


        function Ingimages_Details($provider, $imagecode)
        {
            function name_orientation($orientation){
                if ($orientation=='1')
                    $or = 'Portrait';
                elseif ($orientation=='2')
                    $or = 'Landscape';
                else
                    $or = 'Square';
                return $or;
            }

			$this->load->library('ingimages_api');

			$res = $this->ingimages_api->getMediaData($imagecode);
			$imagecode .= '.jpg';

            $resolutions[] = array(
                'code' => '1',
                'name' => 'High',
                'dimension' => 'Pick your size after purchase',
                'license' => 'Standard',
                'price_type' => "$ ",
                'price' => '1',
                'pixels' => 'Pick your size after purchase'
                );

			$det = array(
				'url' => $this->ingimages_api->getMediaPreview($imagecode, DEF_MEDIA_SIZE),
				'prop' => array(
                    'Source' => $provider,
                    'Code' => (string)$res->code,
                    'Title' => (string)$res->title,                    
                    'Thumbnail' => (string)$res->thumburl,
                    'Keywords' => str_replace(',', ', ', $res->keywords),
                    'More' => array(
                        'Provider' => $provider,
                        'Buy method' => 'cash',
                        'Contributor ID' => (string)$res->contributorId,
                        'Color Type' => ($res->colorType ? 'Color' : 'B/N'),
                        'Orientation' => name_orientation((string)$res->orientation),
                        'Model Release' => ($res->modelRelease ? 'Si' : 'No'),
                        'Property Release' => ($res->propertyRelease ? 'Si' : 'No'),
                        'Image Type' => (string)$res->availableAssetTypes
                        )
				    ),
                'resolutions' => $resolutions
				);		

			return $det;       	
        }

        function Panthermedia_Details($provider, $imagecode)
        {
            $this->load->library('panthermedia_api');

            $res = $this->panthermedia_api->get_media_info($imagecode, true);

            foreach ($res['articles']['singlebuy_list']['singlebuy'] as $value) {
                $license = $value['sizes']['article']['description'];
                switch ($license) {
                    case 'XXL':
                        $fullname = 'Super High';
                        break;
                }
                $curr = $value['sizes']['article']['currency'];
                $resolutions[] = array(
                    'code' => $value['sizes']['article']['id'],
                    'name' => $fullname,
                    'dimension' => '',
                    'license' => 'Standard',
                    'price_type' => ( $curr=='eur' ? '&euro;' : $curr ),
                    'price' => round($value['sizes']['article']['price']),
                    'pixels' => $value['sizes']['article']['width'].' x '.$value['sizes']['article']['height']
                    );
                foreach ($value['extended_rights']['article'] as $valuex) {
                        $resolutions[] = array(
                        'code' => $valuex['id'],
                        'name' => $valuex['name'],
                        'dimension' => '',
                        'license' => 'Extended',
                        'price_type' => ( $valuex['currency']=='eur' ? '&euro;' : $valuex['currency'] ),
                        'price' => round($valuex['price']),
                        'pixels' => ''
                        );
                }
            }

            $det = array(
                'url' => $res['media']['preview_url'],
                'prop' => array(
                    'Source' => $provider,
                    'Code' => $res['media']['id'],
                    'Title' => $res['metadata']['title'],
                    'Thumbnail' => $res['media']['thumb_170_url'],
                    'Keywords' => $res['metadata']['keywords'],
                    'More' => array(
                        'Provider' => $provider,
                        'Buy method' => 'cash, credits',
                        'Contributor ID' => $res['metadata']['author_username'],
                        'Editorial' => ucfirst($res['metadata']['editorial']),
                        'Creation Date' => date_format(date_create($res['metadata']['date']),'d-m-Y'),
                        'Model Release' => ($res['metadata']['model_release'] ? 'Si' : 'No'),
                        'Property Release' => ($res['metadata']['property_release'] ? 'Si' : 'No'),
                        'Image Type' => $res['media']['mime_type']
                    )
                ),
                'resolutions' => $resolutions
                );

            return $det;

        }

        function Pixabay_Details($provider, $imagecode)
        {
            $this->load->library('pixabay_api');
            $params = array(
                PixabayParams::MEDIA_ID => $imagecode
                );

            $res = $this->pixabay_api->search($params);
            $types = array(
                'code'   => ['_640', '_960'],
                'name'   => ['Medium', 'High'],
                'pixels' => ['640x426','960x720']
                );
            for ($i=0; $i < count($types['name']); $i++) { 
                $resolutions[] = array(
                    'code' => $types['code'][$i],
                    'name' => $types['name'][$i],
                    'dimension' => '',
                    'license' => 'Standard',
                    'price_type' => "$ ",
                    'price' => 0,
                    'pixels' => $types['pixels'][$i]
                    );
                }
            $det = array(
                'url' => $res->hits[0]->webformatURL,
                'prop' => array(
                    'Source' => $provider,
                    'Code' => (string)$res->hits[0]->id,
                    'Title' => 'Pixabay',                    
                    'Thumbnail' => (string)$res->hits[0]->previewURL,
                    'Keywords' => $res->hits[0]->tags,
                    'More' => array(
                        'Provider' => $provider,
                        'Buy method' => 'cash',
                        'Contributor ID' => (string)$res->hits[0]->user,
                        'Color Type' => '',
                        'Orientation' => '',
                        'Model Release' => '',
                        'Property Release' => '',
                        'Image Type' => (string)$res->hits[0]->type
                        )
                    ),
                'resolutions' => $resolutions
                );      

            return $det;        
        }


        function setpagination($rows)
        {
            $this->pagination($rows);
            $pagination = $this->pagination->create_links();
            return $pagination;
        }
 
        function pagination($totalrows)
        {
			//pagination settings
			$config['base_url'] = base_url(__CLASS__);  //site_url(__CLASS__.'/search')
			$config['total_rows'] = $totalrows;
			$config['per_page'] = DEF_NUMBER_OF_ROWS;
			$config['uri_segment'] = 3;
			$choice = $config['total_rows'] / $config['per_page'];
			$config['num_links'] = 5; //floor($choice);
			$config['use_page_numbers'] = false;
            $config['page_query_string'] = TRUE;

			//$config['first_url'] = site_url(__CLASS__.'/search/0').'?'.http_build_query($_GET);;
            $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);;
			if (count($_GET) > 0) 
			{
				//$config['suffix'] = '?' . http_build_query($_GET, '', "&");
                $config['suffix'] = '&' . http_build_query($_GET, '', "&");
			}				

			//config for bootstrap pagination class integration
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev"><a href="#!"><i class="material-icons">chevron_left';
			$config['prev_tag_close'] = '</i></a></li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#!">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li class="waves-effect">';
			$config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
        
        }

}