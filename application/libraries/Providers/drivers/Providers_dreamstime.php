<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Providers_dreamstime extends CI_Driver {

	public $result = array( 'count' => 0, 'images' => array() );
	public $preview = array();
	public $instant = array();
	var $CI;

	function __construct() {
			$this->CI =& get_instance();
	    $this->CI->load->library('dreamstime_api');
	}

	function search($query) {
			$page = $query['page'];
    	$params = array(
                DreamstimeParams::SEARCH_QUERY  => $query['keyword'],
                DreamstimeParams::SEARCH_LIMIT  => $query['prov']['dt']['limit'],
                DreamstimeParams::SEARCH_PAGE => $page,
								DreamstimeParams::SEARCH_LEVEL => 0
            );
				if ($query['orientacion']!='') {
					switch ($query['orientacion']) {
						case 'Horizontal':
							$params[DreamstimeParams::SEARCH_ORIENTATION] = 'landscape';
							break;
						case 'Vertical':
							$params[DreamstimeParams::SEARCH_ORIENTATION] = 'portrait';
							break;
						case 'Cuadrada':
							$params[DreamstimeParams::SEARCH_ORIENTATION] = 'square';
							break;
					}
				}
				if ($query['color']!='') {
					$params[DreamstimeParams::SEARCH_COLOR]  = 0;
				}
				switch ($query['medio']) {
					case 'Videos':
						$params[DreamstimeParams::SEARCH_VIDEO] = 'only';
						break;
					case 'Vectores':
						$params[DreamstimeParams::SEARCH_VECTOR] = 1;
						break;
				}
        $res = $this->CI->dreamstime_api->search($params);
        //return $this->convertResult($res);
				return $res;
	}

	function preview($imagecode) {
        //$data['back'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //$_SESSION['mysession'] = $data['back'];
				if ($this->CI->input->get('type')=='video') {
					$res = $this->CI->dreamstime_api->getVideoData($imagecode);
				} else {
        	$res = $this->CI->dreamstime_api->getMediaData($imagecode);
				}

        return $this->convertPreview($res);
    }

    function download($item) {
				/* $media = $this->CI->input->post('id');
				$size = $this->CI->input->post('size');
				$price = $this->CI->input->post('price');
				$license = $this->CI->input->post('license');
				$username = $this->CI->input->post('username');
				$img_url = $this->CI->input->post('thumb'); */

				$media = $item['productId'];
				$size = $item['size'];
				$price = $item['price'];
				$license = $item['license'];
				$username = $item['username'];
				$img_url = $item['thumb'];
				$type = $item['type'];
				$url = '';
				$licenseId = '';

				$this->CI->load->model('membership_model');
				if ($type == 'image') {
					$purchased_media = $this->CI->dreamstime_api->goImageDownload($media, $size);
				} else {
					$purchased_media = $this->CI->dreamstime_api->goVideoDownload($media, $size);
				}

				if ( isset($purchased_media->downloadURL) )
				{
					$rec = array(
						'username' => $username,
						'date' => date("Y-m-d H:i:s"),
						'img_code' => $media,
						'img_provider' => 'Dreamstime',
						'img_url' => $img_url,
						'img_price' => $price,
						'price_type' => 'credits',
						'resolution' => '',
						'size' => $size,
						'img_dimension' => '',
						'img_pixels' => '',
						'license_type' => $license,
						'license_id' => (string)$purchased_media->downloadID
					);
					
					$this->CI->membership_model->record_download($rec);
					$url = (string)$purchased_media->downloadURL;
					$licenseId = (string)$purchased_media->downloadID;

				} elseif ( isset($purchased_media->errorMessage)) {
					log_message('error', '['.__CLASS__.':'.__LINE__.'] ' . $purchased_media->errorMessage);
				}

				return array('url' => $url, 'licenseid' => $licenseId);

				//return array('url' => $purchased_media->downloadURL, 'licenseid' => $purchased_media->downloadID);

    }

		function instant($imagecode) {
				if ($this->CI->input->get('type')=='video') {
					$res = $this->CI->dreamstime_api->getVideoData($imagecode);
				} else {
					$res = $this->CI->dreamstime_api->getMediaData($imagecode);
				}

				$this->instant['source'] = 'instant';
				$this->instant['license'] = 'standard';

				$item = array(
					'desc' => $res->title,
					'license' => $this->instant['license'],
					'thumb' => $res->smallThumb,
					'type' => $this->CI->input->get('type'),
					'subscription' => 0,
					'provider' => 'Dreamstime'
				);

				if ($this->CI->input->get('type')=='video') {
						$this->instant['id'] = $res->videoID;
						$this->instant['size'] = 'web';
						$this->instant['tag'] = $this->price_item_cart( array_merge($item, array(
									'id' 			=> $this->instant['id'],
									'price' 	=> $this->set_price($res->videoweb),
									'width' 	=> $res->videowebWidth,
									'height' 	=> $res->videowebHeight,
									'size' 		=> $this->instant['size'],
									'sizelbl' => 'Web'
								)));
				} else {
						$this->instant['id'] = $res->imageID;
						$this->instant['size'] = 'XS';
						$this->instant['tag'] = $this->price_item_cart( array_merge($item, array(
									'id' 			=> $this->instant['id'],
									'price' 	=> $this->set_price($res->extrasmall),
									'width' 	=> $res->extrasmallWidth,
									'height' 	=> $res->extrasmallHeight,
									'size' 		=> $this->instant['size'],
									'sizelbl' => $this->instant['size']
								)));
				}

				return $this->instant;
		}

    function convertPreview($obj) {
			//print_r($obj);
			$this->preview['type'] = $this->CI->input->get('type')=='video'?:'image';
			$this->preview['source'] = 'preview';
			$this->preview['sizes'] = '';
			$this->preview['sizes'] .= "<tr><td class='table-sizes' colspan='2'><div class='collection collection-size'>";

			if ($this->CI->input->get('type')=='video') {
				$this->preview['image'] = (string)$obj->largeThumb;
        $this->preview['id'] = (string)$obj->videoID;
				$this->preview['type'] = 'video';
        $this->preview['title'] = (string)$obj->title;
        $this->preview['keywords'] = (string)$obj->keywords;
				$this->preview['mp4'] = substr($obj->largevideoThumb,0,-3).'mp4';
				$this->preview['webm'] = substr($obj->largevideoThumb,0,-3).'webm';
				// $similar = array();
				// foreach ($obj->similar->item as $key => $value) {
				// 	$html  = "<div><img src='$value->similarMediumThumb'/><div class='caption'>";
				// 	$html .= "<a href='".base_url('main/preview')."/$value->similarImageID/?provider=Dreamstime&type=image'><i class='tiny material-icons'>zoom_in</i></a>";
				// 	$html .= "</div></div>";
				// 	$similar[] = $html;
				// }
				// $this->preview['similar'] = $similar;
				// $this->preview['similar_url'] = 1;
      	$license = 'standard';

				$item = array(
					'id' => $obj->videoID,
					'desc' => $obj->title,
					'license' => $license,
					'thumb' => $obj->smallThumb,
					'type' => $this->preview['type'],
					'subscription' => 0,
					'provider' => 'Dreamstime'
				);

      	$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
								'size' => 'web',
								'sizelbl' => 'Web',
								'width' => $obj->videowebWidth,
								'height' => $obj->videowebHeight,
								'price' => $this->set_price($obj->videoweb)
							))).$this->price_item( array_merge($item, array(
								'size' => '720',
								'sizelbl' => '720p',
								'width' => $obj->video720Width,
								'height' => $obj->video720Height,
								'price' => $this->set_price($obj->video720)
							)));

				if ($obj->video1080)
					$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
								'size' => '1080',
								'sizelbl' => '1080p',
								'width' => $obj->video1080Width,
								'height' => $obj->video1080Height,
								'price' => $this->set_price($obj->video1080)
							)));

				if ($obj->video4k)
					$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
							'size' => '4K',
							'sizelbl' => '4K',
							'width' => $obj->video4kWidth,
							'height' => $obj->video4kHeight,
							'price' => $this->set_price($obj->video4k)
						)));

			}

			else {
        $this->preview['image'] = (string)$obj->largeThumb;
        $this->preview['id'] = (string)$obj->imageID;
        $this->preview['title'] = (string)$obj->title;
        $this->preview['keywords'] = (string)$obj->keywords;
				$similar = array();
				if (isset($obj->similar->item)) {
					foreach ($obj->similar->item as $key => $value) {
						$html  = "<div><img src='$value->similarMediumThumb'/><div class='caption'>";
						$html .= "<a href='".base_url('main/preview')."/$value->similarImageID/?provider=Dreamstime&type=image'><i class='tiny material-icons'>zoom_in</i></a>";
						$html .= "</div></div>";
						$similar[] = $html;
						//$similar[] = (string)$value->similarMediumThumb;
					}
					$this->preview['similar'] = $similar;
					$this->preview['similar_url'] = 1;
				}

      	$license = 'standard';

				$item = array(
					'id' => $obj->imageID,
					'desc' => $obj->title,
					'license' => $license,
					'thumb' => $obj->smallThumb,
					'type' => 'image',
					'subscription' => 0,
					'provider' => 'Dreamstime'
				);

      	$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
								'size' => 'XS',
								'sizelbl' => 'XS',
								'width' => $obj->extrasmallWidth,
								'height' => $obj->extrasmallHeight,
								'price' => $this->set_price($obj->extrasmall)
							))).$this->price_item( array_merge($item, array(
								'size' => 'S',
								'sizelbl' => 'S',
								'width' => $obj->smallWidth,
								'height' => $obj->smallHeight,
								'price' => $this->set_price($obj->small)
							))).$this->price_item( array_merge($item, array(
								'size' => 'M',
								'sizelbl' => 'M',
								'width' => $obj->mediumWidth,
								'height' => $obj->mediumHeight,
								'price' => $this->set_price($obj->medium)
							)));

				if ($obj->large)
					$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
						'size' => 'L',
						'sizelbl' => 'L',
						'width' => $obj->largeWidth,
						'height' => $obj->largeHeight,
						'price' => $this->set_price($obj->large)
					)));

				if ($obj->extralarge)
					$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
						'size' => 'XL',
						'sizelbl' => 'XL',
						'width' => $obj->extralargeWidth,
						'height' => $obj->extralargeHeight,
						'price' => $this->set_price($obj->extralarge)
					)));

				if ($obj->maximum)
					$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
						'size' => 'MAX',
						'sizelbl' => 'MAX',
						'width' => $obj->maximumWidth,
						'height' => $obj->maximumHeight,
						'price' => $this->set_price($obj->maximum)
					)));

				if ($obj->tiff)
					$this->preview['sizes'] .= $this->price_item( array_merge($item, array(
						'size' => 'TIFF',
						'sizelbl' => 'TIFF',
						'width' => $obj->tiffWidth,
						'height' => $obj->tiffHeight,
						'price' => $this->set_price($obj->tiff)
					)));
			}
			$this->preview['sizes'] .= "</div></td></tr>";
			return $this->preview;
    }

	function convertResult($xml) {
			$obj = simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
			//print_r($obj);
      if (isset($obj)) {
				if (isset($obj->totalResults)) {
					$this->result['count'] = $obj->totalResults;
					if ($obj->partialResults > 0) {
						foreach ($obj->items->item as $value) {

							$html  = "<div><img src='$value->largeThumb' height='170' /><div class='caption'>";
							$html .= "<a class='view-link' href='".base_url('main/preview')."/$value->imageID/?provider=Dreamstime&type=$value->mediaType'><i class='material-icons'>zoom_in</i></a>";
							$html .= "<a class='cart-link' href='".base_url('main/instant')."/$value->imageID/?provider=Dreamstime&type=$value->mediaType'><i class='material-icons' style='padding-left:10px;'>add_shopping_cart</i></a>";
							//$html .= "<a href='#'><i class='material-icons' style='padding-left:10px;'>file_download</i></a>";
							$html .= "</div></div>";

							$this->result['images'][] = [
													 'provider'=> 'Dreamstime',
		                       'id'    	 => (string)$value->imageID,
		                       'title' 	 => (string)$value->title,
		                       'thumb'   => (string)$value->largeThumb,
													 'type'		 => (string)$value->mediaType,
													 'html'		 => $html

		                ];
		        }
					}
				}
      }

		return $this->result;
	}

}
