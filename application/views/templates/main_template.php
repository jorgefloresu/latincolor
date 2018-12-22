<?php 	$data['bodytype'] = $title;
		$data['logged'] = $logged;
		$this->load->view('templates/header', $data);
 		if ($title == "Login Page")
			echo '<link rel="stylesheet" href="'.base_url("materialize/css/materialize-prefix.css").'"" />';

		$this->load->view($main_content, $data);
		$this->load->view('templates/footer'); 
?>