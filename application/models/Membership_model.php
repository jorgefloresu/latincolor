<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership_model extends CI_Model {

	public function __construct()
	{
			$this->load->database();
	}

	public function validate()
	{
		if ($this->input->post('external'))
			$condition['username'] = $this->input->post('email_address');
		else
			$condition = array(
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password'))
			);

		$query = $this->db->get_where('membership', $condition);

		if($query->num_rows() == 1)
		{
			return $query;
		}
		else
		{
			if ($this->input->post('external'))
			{
				$new_member_insert_data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'email_address' => $this->input->post('email_address'),
					'username' => $this->input->post('username'),
				);
				$insert = $this->db->insert('membership', $new_member_insert_data);
				return $insert;
			}
			else
			{
				//echo "user ".$this->input->post('username')." with pass ".$this->input->post('password')." not found";
				//print_r ($query->row_array());
				//die();
				return false;
			}
		}
	}

	function create_member()
	{

		$new_member_insert_data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email_address' => $this->input->post('email_address'),
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'country' => $this->input->post('country')
		);

		$insert = $this->db->insert('membership', $new_member_insert_data);
		return $insert;
	}

	function update_member($username, $prefs)
	{
		if (array_key_exists('password', $prefs))
			$prefs['password'] = md5($prefs['password']);
		$this->db->where('username', $username);
		$query = $this->db->update('membership', $prefs);
		return $query;
	}

	function remove_subaccount($subaccountId)
	{
		$this->db->where('deposit_userid', $subaccountId);
		$query = $this->db->update('membership', ['deposit_userid'=>'']);
		return $query;
	}

	function user_exists($username)
	{
		$query = $this->db->get_where('membership', array('username'=>$username));
		return ($query->num_rows() >= 1);
	}


	function email_exists($email)
	{
		$condition = array(
			'email_address' => $email
		);
		$query = $this->db->get_where('membership', $condition);
		$resp = $query->row();
		return ($query->num_rows() >= 1 ? $resp->username : '');
		//return ($query->num_rows() >= 1);
	}

	function other_member_id($username)
	{
		$query = $this->db->query("SELECT deposit_userid FROM membership WHERE username='".$username."'");
		$row = $query->row();
		return $row->deposit_userid;
	}

	function record_transaction($username, $activity_type, $charge)
	{
		$items = json_decode($this->input->post('items'), TRUE);
        $all_items = array_merge($items['images'], $items['planes']);

		$this->record_payment($username, $charge);

		foreach ($all_items as $key => $item) {
			$new_transaction_data[] = array(
				'username' => $username,
				'session_date' => date("Y-m-d H:i:s"),
				'activity_type' => $item['tranType'],
				'img_code' => $item['productId']
			);
		}

		$this->db->insert_batch('activities', $new_transaction_data);

		$total['images'] = 0;
		$total['planes'] = 0;
		$data_insert = [];
		$new_ventas_data = [];

		foreach ($items as $key => $item) {
			foreach ($item as $value) {
				$total[$key] += $value['price'] + $value['iva'] + $value['tco'];
				$orderType = ($key == 'images' ? 1 : 2);
				//$orderId = floatval("{$charge['response']['merchantOrderId']}{$orderType}");
				$orderId = floatval("{$charge['merchantOrderId']}{$orderType}");
				$value['orderId'] = $orderId;
				unset($value['tranType']); unset($value['username']); 
				unset($value['idplan']); unset($value['subscriptionId']);
				array_push($data_insert, $value);
			}
			if ($total[$key] > 0) {
				$new_ventas_data[] = array(
					'orderId' => $orderId,
					'activity_type' => $activity_type,
					'username' => $username,
					'monto' => $total[$key],
					'fecha' => date("Y-m-d H:i:s"),
					'status' => ($activity_type=='compra_plan' ? 'ord' : 'new')
				);
	
			}
		}
		$this->db->insert_batch('ventas', $new_ventas_data);
		$insert = $this->db->insert_batch('ventas_detalle', $data_insert);

		return $insert;
	}

	function record_download($new_download_data)
	{

		$insert = $this->db->insert('downloads', $new_download_data);
		return $this->db->insert_id();
		//return $insert;
	}

	function set_license_id($license_id){
		$license_id = array(
			'license_id' => $this->input->post('licenseid')
			);

		$this->db->where('id', $this->input->post('recId'));
		$query = $this->db->update('downloads', $license_id);
		return $query;
	}

	function record_payment($username, $charge)
	{
		// Twocheckout
		/* $new_payment_data = array(
			'username' => $username,
			'order_number' => $charge['response']['orderNumber'],
			'date' => date("Y-m-d H:i:s"),
			'merchant_order_id' => $charge['response']['merchantOrderId'],
			'token' => $charge['response']['transactionId'],
			'total' => $this->input->post('totalId'),
			'img_code' => ''
			); */

		// PayU
		$new_payment_data = array(
			'username' => $username,
			'order_number' => $charge['orderId'],
			'date' => date("Y-m-d H:i:s"),
			'merchant_order_id' => $charge['merchantOrderId'],
			'token' => $charge['transactionId'],
			'total' => $this->input->post('totalId'),
			'img_code' => ''
			);

		$insert = $this->db->insert('payments', $new_payment_data);
		return $insert;

	}

	function set_download_ref($id, $downloadRef, $orderId)
	{
		$upd_download_data = array(
			'download_ref' => $downloadRef,
			'order_id' => $orderId
			);
		$this->db->where('id', $id);
		$query = $this->db->update('downloads', $upd_download_data);
		return $query;
	}

	function save_cart()
	{
		$new_cart = array(
			'cart_id' => $this->input->post('cartid'),
			'username' => $this->input->post('username'),
			'creation_date' => date("Y-m-d H:i:s")
			);
		$insert = $this->db->insert('cart', $new_cart);
		return $insert;

	}

	function save_cart_details()
	{
		$new_cart_details = array(
			'cart_id' => $this->input->post('cartid'),
			'img_code' => $this->input->post('img_code'),
			'img_provider' => $this->input->post('img_provider'),
			'img_url' => $this->input->post('img_url'),
			'img_price' => $this->input->post('img_price'),
			'price_type' => $this->input->post('price_type'),
			'resolution' => $this->input->post('resolution'),
			'size' => $this->input->post('size'),
			'img_dimension' => $this->input->post('img_dimension'),
			'img_pixels' => $this->input->post('img_pixels'),
			'download_url' => $this->input->post('download_url')
		);

		$insert = $this->db->insert('cart_details', $new_cart_details);
		return $this->db->insert_id();

	}

	function cart_found()
	{
		$cart_name = array('username' => $this->input->post('username'));
		$query = $this->db->get_where('cart', $cart_name);
		if ($query->num_rows() > 0)
			return true;
		else
			return false;
	}

	function get_cart()
	{
		$cart_name = array('cart_id' => $this->input->get('username'));
		$query = $this->db->get_where('cart_details', $cart_name);
		return $query;
	}

	function count_cart_items($cart_id)
	{
		$query = $this->db->query("SELECT count(*) as conteo FROM cart_details WHERE cart_id = '".$cart_id."'");
		if ($query)
		{
			$row = $query->row();
			return $row->conteo;
		}
		else
			return 0;
	}

	function delete_cart_item()
	{
		$query = $this->db->delete('cart_details', array('id' => $this->input->post('id')));
		return $query;
	}

	function get_activities($username, $activity_type, $img_code)
	{
		$cart_activities = array(
			'username' => $username,
			'activity_type' => $activity_type,
			'img_code' => $img_code
		);
		$query = $this->db->get_where('activities', $cart_activities);
		return $query;
	}

	function all_activities($username=null, $activity_type=null, $img_code=null)
	{
		if (null != $username)
			$this->db->where('username', $username);
		if (null != $activity_type)
			$this->db->where('activity_type', $activity_type);
		if (null != $img_code)
			$this->db->where('img_code', $img_code);

		$this->db->order_by('session_date', 'DESC');

		$query = $this->db->get('activities');
		return $query;
	}

	function get_downloads($username=null, $provider=null, $img_code=null)
	{
		$this->db->select("id, username, date, img_code, img_provider,
							img_url, img_price, price_type, type, resolution, size, img_dimension, 
							img_pixels, download_ref, order_id, license_type, license_id, subscription_id");
		$this->db->from('downloads');
		
		if (null != $username)
			$this->db->where('username', $username);
		if (null != $provider)
			$this->db->where('img_provider', $provider);
		if (null != $img_code)
			$this->db->where('img_code', $img_code);

		$this->db->order_by('date', 'DESC');

		$query = $this->db->get();
		return $query;

	}

	function sum_price($table, $field, $username='')
	{
		$this->db->select_sum($field)
						 ->where('username', $username)
						 ->from($table);

		$query = $this->db->get();
		return number_format($query->row()->{$field}, 2);
	}

	function user_downloads()
	{
		// $query = $this->db->query("SELECT DISTINCT a.session_date, a.img_code, d.img_url, d.img_price, d.img_provider
		// 	FROM activities a INNER JOIN downloads d ON a.img_code = d.img_code
		// 	ORDER BY a.session_date DESC");

		$this->db->select("a.session_date, a.activity_type, a.img_code, d.id, d.img_url,
											 d.img_price, d.img_provider, d.license_id");
		$this->db->distinct();
		$this->db->from("activities a");
		$this->db->join("downloads d", "a.img_code = d.img_code", "inner");
		$this->db->order_by("a.session_date", "DESC");
		$this->db->limit(20);
		$query = $this->db->get();

		return $query->result();
	}

	function prepare_planes()
	{
		$this->db->distinct();
		$this->db->from("activities a");
		$this->db->join("planes p", "a.img_code = p.id", "inner");
		$this->db->where("a.activity_type", "compra_plan");
	}

	function user_planes($username='')
	{
		$this->db->select("a.session_date, a.activity_type, a.img_code, p.provider, p.valor, p.id, p.offerId");
		$this->prepare_planes();
		$this->db->where("a.username", $username);
		$this->db->order_by("a.session_date", "DESC");
		//$this->db->limit(7);
		$query = $this->db->get();

		return $query->result();

	}

	function sum_planes($username='')
	{
		$this->db->select_sum('valor');
		$this->prepare_planes();
		$this->db->where("username", $username);
		$query = $this->db->get();
		return number_format($query->row()->valor, 2);

	}

	function get_fullname($username=null)
	{
		$field_string = "SELECT CONCAT(first_name,' ',last_name) as fname, first_name, last_name, 
								membership.id, username, email_address, address, city, state, country, 
								zip, phone, empresa, nit, dni, cc_token, cc_method, deposit_userid";
		$table_string = " FROM membership";

		if (null != $username) {
			$field_string .= ", code";
			$table_string .=  ", countries WHERE username='$username' 
								AND membership.country=countries.name";
		} 

		$query_string = $field_string . $table_string; 
		$query = $this->db->query($query_string);
		//$row = $query->row();
		//return $row;
		return $query;
	}

	function record_subscriptions()
	{
		$new_subscription_data = array(
			'subscription_id' => $this->input->post('subscription_id'),
			'provider' => $this->input->post('provider'),
			'fecha_compra' => $this->input->post('fecha_compra')
		);

		$insert = $this->db->insert('subscriptions', $new_subscription_data);
		return $this->db->insert_id();

	}

	function get_total_payments()
	{
		$username = $this->input->post('username');
		$query = $this->db->query(
				"SELECT SUM(total) as total
				 FROM payments WHERE username='$username'");
		$row = $query->row();
		return $row;
	}

	function get_payment_order($merchantOrderId)
	{
		$this->db->where('merchant_order_id', $merchantOrderId);
		$query = $this->db->get('payments');
		if ($query->num_rows()>0) {
			$row = $query->row();
			$order = $row->order_number;
		} else {
			$order = null;
		}
		return $order;
	}

	function get_planes($id=null, $provider=null) {
		if ($id != null)
			$this->db->where('id', $id);
		if ($provider != null)
			$this->db->where('provider', $provider);
		$query = $this->db->get('planes');
		return $query;
	}

	function get_plan($medio, $frecuencia, $cantidad, $tiempo, $comision=1, $excl_prov='')
	{
		// Un solo QUERY para modificar valores a todos los planes excluyendo un proveedor
		/* $expr = "SELECT planes.id, planes.provider, planes.medio, planes.frecuencia, planes.cantidad, 
		planes.tiempo, planes.fotos_suscripcion, planes.valor AS real_valor, planes.offerId, 
		IF(planes.provider='$excl_prov',planes.valor,planes.valor*$comision) AS valor, 
		IF(calc_valor>0,'active','') as deal,
		ROUND((IF(planes.provider='$excl_prov',planes.valor,planes.valor*$comision) / planes.fotos_suscripcion), 2) AS por_imagen
		FROM planes LEFT JOIN 
			( SELECT * FROM 
				( SELECT *, IF(provider='$excl_prov',valor,valor*$comision) as calc_valor 
					FROM planes 
					WHERE medio='$medio' AND frecuencia='$frecuencia' AND cantidad=$cantidad AND tiempo=$tiempo
				) AS planes_calc 
				WHERE calc_valor = 
					( SELECT MIN(new_valor) FROM 
						( SELECT id, valor, IF(provider='$excl_prov',valor,valor*$comision) AS new_valor 
							FROM planes 
							WHERE medio='$medio' AND frecuencia='$frecuencia' AND cantidad=$cantidad
							AND tiempo=$tiempo
						) AS new_planes 
					)
			) AS planes_calc 
			ON planes.id = planes_calc.id 
			WHERE planes.medio='$medio' 
			AND planes.frecuencia='$frecuencia' AND planes.cantidad=$cantidad AND planes.tiempo=$tiempo";

		$query = $this->db->query($expr); */

		/* QUERY para traer planes sin alterar precios
		$deal = "IF((SELECT MIN(valor) FROM planes WHERE frecuencia='$frecuencia'
						AND cantidad=$cantidad AND tiempo=$tiempo)<valor,'','active')";

		$costo_imagen = "ROUND((valor / fotos_suscripcion), 2)";

		$query = $this->db->query("SELECT *, $deal AS deal, $costo_imagen AS por_imagen
							FROM planes WHERE medio='$medio' AND frecuencia='$frecuencia'
							AND cantidad=$cantidad AND tiempo=$tiempo ORDER BY valor ASC"); */

		$query = $this->db->query("SELECT *, '' AS deal, 0.00 AS por_imagen
							FROM planes WHERE medio='$medio' AND frecuencia='$frecuencia'
							AND cantidad=$cantidad AND tiempo=$tiempo ORDER BY valor ASC");

		return $query->result();
	}

	function update_planDP($data)
	{
		foreach ($data->offers as $key => $value) {
			$plan = array(
				'offerId' => $value->offerId,
				'name' => $value->name,
				'description' => $value->description,
				'period' => $value->period,
				'buyPeriod' => $value->buyPeriod,
				'count' => $value->count,
				'price' => $value->price,
				'rollover' => $value->rollover,
				'addition' => $value->addition
			);
			$this->db->insert('planes_dp', $plan);
		}
	}

	function get_plan_dp($offerId) {
		$this->db->where('offerId', $offerId);
		$query = $this->db->get('planes_dp');
		$row = $query->row();
		return $row->name;
	}

	function get_system($feature=NULL) {
		if ($feature==NULL) {
			$query = $this->db->get('system');
			$result = $query->result();
			$system = array();
			foreach ($result as $key=>$value) {
				$system[$value->feature] = array('value'=>$value->value,
												 'description'=>$value->description
												);
			}
			return $system;
		} else {
			$query = $this->db->get_where('system', array('feature' => $feature));
			$row = $query->row();
			if (isset($row)) {
	        return array('feature'=>array('value'=>$row->value,
										  'description'=>$row->description
										 ));
			} else {
				return 'No set';
			}
		}
	}

	function update_system($features)
	{
		$feature_array = array();
		foreach ($features as $key => $value) {
			$feature_array[] = array(
				 'feature' => $key,
				 'value' => $value
			);
		}
		$this->db->update_batch('system', $feature_array, 'feature');

		// Produces:
		// UPDATE `system` SET `value` = CASE
		// WHEN `feature` = 'price_comision' THEN '0.4'
		// WHEN `feature` = 'iva' THEN '0.13'
		// ELSE `value` END
		// WHERE `feature` IN ('price_comision','iva')
	}

	function insert_csv($data) {
		$this->db->truncate('planes');

		foreach($data as $key => $field) {
			$id = ($field['provider']=='Depositphotos'?'DP':
						 ($field['provider']=='Fotolia'?'FT':
						 ($field['provider']=='Dreamstime'?'DT':'IN'
					 )));
			$data[$key]['id'] = $id . str_pad($key, 3, '0', STR_PAD_LEFT);
		}

		$this->db->insert_batch('planes', $data);
	}

	function planes_params($medio) {
		$fields = ["frecuencia", "cantidad", "tiempo"];
		$medio = urldecode($medio);
		foreach ($fields as $field) {
			$query = $this->db->query("SELECT $field FROM planes WHERE medio='$medio' group by $field");
			$result = $query->result();
			foreach ($result as $value) {
				$val = $value->{$field};
				$params[$field][] = $val . 
					($field=="cantidad" ? ($medio=="Fotos"? " imágenes" : " videos")
										: ($field=="tiempo" ? ($val==1 ? " mes" :" meses") : ""));
			}
		}
		return $params;

	}

	function get_ventas()
	{
		//$span = '<span style="color:red;font-weight:800">NEW</span>';
		//$if = "IF(status='new','{$span}','') AS status";
		$this->db->select("status, DATE_FORMAT(fecha,'%d/%m/%Y %H:%i') as fecha, orderId, activity_type, username, monto");
		$this->db->where("status = '' OR status='new'");
		$res = $this->db->get('ventas');
		return $res->result();
	}

	function count_new_ventas()
	{
		$this->db->where('status = "new"');
		$this->db->from('ventas');
		return $this->db->count_all_results();
	}

	//function change_venta_status($orderId='', $new_status='')
	function change_venta_status($order, $media='', $url='')
	{
		$this->db->set('status', $order['status']);
		$this->db->set('activity_type', $order['tranType']);
		$this->db->where('orderId', $order['orderId']);
		$this->db->update('ventas');
		if ($url != "") {
			$this->update_ventas_detalle($order, $media, $url);
		}
	}

	function update_ventas_detalle($order, $media, $url) {
		$this->db->set('url_plan', $url);
		$this->db->set('user_plan', $order['items']['planes'][0]['username']);
		$this->db->set('pwd_plan', $order['items']['planes'][0]['password']);
		$this->db->where('orderId', $order['orderId']);
		$this->db->where('productId', $media);
		$this->db->update('ventas_detalle');
	}

	function get_ventas_detalle($orderId='')
	{
		$this->db->select('thumb, productId, provider, price, iva, tco, size, license_type, description, url_plan, user_plan, pwd_plan');
		$this->db->where('orderId', $orderId);
		$this->db->from('ventas_detalle');
		$res = $this->db->get();
		return $res->result();

	}

	function get_ordenes()
	{
		$this->db->select("status, DATE_FORMAT(fecha,'%d/%m/%Y %H:%i') as fecha, orderId, activity_type, username, monto");
		$this->db->where('status = "ord" OR status = "pro" OR status = "g2p"');
		$res = $this->db->get('ventas');
		return $res->result();

	}

	function count_new_ordenes()
	{
		$this->db->select('status, COUNT(status) as count');
		$this->db->where('status = "ord" OR status = "pro"');
		$this->db->from('ventas');
		$this->db->group_by('status');
		$res = $this->db->get();
		$count = (object)['ord'=>0,'pro'=>0];
		foreach ($res->result() as $value) {
			$count->{$value->status} = $value->count;
		}
		return $count;
	}

	function add_consulta()
	{
		$new_consulta_data = array(
			'status' => 'new',
			'fecha' => date("Y-m-d H:i:s"),
			'nombre' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'consulta' => $this->input->post('consulta'),
			'detalle' => $this->input->post('detalle')
		);

		$insert = $this->db->insert('consultas', $new_consulta_data);
		return $this->db->insert_id();
	}

	function get_consultas()
	{
		$this->db->select("status, DATE_FORMAT(fecha,'%e/%m/%Y') as fecha, nombre, email, phone, consulta, detalle");
		$res = $this->db->get('consultas');
		return $res->result();

	}

	function count_new_consultas()
	{
		$this->db->where('status = "new"');
		$this->db->from('consultas');
		return $this->db->count_all_results();
	}

}
