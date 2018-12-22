<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('DEF_NUMBER_OF_ROWS', 25);

class Getdeposit extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $params = array('sessionid' => $this->input->post('sessionid'));
            $this->load->library('depositclient', $params);
        }

 
        function search()
        {
            // 'road, open, highway, mountain, street, summer, drive, journey, field, travel, escape, infinity, landscaped',
                $params = array(
                        //RpcParams::SEARCH_QUERY  => 'animal avian background biology bird isolated', '69916121'
                        RpcParams::SEARCH_QUERY  => $this->input->post('search'),
                        // 128480448, 
                        RpcParams::SEARCH_LIMIT  => DEF_NUMBER_OF_ROWS,
                        RpcParams::SEARCH_OFFSET => 75//$offset
                        //RpcParams::SEARCH_USERNAME => 'Kama'
                    );
                $res = $this->depositclient->search($params);
                $output = '<div class="input-field">';
                $output .= '<textarea id="ta1" class="materialize-textarea">'.$this->depositclient->fullurl.'</textarea><label for="ta1">Full Url</label></div>';
                $offset = ($this->uri->segment(3) ?: 0);
                $this->pagination($res->count);
                $output .= $this->pagination->create_links().'<br/>';
                
                $i = $offset;
                foreach ($res->result as $value) {
                    ++$i;
                    $output .= $i."-".$value->id.'<br/>';
                }
                $output .= empty($res->result) ? 'No results' : $this->show($res->result[0]);
                $output .= "Total found: " . $res->count . "<br/>";
                $sizes = array();
                $subaccounts = array();

                return $output;

        }

		function imgdetail()
		{

		}

        function pagination($totalrows)
        {
			//pagination settings
			$config['base_url'] = site_url('Getdeposit/index/');
			$config['total_rows'] = $totalrows;
			$config['per_page'] = 100;
			$config["uri_segment"] = 3;
			$choice = $config["total_rows"] / $config["per_page"];
			$config["num_links"] = 5; //floor($choice);
			$config['use_page_numbers'] = false;
			$config['first_url'] = site_url('Getdeposit/index/').'?'.http_build_query($_GET);;
			if (count($_GET) > 0) 
			{
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
			}				

			//config for bootstrap pagination class integration
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';

			$this->pagination->initialize($config);
        
        }

        function account_test()
        {
            $res = $this->depositclient->getSubaccountData('6911886');
            print_r($res);   
        }

        function show($info)
        {
            return '<pre>'.print_r($info, true).'</pre>';
        }
        
        function index()
        {
            $dot = $this->input->post('f')?:'';
            if (!$dot)
                $res = '';
            $search = $this->input->post('search');
            $media = $this->input->post('media');
            $sessionid = $this->input->post('sessionid');
            $subusername = $this->input->post('subusername');
            $subpass = $this->input->post('subpass');
            $email = $this->input->post('email');
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');
            $subaccountid = $this->input->post('subaccountid');
            $subsperiodid = $this->input->post('subsperiodid');
            $subscountid = $this->input->post('subscountid');
            $subsdescid = $this->input->post('subsdescid');
            $subscriptionid = $this->input->post('subscriptionid');
            $size = $this->input->post('size');
            $subscripciones = array();
            if (false == $sizes = $this->input->post('sizes'))
                $sizes = array();
            if (false == $subaccounts = $this->input->post('subaccounts'))
                $subaccounts = array();
            if (false == $subsperiod = $this->input->post('subsperiod'))
                $subsperiod = array();
            if (false == $subscount = $this->input->post('subscount'))
                $subscount = array();
            if (false == $subsdesc = $this->input->post('subsdesc'))
                $subsdesc = array();


            $output = link_tag("materialize/css/materialize.min.css");
            $output .= link_tag("https://fonts.googleapis.com/icon?family=Material+Icons");
            $output .= '<div class="row">';
            $output .= '<div class="col s12">';
            $output .= '<ul class="tabs">';
            $output .= '<li class="tab col s3"><a class="active" href="#test1">Search</a></li>';
            $output .= '<li class="tab col s3"><a href="#test2">Test 2</a></li>';
            $output .= '<li class="tab col s3"><a href="#test3">Disabled Tab</a></li>';
            $output .= '<li class="tab col s3"><a href="#test4">Test 4</a></li>';
            $output .= '</ul>';
            $output .= '</div>';
            
            $output .= '<div id="test2" class="col s12">Test 2</div>';
            $output .= '<div id="test3" class="col s12">Test 3</div>';
            $output .= '<div id="test4" class="col s12">Test 4</div>';
            $output .= '</div>';            
            //$output .= '<div class="row">';
            //$output .= '<div class="col s9">';

            if ($dot == 'search'){
                $output .= $this->search();

            }

            if ($dot == 'mdata'){
                $res = $this->depositclient->getMediaData($media);
                $output .= $res->width.' x '.$res->height.' ('.$res->mp.')';
                foreach ($res->sizes as $key => $value) {
                    $sizes[$key] = $key;
                }
            }

            if ($dot == 'login'){
                $res = $this->depositclient->login('latincolorimages', 'latincol2016$');
                $sessionid = $res->sessionid;
            }

            if ($dot == 'logout'){
                $res = $this->depositclient->logout($sessionid);
                $sessionid = '';
            }

            if ($dot == 'creasub'){
                $res = $this->depositclient->createSubaccount($sessionid, $subusername, $subpass, $email, $firstname, $lastname);
            }

            if ($dot == 'getsub'){
                $res = $this->depositclient->getSubaccounts();
                
                foreach ($res->subaccounts as $value) {
                    $subaccounts[$value] = $value; 
                }
           }

            if ($dot == 'subdata'){
                $res = $this->depositclient->getSubaccountData($subaccountid);
                $email = $res->email;
                $firstname = $res->firstName;
                $lastname = $res->lastName;
            }

            if ($dot == 'updsub'){
                $res = $this->depositclient->updateSubaccount($subaccountid, $email, $firstname, $lastname);
            }

            if ($dot == 'getsoffer'){
                $res = $this->depositclient->getSubscriptionOffers($subaccountid);
                $subscripciones = $res->offers;
                foreach ($res->offers as $key => $value) {
                    $subsperiod[$value->period] = $value->period; 
                    //$subscount[$value->count] = $value->count;
                }
                ksort($subsperiod);
                ksort($subscount);
            }

            if ($dot == 'newsubs'){
                $res = $this->depositclient->createSubaccountSubscription($subaccountid, $subscriptionid);
            }

            if ($dot == 'getsubs'){
                $res = $this->depositclient->getSubscriptions($subaccountid);
            }

            if ($dot == 'getmedia'){
                $res = $this->depositclient->getMedia($media, 'standard', $size, $subaccountid);
            }

            if ($dot == 'pursub'){
                $res = $this->depositclient->getSubaccountPurchases($subaccountid,50,0);
            }

            if ($dot == 'purch'){
                $res = $this->depositclient->getPurchases(50,0);               
            }

            /* Datos de compra de la foto 69916121
            [timestamp] => 2017-03-28 18:01:20
            [version] => 1.3
            [type] => success
            [downloadLink] => http://depositphotos.com/download_prepaid_file.php?id=a437e751dd7b269a95dbfd1193d81152
            [licenseId] => 83587936
            [method] => credits
            */

            if ($dot == 'redown'){
                $res = $this->depositclient->reDownload(83587936);
            }
            
            if ($dot != 'search')
                $output .= $this->show($res);

            $output .= '</div>';
        	
            /*
        	$data = $this->depositclient->getCategoriesList();
        	foreach ($data as $key => $value) 
        	{
        		print_r($key . "=" . $value . "<br/>");
        	}
        	//$array = json_decode(json_encode($data), true);
        	//echo $array['6'];
        	
        	$params = array(
        			RpcParams::SEARCH_QUERY  => 'business',
        			RpcParams::SEARCH_LIMIT  => '2'
        		);
        	$response = get_object_vars($this->depositclient->search($params));
        	foreach ($response as $response_item => $response_content) 
        	{
        		if (is_array($response_content)) //($response_item === 'result')
        		{
        			echo $response_item.":<br/>"; //result contents
        			foreach ($response_content as $result_item) 
        			{
        				echo "image data:<br/>"; //image content
        				$result_item_content = get_object_vars($result_item);        			
	        			foreach ($result_item_content as $image_data => $image_value) 
	        			{
	        				if (is_array($image_value)) //($image_data === 'similar')
	        				{
	        					echo "similar:<br/>";
        						foreach ($image_value as $similar_item => $similar_value) 
        						{
        							print_r($similar_item."=>".$similar_value."<br/>");
        						}
        					}
        					else
	        					print_r($image_data."=>".$image_value."<br/>");
        				}
        			}
        		}
        		else
        			print_r($response_item."=>".$response_content."<br/>");
        	}
        	//print_r($response['result']."<br/>");
        	//$res = get_object_vars($response['result'][0]);
        	print_r($response);
        	echo "<br/>result------ <br/>";
        	foreach ($response['result'] as $result) {
        		print_r($result->id."<br/>");
        		print_r($result->huge_thumb."<br/>");
        	}
        	//print_r($response['result'][0]->id);
        	//print_r($response['count']);
        	
        	//echo "Primer similar de: ".$response['result'];
            */
            /*
            $res = $this->depositclient->getMediaData('39685537');
            echo "<pre>";
            print_r($res);
            echo "<pre>";
            //print_r($res->medium_thumbnail);
            */
            /*
            $params = array(
                RpcParams::SEARCH_QUERY  => 'road, open, highway, mountain, street, summer, drive, journey, field, travel, escape, infinity, landscaped',
                RpcParams::SEARCH_LIMIT  => '6',
                RpcParams::SEARCH_OFFSET => '0'
            );
            $response = $this->depositclient->search($params);
            print_r($response);
            echo "<br/>";
            if (empty($response->result)) echo 'No hay resultados';
            */
            //$output .= '<div style="z-index: 1;position: absolute;left: 0;top: 0;">';
            //$output .= '<div class="col s3">';
            $attr = array('method'=>'post');
            
            //$output .= form_open(__CLASS__, $attr);
            foreach ($sizes as $value) {
                $output .= '<input type="hidden" name="sizes['.$value.']" value="'.$value.'">';
            }
            foreach ($subaccounts as $value) {
                $output .= '<input type="hidden" name="subaccounts['.$value.']" value="'.$value.'">';
            }


            $output .= '<div id="test1" class="col s12">';
            //$output .= form_radio('f', 'search', true, 'id="r1"');
            $output .=  '<div class="row">';
            $output .=      '<div class="col s6 push-s3">';
            $output .=          '<nav>';
            $output .=              '<div class="nav-wrapper">';
            $output .=                  form_open(__CLASS__, array('method'=>'post'));
            $output .=                  '<div class="input-field">';
            $output .=                      '<input id="search" type="search" name="search" required>';
            $output .=                      '<label class="label-icon" for="search">';
            $output .=                          '<i class="material-icons">search</i>';
            $output .=                      '</label>';
            $output .=                      '<i class="material-icons">close</i>';
            $output .=                  '</div>';
            $output .=                  '<input type="hidden" name="f" value="search"/>';
            $output .=                  form_close();
            $output .=              '</div>';
            $output .=          '</nav>';
            $output .=      '</div>';
            $output .=   '</div>';
            $output .= '</div>';


            $output .= form_radio('f', 'mdata', false, 'id="r2"') . form_label('Media details', 'r2');
            $output .= '<div class="input-field">';
            $output .= form_input('media', $media, 'id="media"');
            $output .= form_label('Media', 'media');
            $output .= '</div>';
            if ($sizes){
                $output .= '<div class="input-field">';
                $output .= form_dropdown('size', $sizes, $size);
                $output .= form_label('Sizes');
                $output .= '</div>';
            }
            $output .= form_fieldset_close();
            $output .= form_fieldset('Session');
            if ($sessionid)
                $output .= form_radio('f', 'logout', false, 'id="r3"') . form_label('Logout', 'r3');
            else
                $output .= form_radio('f', 'login', false, 'id="r4"') . form_label('Login', 'r4');

            $output .= '<div class="input-field">';
            $output .= form_input('sessionid', $sessionid, 'id="sessionid"') .'<br/>';
            $output .= form_label('Session ID', 'sessionid');
            $output .= '</div>';
            $output .= form_fieldset_close();
            $output .= form_fieldset('Subaccounts');
            $output .= form_radio('f', 'creasub', false, 'id="r5"') . form_label('Create subaccount', 'r5');
            $output .= '<div class="input-field">';
            $output .= form_input('email', $email) .'<br/>';
            $output .= form_label('Email', 'email');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_input('firstname', $firstname, 'id="firstname"');
            $output .= form_label('First name', 'firstname');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_input('lastname', $lastname, 'id="lastname"');
            $output .= form_label('Last name', 'lastname');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_input('subusername', $subusername, 'id="subusername"');
            $output .= form_label('Username', 'subusername');
            $output .= '<div class="input-field">';
            $output .= form_input('subpass', $subpass, 'id="subpass"');
            $output .= form_label('Password', 'subpass');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_dropdown('subaccountid', $subaccounts, $subaccountid);
            $output .= form_label('Subaccount ID');
            $output .= '</div>';
            $output .= form_radio('f', 'getsub', false, 'id="r8"') . form_label('Get subaccounts', 'r8');
            $output .= form_radio('f', 'subdata', false, 'id="r9"') . form_label('Get subaccount Data', 'r9');
            $output .= form_radio('f', 'pursub', false, 'id="r10"') . form_label('Subaccount Purchases', 'r10');
            $output .= form_radio('f', 'updsub', false, 'id="r11"') . form_label('Update subaccount', 'r11');
            $output .= form_fieldset_close();
            $output .= form_fieldset('Subscriptions');
            $output .= form_radio('f', 'getsoffer', false, 'id="r12"') . form_label('Get subscription offers', 'r12');           
            $output .= '<div class="input-field">';
            $output .= form_dropdown('subsperiod', $subsperiod, $subsperiodid, array('id'=>'subsperiod'));
            $output .= form_label('Periodo');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_dropdown('subscount', $subscount, $subscountid, array('id'=>'subscount'));
            $output .= form_label('Count');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_dropdown('subsdesc', $subsdesc, $subsdescid, array('id'=>'subsdesc'));
            $output .= form_label('Description');
            $output .= '</div>';
            $output .= '<div class="input-field">';
            $output .= form_input('subscriptionid', $subscriptionid, 'id="subscriptionid"');
            $output .= form_label('Subscription ID', 'subscriptionid');
            $output .= '</div>';

            $output .= form_fieldset_close();
            $output .= form_fieldset('Download & Purchases');
            $output .= form_radio('f', 'getmedia', false, 'id="r6"') . form_label('Download Media', 'r6');
            $output .= form_radio('f', 'purch', false, 'id=r7') . form_label('Purchases', 'r7');
            $output .= form_radio('f', 'redown', false, 'id=r13') . form_label('Re-download', 'r13');
            $output .= form_radio('f', 'newsubs', false, 'id=r14') . form_label('Create Subscription', 'r14');
            $output .= form_radio('f', 'getsubs', false, 'id=r15') . form_label('Get Subscriptions', 'r15');
            $output .= form_fieldset_close();
            $output .= form_submit('submit', 'submit');
            $output .= form_close();
            $output .= "</div></div>";
            $output .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
            $output .= '<script src="'.base_url("materialize/js/materialize.js").'"></script>';
            $output .= '<script type="text/javascript">
                        $(document).ready(function() { 
                            var subscripciones ='.json_encode($subscripciones). ';
                            var selectedPeriod;

                            $("select").material_select(); 

                            $("[name=subsperiod]").on("change", function(){
                                $("[name=subscount]").html("<option value=0>-Select Count-</option>");
                                for(var i=0; i<subscripciones.length; i++){
                                    if(subscripciones[i].period == $(this).val())
                                    {
                                        $("[name=subscount]").append("<option value="+subscripciones[i].count+">"+subscripciones[i].count+"</option>");
                                        selectedPeriod = $(this).val();
                                    }
                                }
                                $("select").material_select();
                            });

                            $("[name=subscount]").on("change", function(){
                                $("[name=subsdesc]").html("<option value=0>-Select Plan-</option>");
                                for(var i=0; i<subscripciones.length; i++){
                                    if(subscripciones[i].count == $(this).val() && subscripciones[i].period == selectedPeriod)
                                    {
                                        $("[name=subsdesc]").append("<option value="+subscripciones[i].offerId+">"+subscripciones[i].description+"</option>");
                                    }
                                }
                                $("select").material_select();
                            });

                            $("[name=subsdesc]").on("change", function(){
                                $("#subscriptionid").val($(this).val());
                            });

                        });
                        </script>';
            echo $output;

        }

}