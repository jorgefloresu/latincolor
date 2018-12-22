<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	private $logo;

	function __construct() {
		parent::__construct();
		$this->logo = base_url('img/LCI_LOGO_T56.png');
		$this->load->library('membership');
		$this->load->model('membership_model');

	}

	public function index()
	{
    $logged = $this->membership->is_logged_in();
    if ($logged)
    {
			redirect('pages/view');
		}
		else
		{
			$data['title'] = "Home Page";
			$data['logged'] = $logged;
			$this->load->view('main', $data);
		}
	}

	function save_user_session($query)
	{
		$data = array(
			'username' => $query->row()->username,
			'first_name' => $query->row()->first_name,
			'last_name' => $query->row()->last_name,
			'email_address' => $query->row()->email_address,
			'country' => $query->row()->country,
			'is_logged_in' => true
		);
		$this->session->set_userdata($data);
		return $data;
	}

	function set_userpref() {
		$username = $this->membership->is_logged_in();
		if ($username != '') {
			$prefs = $this->input->get('prefs');
			$this->membership_model->update_member($username, $prefs);
			$userdata = $this->membership_model->get_fullname($username);
			$this->save_user_session($userdata);
			echo json_encode(array(
				'result' => 'updated',
				'userData' => $userdata->row()
			));
		}
		else
			redirect();
	}

	function validate_credentials($goto='')
	{
		$query = $this->membership_model->validate();

		if($query) // if the user's credentials validated...
		{
			$data = $this->save_user_session($query);
		}
		else
		{
			http_response_code(404);
			$data = array(
				'response' => 'Clave incorrecta'
				);
		}
		if ($goto == 'redir')
			redirect('admin');
		else
			echo json_encode($data);
	}

	function user_info()
	{
		$user_info = array(
			'username' => $this->session->username,
			'country' => $this->session->country
		);
		echo json_encode($user_info);
	}

	function signup()
	{
		$data['title'] = "Login Page";
		$data['main_content'] = 'pages/signup_form';
		$data['logged'] = false;
		$this->load->view('templates/login_template', $data);
		//$this->load->view('templates/header');
		//$this->load->view('pages/signup_form');
		//$this->load->view('templates/footer');

	}

	function forgot_pass()
	{

    $logged = $this->membership->is_logged_in();

		$data['logo'] = $this->logo;
		$data['title'] = "Login Page";
		$data['logged'] = $logged;
		$this->load->view('pages/forgot_pass', $data);

	}

	function create_member()
	{
		$email = $this->input->post('email_address');
		$username = $this->input->post('username');

		if ($this->membership_model->user_exists($username)) {
			http_response_code(404);
			echo 'Nombre de usuario ya está asignado';

		} elseif ($this->membership_model->email_exists($email)) {
			http_response_code(404);
			echo 'Correo ya está asignado a otro usuario';

		} elseif ($this->membership_model->create_member()) {

			$fullname = $this->input->post('first_name')+' '+$this->input->post('last_name');
			$email_data['first_name'] = $this->input->post('first_name');
			$email_data['email_address'] = $email;

			$this->load->library("PHPMailer_Library");
			$mail = $this->phpmailer_library->createPHPMailer();

			//Set who the message is to be sent to
			$mail->addAddress($email, $fullname);
			//Set the subject line
			$mail->Subject = 'Bienvenido a Latin Color Images';
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->CharSet = 'utf-8';
			//$mail->msgHTML(file_get_contents(VIEWPATH.'content_sample.php'), __DIR__);
			$mail->isHTML(true);
			$body = $this->load->view('email/Bienvenido/mail','', TRUE);
			$mail->Body = str_replace('__USUARIO__', strtoupper($email_data['first_name']), $body);
			//Replace the plain text body with one created manually
			$mail->AltBody = 'Su cuenta ha sido creada';

			if (!$mail->send()) {
					http_response_code(500);
					echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
					echo "Se ha enviado el correo con éxito";
			}

		} else {
			http_response_code(500);
			echo 'Ha ocurrido un error al crear el usuario';
		}

	}

	function regenerate_pass_OFF() {
		$email = $this->input->get('email_address');
		if ($query = $this->membership_model->email_exists($email)) {
			$to = $email;
			//echo "your email is ::".$email;
			//Details for sending E-mail
			$from = "MySite";
			$url = "http://www.mysite.com/";
			$pass = $this->randomPassword();
			$body  =  "MySite user password recovery service
			-----------------------------------------------
			Url : $url;
			email Details is : $to;
			Here is your password  : $pass;
			Sincerely,
			MySite";
			$from = "admin@mysite.com";
			$subject = "MySite Password recovered";
			$headers1 = "From: $from\n";
			$headers1 .= "Content-type: text/html;charset=iso-8859-1\r\n";
			$headers1 .= "X-Priority: 1\r\n";
			$headers1 .= "X-MSMail-Priority: High\r\n";
			$headers1 .= "X-Mailer: Just My Server\r\n";
			$sentmail = mail ( $to, $subject, $body, $headers1 );
		}
		else {
			echo "<span style='color: #ff0000;''> Not found your email in our database</span>";
		}

		if($sentmail==1)
			echo "<span style='color: #ff0000;'> Your Password Has Been Sent To Your Email Address.</span>";
		else
			echo "<span style='color: #ff0000;'> Cannot send password to your e-mail address.Problem with sending mail...</span>";
	}

	function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	function logout($action = 'index')
	{
		$this->session->sess_destroy();
		//session_write_close();
		switch ($action) {
			case 'index':
				redirect();
				break;
			case 'noindex':
				echo json_encode(array('status'=>'success'));
				break;
			default:
				redirect($action);
				break;
		}
	}

	function is_logged(){
        if ($this->membership->is_logged_in())
        	return json_encode('1');
        else
        	return json_encode('0');
	}

	function phpmailer() {
		$this->load->library("phpmailer_library");
		$this->phpmailer_library->load();

	}

	function forgot_pass_phpmailer() {
		//Check if email exists in database
		$email = $this->input->post('email_forgot');
		$user_from_email = $this->membership_model->email_exists($email);
		if ($user_from_email) {
				//Load dependencies from composer
				//If this causes an error, run 'composer install'
				//Create a new PHPMailer instance
				$this->load->library("PHPMailer_Library");
				$mail = $this->phpmailer_library->createPHPMailer();

				//Set who the message is to be sent to
				$mail->addAddress($email, 'Jorge Flores');
				//Set the subject line
				$mail->Subject = 'Password recovery';
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->CharSet = 'utf-8';
				//$mail->msgHTML(file_get_contents(VIEWPATH.'content_sample.php'), __DIR__);
				$mail->isHTML(true);
				$new_passwd = $this->randomPassword();
				$mail->Body = 'The new pasword for <b>'.$email.
				 							'</b> is: <b>'. $new_passwd.'</b>';
				//Replace the plain text body with one created manually
				$mail->AltBody = 'This is a plain-text message body';
				//Attach an image file
				//$mail->addAttachment(FCPATH.'img/avatar.jpg');
				//send the message, check for errors
				if (!$mail->send()) {
						http_response_code(500);
				    echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
						$this->membership_model->update_member($user_from_email, ['password'=>$new_passwd]);
				    echo "Su nueva clave ha sido enviada al correo proporcionado";
				}
			} else {
				http_response_code(404);
				echo 'Correo no esta registrado';
			}
	}


}
