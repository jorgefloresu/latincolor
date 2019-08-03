<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Providers_adobestock extends CI_Driver {

	public $result = array( 'count' => 0, 'images' => array() );
	public $preview = array();
	public $instant = array();
	var $CI;

	function __construct() {
		$this->CI =& get_instance();
        $this->CI->load->library('adobestock_api');
	}

	function search($query) {
        $offset = $query['prov']['as']['limit'] * ($query['page'] - 1);
    	$params = array(
                AdobeStockParams::SEARCH_QUERY  => $query['keyword'],
                AdobeStockParams::SEARCH_LIMIT  => $query['prov']['as']['limit'],
                AdobeStockParams::SEARCH_OFFSET => $offset
            );
        if ($query['orientacion']!='') {
            $params[AdobeStockParams::SEARCH_ORIENTATION] = ($query['orientacion']=='Cuadrada') ?
                                                        'square' : strtolower($query['orientacion']);
        }
        if ($query['color']!='') {
            $params[AdobeStockParams::SEARCH_COLOR]  = '000000,ffffff';
        }
        switch ($query['medio']){
                case 'Videos':
                    $params[AdobeStockParams::TYPE_VIDEO] = 1;
                    break;
                case 'Vectores':
                    $params[AdobeStockParams::TYPE_VECTOR] = 1;
                    break;
                default:
                    $params[AdobeStockParams::TYPE_PHOTO] = 1;
            }

        $res = $this->CI->adobestock_api->search($params);
		return $res;
	}

	function preview($imagecode) {
        $res = $this->CI->adobestock_api->getMediaData($imagecode);
	    $ret = $this->convertPreview($res);
		return $ret;
    }

	function similar($imagecode) {
				$res = $this->CI->adobestock_api->getRelated($imagecode);
				//header('Content-Type: application/json');
				//echo json_encode($res);
				$similar = array();
				foreach ($res->items as $key => $value) {
					$html  = "<div><img src='$value->thumbnail'/><div class='caption'>";
					$html .= "<a href='".base_url('main/preview/')."/$value->id/?provider=Adobestock'><i class='tiny material-icons'>zoom_in</i></a>";
					$html .= "</div></div>";
					$similar[] = $html;
					//$similar[] = $value->thumbnail;
				}
				return $similar;
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

        $subaccountid = "";
        $subaccountid = $this->CI->membership_model->other_member_id($username);

        $login = $this->CI->depositclient->login(DEF_DPUSER, DEF_DPPASS);
        $this->CI->adobestock_api->setSessionId($login->sessionid);

        $purchased_media = $this->CI->adobestock_api->getMedia($media, $license, $size, $subaccountid, $item['subscriptionId']);
				//if ( $query = $this->CI->membership_model->record_transaction($username, 'download', $media) )
				//{
					$rec = array(
						'username' => $username,
						'date' => date("Y-m-d H:i:s"),
						'img_code' => $media,
						'img_provider' => 'Adobestock',
						'img_url' => $img_url,
						'img_price' => $price,
						'price_type' => 'credits',
						'type' => $item['type'],
						'resolution' => '',
						'size' => $size,
						'img_dimension' => '',
						'img_pixels' => '',
						'license_type' => $license,
						'license_id' => $purchased_media->licenseId,
						'subscription_id' => $item['subscriptionId']
					);
					$this->CI->membership_model->record_download($rec);
				//}
				//$this->CI->membership_model->set_license_id($purchased_media->licenseId);
				$logout = $this->CI->adobestock_api->logout();
				//return array('url'=>$login->sessionid, 'licenseid'=>$logout->type);
        return array('url' => $purchased_media->downloadLink, 'licenseid' => $purchased_media->licenseId);

    }

		function instant($imagecode) {
				$res = $this->CI->adobestock_api->getMediaData($imagecode);
				//print_r($res);
				if ($res->type == 'success') {
					if ($res->itype == 'video') {
						$this->instant['size'] = 'smallweb';
						$this->instant['price'] = $res->sizes->smallweb->credits;
						$this->instant['subscription'] = $res->sizes->smallweb->subscription;
					} else {
						$this->instant['size'] = 's';
						$this->instant['price'] = $res->sizes->s->credits;
						$this->instant['subscription'] = $res->sizes->s->subscription;
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
								'type' 		=> $res->itype,
								'subscription'=> $this->instant['subscription'],
								'provider'=> 'Adobestock'
							));
				}

				return $this->instant;
		}

    function convertPreview($obj) {
        $this->preview['sizes'] = '';
		$this->preview['sizes'] .= "<tr><td class='table-sizes' colspan='2' style='min-height:335px'><div class='valign-wrapper'>";
		$this->preview['source'] = 'preview';
        foreach ($obj->files as $key => $s) {
            $this->preview['image'] = (string)$s->thumbnail_1000_url;
            $this->preview['id'] = (string)$s->id;
            $this->preview['title'] = $s->title;
            $array_keywords = array();
            foreach ($s->keywords as $keyword) {
                $array_keywords[] = $keyword->name;
            }
            $this->preview['keywords'] = implode(', ', $array_keywords);
            $license = 'standard';
            $this->preview['sizes'] .= '<p class="center-align" style="margin:0 auto; padding: 20px 50px 0 50px;"><img src="'.base_url('img/Adobe.png').'"><br>Para adquirir esta imagen debes comprar un plan de Adobe Stock</p>';
        }
		$this->preview['sizes'] .= "</div><div class='collection collection-size'>";
		$planes = $this->CI->membership_model->get_planes(null, 'Adobe')->result();
		foreach ($planes as $plan) {
			$this->preview['sizes'] .= $this->price_item( array(
				'id' 			=> $plan->id,
				'price' 	=> $this->set_price($plan->valor),
				'width'		=> '',
				'height'	=> '',
				'size' 		=> "$plan->cantidad $plan->medio X $plan->tiempo meses",
				'sizelbl' => $plan->cantidad,
				'desc' 		=> "$plan->cantidad $plan->medio para descarga ".strtolower($plan->frecuencia). " durante $plan->tiempo meses",
				'license' => 'standard',
				'thumb' 	=> base_url('img/Adobe.png'),
				'type' 		=> 'plan',
				'subscription'=>'',
				'provider'=> 'Adobe'
			));
			
		}
		$this->preview['sizes'] .= '</div></td></tr>';

        return $this->preview;
    }

	function convertResult($obj) {
      if ($obj) {
                $obj = json_decode($obj);
				$this->result['provider'] = 'Adobestock';
				//$this->source = $obj->result;
				$this->result['count'] = (int)$obj->nb_results;
				foreach ($obj->files as $value) {

					$html  = "<div><img src='$value->thumbnail_url' height='170'/><div class='caption'>";
					$html .= "<a class='view-link' href='".base_url('main/preview')."/$value->id/?provider=Adobestock'><i class='material-icons'>zoom_in</i></a>";
					$html .= "<a class='cart-link' href='".base_url('main/instant')."/$value->id/?provider=Adobestock'><i class='material-icons' style='padding-left:10px;'>add_shopping_cart</i></a>";
					//$html .= "<a href='#'><i class='material-icons' style='padding-left:10px;'>file_download</i></a>";
                    $html .= "</div></div>";

                    $type = ((string)$value->media_type_id==4) ? 'video' :
                    ((string)$value->media_type_id==1) ? 'image' : 'vector';


					$this->result['images'][] = [
					    'provider'=> 'Adobestock',
                        'id'      => (string)$value->id,
                        'title'   => (string)$value->title,
                        'thumb'   => (string)$value->thumbnail_url,
						'type'	  => $type,
						'html'	  => $html
                ];
        }
      }

		return $this->result;

	}

	function reDownload($license_id='', $subaccountId='')
	{
		$login = $this->CI->adobestock_api->login(DEF_DPUSER, DEF_DPPASS);
		$this->CI->adobestock_api->setSessionId($login->sessionid);
		$res = $this->CI->adobestock_api->reDownload($license_id, $subaccountId);
		$logout = $this->CI->adobestock_api->logout();

		return $res;
	}

	function getSubscriptionOffers() {
		$res = $this->CI->adobestock_api->getSubscriptionOffers();
		//$this->CI->membership_model->update_planDP($res);

		return $res;
	}

	function subaccounts($method='', $subaccountId='', $subscriptionId='', $date_format=null)
	{
		$login = $this->CI->adobestock_api->login(DEF_DPUSER, DEF_DPPASS);
		$this->CI->adobestock_api->setSessionId($login->sessionid);
		switch ($method) {
			case 'data':
				$res = $this->CI->adobestock_api->getSubaccountData($subaccountId, $date_format);
				break;
			case 'createSubscription':
				$res = $this->CI->adobestock_api->createSubaccountSubscription($subaccountId, $subscriptionId);
				break;
			default:
				$res = $this->CI->adobestock_api->getSubaccounts();
		}
		$logout = $this->CI->adobestock_api->logout();

		return $res;
	}

	function createSubaccount($userinfo)
	{
		$login = $this->CI->adobestock_api->login(DEF_DPUSER, DEF_DPPASS);
		$this->CI->adobestock_api->setSessionId($login->sessionid);

		$res = $this->CI->adobestock_api->createSubaccount($userinfo->email_address, 
														$userinfo->first_name, 
														$userinfo->last_name);

		$logout = $this->CI->adobestock_api->logout();

		$subaccountId = '';
		if  ( ! is_object($res) ) {
			if( isset($res['error']) ) {
				log_message('error', '['.__CLASS__.':'.__LINE__.'] ' . $res['error']['msg']);
				$subaccountId = 'Ya existe login';
			} 
		} elseif ( isset($res->type)) {
			if ($res->type=='success' )
				$subaccountId = $res->subaccountId;
		}
		return $subaccountId;
		/*print_r($res);
		echo $userinfo->email_address . $userinfo->first_name . $userinfo->last_name;
		echo "Subaccount: $subaccountId";*/
	}

	function deleteSubaccount($subaccountId)
	{
		$login = $this->CI->adobestock_api->login(DEF_DPUSER, DEF_DPPASS);
		$this->CI->adobestock_api->setSessionId($login->sessionid);

		$res = $this->CI->adobestock_api->deleteSubaccount($subaccountId);

		$logout = $this->CI->adobestock_api->logout();

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
