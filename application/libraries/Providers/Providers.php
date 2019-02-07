<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_DPUSER', 'latincolorimages');
define('DEF_DPPASS', 'latincol2016$');

class Providers extends CI_Driver_Library {

	private $itemsToFill;
	private $itemsPerPage;
	private $comision;
	private $iva;
	private $tco_percent;
	private $tco_additional;
	var $CI;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->valid_drivers = array('depositphoto', 'fotosearch', 'dreamstime');
		//$this->itemsPerPage = round( DEF_NUMBER_OF_ROWS / count($this->valid_drivers));
		$this->CI->load->model('membership_model');
		$system = $this->CI->membership_model->get_system();
		$this->comision = $system['price_comision']['value'];
		$this->iva = $system['iva']['value'];
		$this->tco_percent = $system['2co_percent']['value'];
		$this->tco_additional = $system['2co_additional']['value'];
	}

	function index() {
		echo "<h1>Parent driver</h1>";
	}

	function set_price($original_price) {
		return $original_price * (1+strval($this->comision));
	}

	function set_iva($valor) {
		//return ($this->CI->session->user_country=='Colombia' ? $valor * strval($this->iva) : 0);
		return $valor * strval($this->iva);
	}

	function set_tco($valor) {
		$valor = strval($valor) + $this->set_iva($valor);
		return ($valor * strval($this->tco_percent))+strval($this->tco_additional);
	}

	function price_tag($item) {

		return "<a id='savetocart' class='collection-item' href='#' data-img='".$item['id']
													 ."' data-price='".$item['price']."' data-desc='".$item['desc']
													 ."' data-height='".$item['height']."' data-width='".$item['width']
													 ."' data-license='".$item['license']."' data-size='".$item['size']
													 ."' data-thumb='".$item['thumb']."' data-iva='".number_format($this->set_iva($item['price']),2)
													 ."' data-tco='".number_format($this->set_tco($item['price']),2)
													 ."' data-sizelbl='".$item['sizelbl']."' data-provider='".$item['provider']
													 ."' data-tranType='compra_img'>";
	}

	function price_item_cart($item) {
		return $this->price_tag($item)."</a>";
	}

	function price_item($item) {
		// return "<tr><td><span class='size'>".strtoupper($item['size'])."</span></td><td>"
		// 				.($item['height']=='' ? $item['width'] : $item['width']."x".$item['height'])
		// 				."</td><td>".number_format($item['price'],2)."</td><td>"
		// 				.$this->price_tag($item)
		// 				."<i class='material-icons'>add_shopping_cart</i></a></td></tr>";
		//return $this->price_tag($item) .$item['width']. 'x' .$item['height']. '</a><br/><span class="size-name">'.strtoupper($item['size']).'</span></div>';
		//return $this->price_tag($item) .strtoupper($item['size']). '</a><br/><span class="size-name">'.$item['width']. 'x' .$item['height'].'</span></li>';
		$row = "<div class='row valign-wrapper'><div class='size-option col s4 blue-text text-darken-4'><div class='center'>".$item['sizelbl']."</div></div><div class='col s4 center-align'>".$item['width']."x".$item['height']."</div><div class='col s4 right-align'>US$ ".$item['price']."</div></div>";
		return $this->price_tag($item) . $row . '</a>';
	}

	function createSubscription($provider='', $subscriptionId='', $subaccountId='')
	{
		if ($provider == 'Depositphoto') {
			//$this->depositphoto->subaccounts('createSubscription', $subaccountId, $subscriptionId);
		}
		return array('suscriptionId'=>$subscriptionId, 'subaccountId'=>$subaccountId, 'provider'=>$provider);
	}

	function createSubaccount($provider, $userinfo) 
	{
		if ($provider == 'Depositphoto') {
			$res = $this->depositphoto->createSubaccount($userinfo);
		}
		return $res;
	}

	// function itemsPerPage($rows) {
	// 	return round( $rows / count($this->valid_drivers));
	// }

	// function calc_remain($value) {
	// 	return ($value = DEF_NUMBER_OF_ROWS ?
	// 									 DEF_NUMBER_OF_ROWS - $this->itemsPerPage :
	// 									 $this->itemsPerPage - $value );
	// }
	//
	function have_items($value) {
		return ($value >= $this->itemsPerPage ? $value : 0 );
  }
	//
	// function need_items($value) {
	// 	return ($value < $this->itemsPerPage ? $this->itemsPerPage - $value : 0 );
	// }
	//
	// function dist_items($have) {
	// 	return ($this->itemsPerPage + $this->itemsToFill);
	// }
	//
	// function unfilled($value) {
	// 	return ($value < $this->itemsPerPage ? $value : 0);
	// }
	//
	// function new_images($arr, $item_count){
	// 	return array_slice($arr, 0, $item_count);
	// }

	function search($query) {

		// $query['limit'] = $query['rows'];
		// $page = ($query['page']==1?0:$query['page']-1);
		// $query['offset'] = $page * $query['limit'];

		// $this->itemsPerPage = round( $query['rows'] / count($this->valid_drivers));
		// $query['limit'] = $this->itemsPerPage + $query['np'];
		// $page = ($query['page']==1?0:$query['page']-1);
		// $query['offset'] = ($this->itemsPerPage + $query['np']) * $page;

		//$this->CI->session->set_userdata('icount', array('dp'=>0,'dt'=>0,'fs'=>0));

		$query['prov'] = $this->resolve($query['page'], $query['rows']);

		$url['fs'] = $this->fotosearch->search($query);
		$url['dp'] = $this->depositphoto->search($query);
		$url['dt'] = $this->dreamstime->search($query);

		foreach ($url as $key => $value) {
			$ch[$key] = curl_init();
			curl_setopt_array($ch[$key], array(
					CURLOPT_RETURNTRANSFER  => true,
					CURLOPT_URL             => $value
			));
		};

		$mh = curl_multi_init();
		foreach ($ch as $key => $value) {
			curl_multi_add_handle($mh, $value);
		};

		$running = null;
		do {
			curl_multi_exec($mh, $running);
			curl_multi_select($mh);
		} while ($running > 0);

		foreach ($ch as $key => $value) {
			curl_multi_remove_handle($mh, $value);
		};

		curl_multi_close($mh);

		$fs = $this->fotosearch->convertResult(json_decode(curl_multi_getcontent($ch['fs'])));
		$dp = $this->depositphoto->convertResult(json_decode(curl_multi_getcontent($ch['dp'])));
		$dt = $this->dreamstime->convertResult(curl_multi_getcontent($ch['dt']));

		$resCount = array('dp'=>(int)$dp['count'], 'dt'=>(int)$dt['count'], 'fs'=>(int)$fs['count']);
		$resImages = array('dp'=>$dp['images'], 'dt'=>$dt['images'], 'fs'=>$fs['images']);
		$resRemain = array('dp'=>count($dp['images']), 'dt'=>count($dt['images']), 'fs'=>count($fs['images']));
		if ($query['page'] == 1) {
			$resRemain = $this->cutres($resRemain, $query['rows']);
			$resImages = $this->cutimg($resRemain, $resImages);
		}
		//$this->session->set_userdata('last_remain', $resRemain);
		$this->CI->session->set_userdata('icount', $resCount );

		$all['total'] = array_sum($resCount);
		$all['count'] = array_sum($resRemain);
		$all['count_prov'] = $resCount;

		$percent = array();
		foreach ($resCount as $key => $value) {
			if ($all['total'] == 0) {
				$percent[] = 0;
			} else {
				$percent[] = round($value / $all['total'] * 100);
			}
		}

		$all['totalProv'] = array('names' => array_keys($resCount),
	 														'count' => $percent);
//$need_items = ($query['np']>0?$query['np']:$query['rows']) - $all['count'];
		//$have_items = array_filter($resRemain, array($this, 'have_items'));
		//$this->CI->session->set_userdata('have_items', $have_items );

		//$all['items_need'] = ( $need_items > 0 && count($have_items) > 0 ? round($need_items/count($have_items)) : 0) ;
		//$resRemain = array_map(array($this,'calc_remain'), $resCount);
		//--$need_items = array_filter($resRemain, array($this, 'need_items'));
		//--$this->itemsToFill = ceil(($this->itemsPerPage - array_sum($need_items)) / count($have_items));
		//--$dist_items = array_map(array($this, 'dist_items'), $have_items);
		//$unfilled = array_filter($resRemain, array($this, 'unfilled'));
		//--$new_count = array_merge($dist_items, $need_items);
		//$this->session->set_userdata('last_track', ksort($new_count));
		//--ksort($new_count);
		//--$new_images = array_map(array($this, 'new_images'), $resImages, $new_count);

		// print_r($resCount);
		// print_r($resRemain);
		// print_r($have_items);
		// print_r($need_items);
		// print_r($dist_items);
		// print_r($unfilled);
		// print_r($new_count);
		// print_r($new_images);

		//$all['images'] = array_merge_recursive($fs['images'], $dp['images'], $dt['images']);
		$all['images'] = array_merge_recursive($resImages['dp'], $resImages['dt'], $resImages['fs']);
		//$all['images'] = array_merge($new_images[0],$new_images[1],$new_images[2]);
		//print_r($all['images']);

		return $all;
	}

	function resolve($page, $rows) {
		if ($page == 1) {
			//$rows_per_prov = round($rows/count($this->valid_drivers));
			$init = array('limit'=>$rows,'offset'=>0);
			$show_page = array('dp'=> $init,'dt'=> $init,'fs'=> $init);
			$total_found = $show_page;
			//$total_start = array('dp'=> $rows,'dt'=> $rows,'fs'=> $rows);
		}	else {
			//$total_start = array('dp'=>70,'dt'=>30,'fs'=>100);
			//print_r($total_start);
			$total_start = $this->CI->session->icount;
			$total_found = $total_start;
			asort($total_found);
			$num_prov = count($total_found);
			$per_page = $rows;
			$show_page = array(
				'dp'=> array('limit'=>0,'offset'=>0),
				'dt'=> array('limit'=>0,'offset'=>0),
				'fs'=> array('limit'=>0,'offset'=>0)
			);
			$need = 0;
			for ($i=1; $i<=$page ;$i++) {
				$per_prov = ($num_prov > 0 ? round($per_page / $num_prov) : 0);
				foreach ($total_found as $key => $value) {
					// echo "<br>page:$i<br/>";
					// echo "num_prov:$num_prov<br/>";
					// echo "per_prov:$per_prov<br/>";
					// echo "$key:$value<br/>";
					if ($value === NULL) {
						$need = 0;
						$show_page[$key]['limit'] = 0;
						$show_page[$key]['offset'] = 0;
					} else {
						if ($value <= $per_prov) {
							if ($value==0) {
								$show_page[$key]['limit'] = 0;
								$show_page[$key]['offset'] = 0;
								$need = 0;
								$num_prov--;
								$per_prov = ($num_prov==0?0:round($per_page / $num_prov));
								$total_found[$key] = NULL;
							} else {
								$show_page[$key]['limit'] = $value;
								$show_page[$key]['offset'] = $total_start[$key] - $value;
								$total_found[$key] = 0;
								$need += $per_prov - $value;
								$num_prov--;
								$total_found[$key] = NULL;
							}
						} else {
							$show_page[$key]['limit'] = $per_prov + ceil($need/$num_prov);
							$show_page[$key]['offset'] = $total_start[$key] - $value;
							$total_found[$key] = $value - $show_page[$key]['limit'];
							//echo "limit: $per_prov"."+need/num_prov(".ceil($need/$num_prov).")=".$show_page[$key]['limit']."<br>";
						}
					}
					// print_r($show_page);
					// echo "need:$need<br/>";
				}
			}
		}
		//print_r($this->cutres($total_found, $rows));
		return $show_page;
	}

	function cutres($source, $rows) {
		asort($source);
		$need = 0;
		$num_source = count($source);
		$count = round($rows / $num_source);
		foreach ($source as $key => $value) {
			if ($value < $count) {
				$fixed[$key] = $value;
				$need += $count - $value;
				$num_source--;
			} else {
				$fixed[$key] = $count + ceil($need/$num_source);
			}
		}
		return $fixed;
	}

	function cutimg($limit, $source) {
		foreach ($source as $key => $value) {
			$source_cut[$key] = array_slice($value,0,$limit[$key]);
		}
		return $source_cut;
	}
}
