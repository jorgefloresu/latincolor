<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership
{
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library(['session','taxes']);
		$this->CI->load->model('membership_model');
	}

	public function is_logged_in()
	{
		$is_logged_in = $this->CI->session->userdata('is_logged_in');
		if (! $is_logged_in )
		{
			$logstatus = '';
			//echo 'You don\'t have permission to access this page. <a href="'.site_url('login').'">Login</a>';
			//die();
			//$this->load->view('login_form');
		}
		else
			$logstatus = $this->CI->session->userdata('username');

		return $logstatus;
	}

	public function get_user($username)
	{
		$fullname = $this->CI->membership_model->get_fullname($username);
		return $fullname->row();
	}

	public function get_plan($medio, $frecuencia, $cantidad, $tiempo)
	{
		$plan = $this->CI->membership_model->get_plan($medio, $frecuencia, $cantidad, $tiempo);
		foreach ($plan as $key => $value) {
			$plan[$key]->iva = $this->CI->taxes->set_iva($value->valor);
			$plan[$key]->tco = $this->CI->taxes->set_tco($value->valor+$plan[$key]->iva, true);
		}
		return $plan;
	}

	public function get_system($feature)
	{
		return $this->CI->membership_model->get_system($feature);
	}

	public function planes_params($medio) {
		return $this->CI->membership_model->planes_params($medio);
	}

	public function confirmar_orden($order) {
		//public function confirmar_orden($orderId='', $username='', $monto=0, $description='', $activity_type='', $status='') {
		// $this->CI->membership_model->change_venta_status($orderId, $status);
		// $detalles = $this->CI->membership_model->get_ventas_detalle($orderId);
		// $user = $this->CI->membership_model->get_fullname($username);
		// $this->send_email_order($user->row(), $orderId, $detalles[0]->productId, $monto, $description, $activity_type, $status);
		$user = $this->CI->membership_model->get_fullname($order['username']);
		$resp_process = [];
		foreach ($order['list'] as $value) {
			# code...
			$order['orderId'] = $value['orderId'];
			$order['status'] = $value['status'];
			$order['description'] = $value['description'];
			$order['tranType'] = $value['tranType'];
			$detalles = $this->CI->membership_model->get_ventas_detalle($order['orderId']);
			/* if ( ! array_key_exists('description', $order) ) {
				$order['description'] = $detalles[0]->description;
			}
			else {
				$order['description'] = $order['description']=='' ? $detalles[0]->description : $order['description'];
			} */
			//print_r($order);
			//$res['url'] = '';
			$res = $this->send_email_order($user->row(), $order);
			$order['images_status'] = $res['images']['result'];
			$order['planes_status'] = $res['planes']['result'];
			$resp_process = array_merge($resp_process, $res);
			$url = array_key_exists('planes', $res) ? $res['planes']['url'] : '';
			if ($order['planes_status'] == 'ok' && $order['status'] == 'pro') {
				$order['status'] = '';
			}
			$this->CI->membership_model->change_venta_status($order, $detalles[0]->productId, $url);
		}
		
		return ['process'  => $resp_process,
				'detalles' => $detalles];
	}

	// public function send_email_order($user, $orderId, $media, $valor, $description, $activity_type, $status)
	public function send_email_order($user, $order)
	{
		//$email_data['user'] = $user;
		//$email_data['orderId'] = $order['orderId'];
		//$email_data['productId'] = $media;
		//$email_data['description'] = $order['description'];

		$this->CI->load->library("PHPMailer_Library");
		$mail = $this->CI->phpmailer_library->createPHPMailer();
		$mail->addAddress($user->email_address, $user->fname);
		//$mail->addAddress("jorfu@yahoo.com", $user->fname);
		$mail->CharSet = 'utf-8';
		$mail->isHTML(true);
		$url = "";
		$result = [
				   'images' => ['result'=> $order['images_status'], 'url' => ''],
				   'planes' => ['result'=> $order['planes_status'], 'url' => '']
				  ];

		if (count($order['items']['planes']) > 0 && $order['planes_status'] == '') {
			$media = $order['items']['planes'][0]['productId'];
			$mail->Subject = "Orden de compra {$order['orderId']} - Plan {$media}";

			if ($order['status'] == 'ord') {
				$email = $this->CI->load->view('email/Orden_recibida/mail','', TRUE);
				$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
				$mail->AddCC("gerencia@latincolorimages.com");
				$mail->AltBody = 'Su orden fue recibida';

				$result['planes'] = $this->send_mail($mail);

			} elseif ($order['status'] == 'g2p') {
				$email = $this->CI->load->view('email/Orden_proceso/mail', '', TRUE);
				$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
				$mail->AltBody = 'Su orden está en proceso';
				$order['status'] = 'pro';

				$result['planes'] = $this->send_mail($mail);

			} else {
				$email = $this->CI->load->view('email/Orden_completa/mail', '', TRUE);
				switch ($order['items']['planes'][0]['provider']) {
					case 'Fotosearch':
						$attach = realpath("img/Acuerdo de licencia de Fotosearch.pdf");
						$url = 'http://www.unlistedimages.com';
						break;
					case 'Depositphoto':
						$attach = realpath("img/Contrato de Suscripcion Depositphotos.pdf");
						break;
					case 'Dreamstime':
						$attach = realpath("img/Dreamstime Licencia Standar y Extendida.pdf");
						$url = "http://www.dreamstime.com";
						break;
					case 'Ingimages':
						$attach = '';
						$url = 'http://www.ingimage.com';
						break;
					default:
						$attach = '';
				}
				$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
				$mail->AddCC("gerencia@latincolorimages.com");
				//$mail->AddCC("jorfu@yahoo.com");
				$mail->AltBody = 'Su orden está lista';
				if ($attach) {
					$mail->addAttachment($attach);
				}

				$result['planes'] = $this->send_mail($mail, $url);

				/* if ($result['planes']['result'] == 'ok') {
					$order['status'] = '';
					$this->CI->membership_model->change_venta_status($order);
				} */
			}

		} elseif (count($order['items']['images']) > 0 && $order['images_status'] == '') {
			$mail->Subject = "Orden de compra {$order['orderId']} - Imágenes";
			$email = $this->CI->load->view('email/Compra/mail', '', TRUE);

			$media = '';
			$attached = ['Fotosearch'=>FALSE, 'Depositphoto'=>FALSE, 'Dreamstime'=>FALSE];
			foreach ($order['items']['images'] as $key => $value) {
				$media .= $value['productId'].'<br>';
				//Attachments
				switch ($value['provider']) {
					case 'Fotosearch':
						if (!$attached['Fotosearch']) {
							$mail->addAttachment(realpath("img/Acuerdo de licencia de Fotosearch.pdf"));
							$attached['Fotosearch'] = TRUE;
						}
						break;
					case 'Depositphoto':
						if (!$attached['Depositphoto']) {
							$mail->addAttachment(realpath("img/Contrato de Licencia Standar y Extendida Depositphotos.pdf"));
							$attached['Depositphoto'] = TRUE;
						}
						break;
					case 'Dreamstime':
						if (!$attached['Dreamstime']) {
							$mail->addAttachment(realpath("img/Dreamstime Licencia Standar y Extendida.pdf"));
							$attached['Dreamstime'] = TRUE;
						}
						break;
				}
			}
			$mail->AddCC("gerencia@latincolorimages.com");
			$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
			$mail->AltBody = $order['description'];

			$result['images'] = $this->send_mail($mail);
		}
		//$this->CI->membership_model->change_venta_status($order['orderId'], $order['status']);
		return $result;
	}

	function send_mail($mail, $url='')
	{
		try {
			if (!$mail->send()) {
				throw new Exception("Mailer Error: " . $mail->ErrorInfo, 1);
			} 
		} catch (Exception $e) {
			return ['result'=>$e->getMessage(),
					'url'=>""];
		}

		return ['result'=>"ok",
				'url' => $url];
	}

	function replaceTags($user, $order, $media, $email, $url) {
		$email = str_replace('__USUARIO__', strtoupper($user), $email);
		$email = str_replace('__ORDEN__', $order['orderId'], $email);
		$email = str_replace('__PRODUCTO__', $media, $email);
		$email = str_replace('__DESCRIPCION__', $order['description'], $email);
		if ( $url != '' ) {
			$email = str_replace('__PROVEEDOR__', $order['items']['planes'][0]['provider'], $email);
			$email = str_replace('__PLANUSER__', $order['items']['planes'][0]['username'], $email);
			$email = str_replace('__PLANPASS__', $order['items']['planes'][0]['password'], $email);
			$email = str_replace('__URL__', $url, $email);
		}
		return $email;
	}


}
