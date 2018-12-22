<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Providers_depositphoto extends CI_Driver {

	public $result = array( 'count' => 0, 'images' => array() );
	public $preview = array();
	public $instant = array();
	//private $comision;
	var $CI;

	function __construct() {
				$this->CI =& get_instance();
    		$sessionid = array('sessionid' => $this->CI->input->get('sessionid'));
        $this->CI->load->library('depositclient', $sessionid);
				//$this->CI->load->model('membership_model');
				//$this->comision = $this->CI->membership_model->get_system('price_comision');
	}

	function search($query) {
        $params = array(
              RpcParams::SEARCH_QUERY  => $query['keyword'],
              RpcParams::SEARCH_LIMIT  => $query['prov']['dp']['limit'],
              RpcParams::SEARCH_OFFSET => $query['prov']['dp']['offset'],
							RpcParams::SEARCH_VECTOR => 'false'
            	//RpcParams::SEARCH_USERNAME => 'Kama'
            );
				if ($query['orientacion']!='') {
					$params[RpcParams::SEARCH_ORIENTATION] = ($query['orientacion']=='Cuadrada') ?
																										'square' : strtolower($query['orientacion']);
				}
				if ($query['color']!='') {
					$params[RpcParams::SEARCH_COLOR]  = '17';
				}
				switch ($query['medio']) {
					case 'Vectores':
						$params[RpcParams::SEARCH_VECTOR] = 'true';
						$params[RpcParams::SEARCH_PHOTO] = 'false';
						break;
					case 'Videos':
						$params[RpcParams::SEARCH_VIDEO] = 'true';
						$params[RpcParams::SEARCH_PHOTO] = 'false';
						break;
				}

        $res = $this->CI->depositclient->search($params);
        //return $this->convertResult($res);
				return $res;
	}

	function preview($imagecode) {
        //$data['back'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //$_SESSION['mysession'] = $data['back'];

        $res = $this->CI->depositclient->getMediaData($imagecode);
				//print_r($res);
				if ($res->type == 'success')
					$ret = $this->convertPreview($res);
				else
					$ret = $this->preview;

				return $ret;
    }

	function similar($imagecode) {
				$res = $this->CI->depositclient->getRelated($imagecode);
				//header('Content-Type: application/json');
				//echo json_encode($res);
				$similar = array();
				foreach ($res->items as $key => $value) {
					$html  = "<div><img src='$value->thumbnail'/><div class='caption'>";
					$html .= "<a href='".base_url('main/preview/')."/$value->id/?provider=Depositphoto'><i class='tiny material-icons'>zoom_in</i></a>";
					$html .= "</div></div>";
					$similar[] = $html;
					//$similar[] = $value->thumbnail;
				}
				return $similar;
	}

    function download() {
        $media = $this->CI->input->post('id');
        $size = $this->CI->input->post('size');
				$price = $this->CI->input->post('price');
        $license = $this->CI->input->post('license');
        $username = $this->CI->input->post('username');
				$img_url = $this->CI->input->post('thumb');

        $subaccountid = "";
        $subaccountid = $this->CI->membership_model->other_member_id($username);

        $login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
        $this->CI->depositclient->setSessionId($login->sessionid);

        $purchased_media = $this->CI->depositclient->getMedia($media, $license, $size, $subaccountid);
				//if ( $query = $this->CI->membership_model->record_transaction($username, 'download', $media) )
				//{
					$rec = array(
						'username' => $username,
						'date' => date("Y-m-d H:i:s"),
						'img_code' => $media,
						'img_provider' => 'Depositphoto',
						'img_url' => $img_url,
						'img_price' => $price,
						'price_type' => 'credits',
						'resolution' => '',
						'size' => $size,
						'img_dimension' => '',
						'img_pixels' => '',
						'license_type' => $license,
						'license_id' => $purchased_media->licenseId
					);
					$this->CI->membership_model->record_download($rec);
				//}
				//$this->CI->membership_model->set_license_id($purchased_media->licenseId);
				$logout = $this->CI->depositclient->logout();
				//return array('url'=>$login->sessionid, 'licenseid'=>$logout->type);
        return array('url' => $purchased_media->downloadLink, 'licenseid' => $purchased_media->licenseId);

    }

		function instant($imagecode) {
				$res = $this->CI->depositclient->getMediaData($imagecode);
				//print_r($res);
				if ($res->type == 'success') {
					if ($res->itype == 'video') {
						$this->instant['size'] = 'smallweb';
						$this->instant['price'] = $res->sizes->smallweb->credits;
					} else {
						$this->instant['size'] = 's';
						$this->instant['price'] = $res->sizes->s->credits;
					}
					$this->instant['source'] = 'instant';
					$this->instant['license'] = 'standard';
        	$this->instant['tag'] = $this->price_item_cart( array(
								'id' 			=> $res->id,
								'price' 	=> $this->set_price($this->instant['price']),
								'width'		=> $res->width,
								'height'	=> $res->height,
								'size' 		=> $this->instant['size'],
								'sizelbl' => strtoupper($this->instant['size']),
								'desc' 		=> $res->title,
								'license' => $this->instant['license'],
								'thumb' 	=> $res->thumb,
								'provider'=> 'Depositphoto'
							));
				}

				return $this->instant;
		}

    function convertPreview($obj) {
			//print_r($obj);
        $this->preview['image'] = $obj->url_big;
				$this->preview['type'] = $obj->itype;
				$this->preview['source'] = 'preview';
				if ( $this->preview['type'] == "video" ) {
					$this->preview['mp4'] = (string)$obj->mp4;
					$this->preview['webm'] = isset($obj->webm) ? (string)$obj->webm : ""; //(string)$obj->webm;
				}
        $this->preview['id'] = $obj->id;
        $this->preview['title'] = $obj->title;
        $this->preview['keywords'] = implode(", ", $obj->tags);
				$this->preview['similar'] = $obj->similar;
				$this->preview['similar_url'] = 0;
        $this->preview['sizes'] = '';
				$this->preview['sizes'] .= "<tr><td class='table-sizes'><div class='collection collection-size'>";

        foreach ($obj->sizes as $key => $s) {
        	$license = ($key=='el0' ? 'extended' : 'standard');
        	$this->preview['sizes'] .= $this->price_item( array(
						'id' 			=> $obj->id,
						'price' 	=> $this->set_price($s->credits),
						'width'		=> $s->width,
						'height'	=> $s->height,
						'size' 		=> $key,
						'sizelbl' => strtoupper($key),
						'desc' 		=> $obj->title,
						'license' => $license,
						'thumb' 	=> $obj->thumb,
						'provider'=> 'Depositphoto'
					));
        }
				//$this->preview['sizes'] .= "</ul></td></tr><tr><td class='size-info'></td></tr>";
				$this->preview['sizes'] .= "</div></td></tr>";

      return $this->preview;
    }

	function convertResult($obj) {
      if ($obj) {
				$this->result['provider'] = 'Depositphoto';
				//$this->source = $obj->result;
				$this->result['count'] = $obj->count;
				foreach ($obj->result as $value) {

					$html  = "<div><img src='$value->url2' height='170'/><div class='caption'>";
					$html .= "<a class='view-link' href='".base_url('main/preview')."/$value->id/?provider=Depositphoto'><i class='material-icons'>zoom_in</i></a>";
					$html .= "<a class='cart-link' href='".base_url('main/instant')."/$value->id/?provider=Depositphoto'><i class='material-icons' style='padding-left:10px;'>add_shopping_cart</i></a>";
					$html .= "<a href='#'><i class='material-icons' style='padding-left:10px;'>file_download</i></a>";
					$html .= "</div></div>";

					$this->result['images'][] = [
											 'provider'=> 'Depositphoto',
                       'id'      => (string)$value->id,
                       'title'   => (string)$value->title,
                       'thumb'   => (string)$value->url2,
											 'type'		 => (string)$value->type,
											 'html'		 => $html
                ];
        }
      }

		return $this->result;

	}

	function reDownload($license_id='', $subaccountId='')
	{
		$login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
		$this->CI->depositclient->setSessionId($login->sessionid);
		$res = $this->CI->depositclient->reDownload($license_id, $subaccountId);
		$logout = $this->CI->depositclient->logout();

		return $res;
	}

	function getSubscriptionOffers() {
		$res = $this->CI->depositclient->getSubscriptionOffers();
		//$this->CI->membership_model->update_planDP($res);

		return $res;
	}

	function subaccounts($method='', $subaccountId='', $subscriptionId='')
	{
		$login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
		$this->CI->depositclient->setSessionId($login->sessionid);
		switch ($method) {
			case 'data':
				$res = $this->CI->depositclient->getSubaccountData($subaccountId);
				break;
			case 'createSubscription':
				$res = $this->CI->depositclient->createSubaccountSubscription($subaccountId, $subscriptionId);
				break;
			default:
				$res = $this->CI->depositclient->getSubaccounts();
		}
		$logout = $this->CI->depositclient->logout();

		return $res;
	}

	// function getSubaccounts()
	// {
	// 	$login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
	// 	$this->CI->depositclient->setSessionId($login->sessionid);
	// 	$res = $this->CI->depositclient->getSubaccounts();
	// 	$logout = $this->CI->depositclient->logout();
	//
	// 	return $res;
	// }
	//
	// function getSubaccountData($subaccountId)
	// {
	// 	$login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
	// 	$this->CI->depositclient->setSessionId($login->sessionid);
	// 	$res = $this->CI->depositclient->getSubaccountData($subaccountId);
	// 	$logout = $this->CI->depositclient->logout();
	//
	// 	return $res;
	// }
	//
	// function createSubaccountSubscription($subaccountId)
	// {
	// 	$login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
	// 	$this->CI->depositclient->setSessionId($login->sessionid);
	// 	$res = $this->CI->depositclient->createSubaccountSubscription($subaccountId);
	// 	$logout = $this->CI->depositclient->logout();
	//
	// 	return $res;
	//
	// }

}
