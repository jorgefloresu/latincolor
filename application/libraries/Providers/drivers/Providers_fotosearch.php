<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Providers_fotosearch extends CI_Driver {

	public $result = array( 'count' => 0, 'images' => array() );
	public $preview = array();
	public $instant = array();
	var $CI;

	function __construct() {
			$this->CI =& get_instance();
	    $this->CI->load->library('fotosearch_api');
	}

	function search($query) {
			$offset = $query['prov']['fs']['limit'] * ($query['page'] - 1);
    	$params = array(
                FotosearchParams::SEARCH_QUERY  => $query['keyword'],
                FotosearchParams::SEARCH_LIMIT  => $query['prov']['fs']['limit'],
                FotosearchParams::SEARCH_OFFSET => $offset,
								FotosearchParams::SEARCH_SUBSCRIPTION => 'true'

            );
				//echo 'Orientacion: '.$query['orientacion'];
				if ($query['orientacion']!='') {
					switch ($query['orientacion']) {
						case 'Horizontal':
							$orientacion = 'h';
							break;
						case 'Vertical':
							$orientacion = 'v';
							break;
						default:
							$orientacion = 's';
					}
					$params[FotosearchParams::SEARCH_ORIENTATION] = $orientacion;
				}
				switch ($query['medio']){
					case 'Videos':
						$params[FotosearchParams::SEARCH_TYPE] = 'VIDEO';
						break;
					case 'Vectores':
						$params[FotosearchParams::SEARCH_TYPE] = 'VECTOR';
						break;
				}
        $res = $this->CI->fotosearch_api->search($params);
        //return $this->convertResult($res);
				return $res;
	}

	function preview($imagecode) {
        //$data['back'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //$_SESSION['mysession'] = $data['back'];

        $res = $this->CI->fotosearch_api->getMediaData($imagecode);
        return $this->convertPreview($res);
    }

  function download($item) {
				/* $media = $this->CI->input->post('id');
        $size = $this->CI->input->post('size');
				$price = $this->CI->input->post('price');
        $license = $this->CI->input->post('license');
        $username = $this->CI->input->post('username');
				$img_url = $this->CI->input->post('thumb'); */

				$media = $item['id'];
				$size = $item['size'];
				$price = $item['price'];
				$license = $item['license'];
				$username = $item['username'];
				$img_url = $item['thumb'];

				$usage = 'sale';
        $saveAs = 'fs_'.$media;

        $res = $this->CI->fotosearch_api->getDownloadURL($usage, $saveAs, $size, $media);
        $imgFile = $this->CI->fotosearch_api->goDownload($res->download_id);

				$this->CI->load->model('membership_model');
					$rec = array(
						'username' => $username,
						'date' => date("Y-m-d H:i:s"),
						'img_code' => $media,
						'img_provider' => 'Fotosearch',
						'img_url' => $img_url,
						'img_price' => $price,
						'price_type' => 'credits',
						'resolution' => '',
						'size' => $size,
						'img_dimension' => '',
						'img_pixels' => '',
						'license_type' => $license,
						'license_id' => 'No License'
					);
					$this->CI->membership_model->record_download($rec);

				//return array('url' => $saveAs, 'licenseid' => $size);

        return array('url' => $imgFile, 'licenseid' => 'No License');

    }

		function instant($imagecode) {
				$res = $this->CI->fotosearch_api->getMediaData($imagecode);
				$this->instant['source'] = 'instant';
				$this->instant['license'] = 'standard';
				$this->instant['tag'] = $this->price_item_cart( array(
							'id' 			=> (string)$res->id,
							'price' 	=> $this->set_price((string)$res->resolutions->{'2'}->price),
							'width'		=> $res->resolutions->{'2'}->phys_dims,
							'height'	=> '',
							'size' 		=> '2',
							'sizelbl' => 'S',
							'desc' 		=> $res->title,
							'license' => $this->instant['license'],
							'thumb' 	=> (string)$res->thumbnail_url,
							'subscription' => 0,
							'provider'=> 'Fotosearch'
						));

				return $this->instant;
		}

    function convertPreview($obj) {
        $this->preview['image'] = (string)$obj->preview_url;
        $this->preview['id'] = (string)$obj->id;
        $this->preview['title'] = $obj->title;
        $this->preview['keywords'] = implode(', ', $obj->keywords);
        $this->preview['sizes'] = '';
				$this->preview['sizes'] .= "<tr><td class='table-sizes' colspan='2'><div class='collection collection-size'>";
				$this->preview['source'] = 'preview';
        foreach ($obj->resolutions as $key => $s) {
        	$license = 'standard';
        	$this->preview['sizes'] .= $this->price_item( array(
						'id' 			=> (string)$obj->id,
						'price' 	=> $this->set_price($s->price),
						'width'		=> $s->phys_dims,
						'height'	=> '',
						'size' 		=> $key,
						'sizelbl' => ($key=='2'?'S':($key=='3'?'M':($key=='4'?'L':($key=='5'?'XL':$key)))),
						'desc' 		=> $obj->title,
						'license' => $license,
						'thumb' 	=> (string)$obj->thumbnail_url,
						'subscription' => 0,
						'provider'=> 'Fotosearch'
					));

        }
				$this->preview['sizes'] .= "</div></td></tr>";

        return $this->preview;
    }

	function convertResult($obj) {
		//print_r($obj);
      if ($obj) {
				 if (!isset($obj->error_message)) {
				// 	$this->result['count'] = 0;
				// 	$this->result['images'] = [];
				// } else {
				//if ('No results' !== $obj) {
					$this->result['count'] = $obj->meta->total_count;
					foreach ($obj->objects as $value) {

						$html  = "<div><img src='$value->preview_url' height='170'/><div class='caption'>";
						$html .= "<a class='view-link' href='".base_url('main/preview/')."/$value->id/?provider=Fotosearch'><i class='material-icons'>zoom_in</i></a>";
						$html .= "<a class='cart-link' href='".base_url('main/instant/')."/$value->id/?provider=Fotosearch'><i class='material-icons' style='padding-left:10px;'>add_shopping_cart</i></a>";
						//$html .= "<a href='#'><i class='material-icons' style='padding-left:10px;'>file_download</i></a>";
						$html .= "</div></div>";

						$type = ((string)$value->type=='VIDEO') ? 'video' :
										((string)$value->type=='PHOTO') ? 'image' : 'vector';

						$this->result['images'][] = [
												 'provider'=> 'Fotosearch',
	                       'id'    	 => (string)$value->id,
	                       'title' 	 => (string)$value->title,
	                       'thumb'   => (string)$value->preview_url,
												 'type'		 => $type,
												 'html'		 => $html
	                ];
	        }
				}
		}

		return $this->result;
	}

}
