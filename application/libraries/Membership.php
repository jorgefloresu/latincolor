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

	public function get_plan($provider, $frecuencia, $cantidad, $tiempo)
	{
		$plan = $this->CI->membership_model->get_plan($provider, $frecuencia, $cantidad, $tiempo);
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

	public function planes_params() {
		return $this->CI->membership_model->planes_params();
	}

	public function confirmar_orden($order) {
		//public function confirmar_orden($orderId='', $username='', $monto=0, $description='', $activity_type='', $status='') {
		// $this->CI->membership_model->change_venta_status($orderId, $status);
		// $detalles = $this->CI->membership_model->get_ventas_detalle($orderId);
		// $user = $this->CI->membership_model->get_fullname($username);
		// $this->send_email_order($user->row(), $orderId, $detalles[0]->productId, $monto, $description, $activity_type, $status);
		$detalles = $this->CI->membership_model->get_ventas_detalle($order['orderId']);
		$user = $this->CI->membership_model->get_fullname($order['username']);
		if ( ! array_key_exists('description', $order) ) {
			$order['description'] = $detalles[0]->description;
		}
		else {
			$order['description'] = $order['description']=='' ? $detalles[0]->description : $order['description'];
		}
		//print_r($order);
		$this->send_email_order($user->row(), $order, $detalles[0]->productId);
		return ['process'  => 'ok',
						'detalles' => $detalles];
	}

	// public function send_email_order($user, $orderId, $media, $valor, $description, $activity_type, $status)
	public function send_email_order($user, $order, $media)
	{
		$email_data['user'] = $user;
		$email_data['orderId'] = $order['orderId'];
		$email_data['productId'] = $media;
		$email_data['description'] = $order['description'];

		$this->CI->load->library("PHPMailer_Library");
		$mail = $this->CI->phpmailer_library->createPHPMailer();
		$mail->addAddress($user->email_address, $user->fname);
		$mail->CharSet = 'utf-8';
		$mail->isHTML(true);

		if ($order['tranType'] == 'compra_plan') {
			$mail->Subject = "Orden de compra {$order['orderId']} - Plan {$media}";

			if ($order['status'] == 'ord') {
				$email = $this->CI->load->view('email/Orden_recibida/mail','', TRUE);
				$url = "";
				$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
				$mail->AddCC("gerencia@latincolorimages.com");
				$mail->AltBody = 'Su orden fue recibida';

			} elseif ($order['status'] == 'g2p') {
				$email = $this->CI->load->view('email/Orden_proceso/mail', '', TRUE);
				$url = "";
				$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
				$mail->AltBody = 'Su orden está en proceso';
				$order['status'] = 'pro';

			} else {
				$email = $this->CI->load->view('email/Orden_completa/mail', '', TRUE);
				switch ($order['plan']['provider']) {
					case 'Fotosearch':
						$attach = realpath("img/Acuerdo de licencia de Fotosearch.pdf");
						$url = "";
						break;
					case 'Depositphoto':
						$attach = realpath("img/Contrato de Suscripcion Depositphotos.pdf");
						$url = "";
						break;
					case 'Dreamstime':
						$attach = realpath("img/Dreamstime Licencia Standar y Extendida.pdf");
						$url = "http://www.dreamstime.com";
						break;
				}
				$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
				$mail->AddCC("gerencia@latincolorimages.com");
				$mail->AltBody = 'Su orden está lista';
				$mail->addAttachment($attach);

			}

		} else {
			$mail->Subject = "Orden de compra {$order['orderId']} - Imágenes";
			$email = $this->CI->load->view('email/Compra/mail', '', TRUE);
			$url = "";
			$mail->Body = $this->replaceTags($user->first_name, $order, $media, $email, $url);
			$mail->AltBody = 'Has comprado una o varias imágenes';
			//Attachments
			switch ($order['provider']) {
				case 'Fotosearch':
					$mail->addAttachment(realpath("img/Acuerdo de licencia de Fotosearch.pdf"));
					break;
				case 'Depositphoto':
					$mail->addAttachment(realpath("img/Contrato de Licencia Standar y Extendida Depositphotos.pdf"));
					break;
				case 'Dreamstime':
					$mail->addAttachment(realpath("img/Dreamstime Licencia Standar y Extendida.pdf"));
					break;
			}

		}
		//$this->CI->membership_model->change_venta_status($order['orderId'], $order['status']);
		$this->CI->membership_model->change_venta_status($order, $media, $url);

		if (!$mail->send()) {
				throw new Exception("Mailer Error: " . $mail->ErrorInfo, 1);
		} else {
				return "Tu compra ha sido exitosa";
		}

	}

	function replaceTags($user, $order, $media, $email, $url) {
		$email = str_replace('__USUARIO__', strtoupper($user), $email);
		$email = str_replace('__ORDEN__', $order['orderId'], $email);
		$email = str_replace('__PRODUCTO__', $media, $email);
		$email = str_replace('__DESCRIPCION__', $order['description'], $email);
		if ( array_key_exists('plan', $order)) {
			$email = str_replace('__PROVEEDOR__', $order['plan']['provider'], $email);
			$email = str_replace('__PLANUSER__', $order['plan']['username'], $email);
			$email = str_replace('__PLANPASS__', $order['plan']['password'], $email);
			$email = str_replace('__URL__', $url, $email);
		}
		return $email;
	}


}
