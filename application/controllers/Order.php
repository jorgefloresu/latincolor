<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        /* require_once APPPATH . 'libraries/2CO/Twocheckout.php';
        require_once APPPATH . 'libraries/PayU.php'; */

        $this->load->driver('providers');
        $this->load->driver('pasarelas');
    }

    public function consulta_payu($orderId)
    {
        $this->pasarelas->payu->consulta_payu($orderId);
    }

    public function testForm()
    {
        $this->load->view('pages/order_intan');
    }

    public function pay()
    {
        $this->load->view('pages/payment_form');
    }

    public function dummy_payment()
    {
        if ($this->input->post('token') == 'TokenDePrueba') {
            $this->load->model('membership_model');
            echo $this->membership_model->record_payment();
        } else {
            echo "Error with token";
        }

    }

    public function PayU_token()
    {
        $username = $this->input->post('username');
        $fullname = $this->membership_model->get_fullname($username);

        $pay = [
            'userinfo' => $fullname->row(),
            'ccNo' => $this->input->post('ccNo'),
            'expire' => $this->input->post('expYear').'/'.$this->input->post('expMonth'),
            'tipoTarjeta' => $this->input->post('tipoTarjeta')
        ];

        $response = $this->pasarelas->payu->PayU_token($pay);
        echo json_encode($response);            
    }

    public function PayU_pay_token()
    {
        $username = $this->input->post('username');
        $fullname = $this->membership_model->get_fullname($username);

        $unique = strtotime('now');

        $pay = [
            'userinfo' => $fullname->row(),
            'orderId' => floatval($unique),
            'totalId' => $this->input->post('totalId')
        ];

        $response = $this->pasarelas->payu->PayU_pay_token($pay);
        echo json_encode($response);
    }

    public function PayU_pay($orderId, $userinfo)
    {
        $pay = [
            'orderId' => $orderId,
            'totalId' => $this->input->post('totalId'),
            'currency' => 'USD',
            'userinfo' => $userinfo,
            'ccNo' => $this->input->post('ccNo'),
            'expire' => $this->input->post('expYear').'/'.$this->input->post('expMonth'),
            'cvv' => $this->input->post('cvv'),
            'tipoTarjeta' => $this->input->post('tipoTarjeta')
        ];

        $response = (array) $this->pasarelas->payu->PayU_pay($pay);
        if (isset($response['state'])) {
            $response['merchantOrderId'] = $pay['orderId'];
        }
        return $response;           
    }

    public function TCO_pay($orderId, $userinfo) 
    {
        $pay = array(
            "merchantOrderId" => $orderId,
            "token" => $this->input->post('token'),
            "currency" => 'USD',
            "total" => $this->input->post('totalId'),
            "billingAddr" => array(
                "name" => $userinfo->fname,
                "addrLine1" => $userinfo->address,
                "city" => $userinfo->city,
                "state" => $userinfo->state,
                "zipCode" => $userinfo->zip,
                "country" => $userinfo->country,
                "email" => $userinfo->email_address,
                "phoneNumber" => $userinfo->phone,
            ),
            "shippingAddr" => array(
                "name" => $userinfo->fname,
                "addrLine1" => $userinfo->address,
                "city" => $userinfo->city,
                "state" => $userinfo->state,
                "zipCode" => $userinfo->zip,
                "country" => $userinfo->country,
                "email" => $userinfo->email_address,
                "phoneNumber" => $userinfo->phone,
            ),
        );

        $charge = $this->pasarelas->tco->TCO_pay($pay);
        //$this->assertEquals('APPROVED', $charge['response']['responseCode']);
        //echo "<pre>";print_r($charge);echo "</pre>";

        return $charge;
    }

    public function testPay()
    {
        $this->load->model('membership_model');
        $items = json_decode($this->input->post('items'), true);
        //$all_items = array_merge($items['images'], $items['planes']);
        $activity_type = $this->input->post('tranType');

        /* $username = $this->input->post('username');
        $activity_type = $this->input->post('tranType');
        $product_id = $this->input->post('productId');
        $provider = $this->input->post('provider');
        $description = $this->input->post('description'); */
        $username = $this->input->post('username');
        $fullname = $this->membership_model->get_fullname($username);
        $userinfo = $fullname->row();


        $unique = strtotime('now');
        $orderId = floatval($unique);

        $subaccountCheck = true;

        try {
            if (count($items['planes']) > 0) {
                $provider = $items['planes'][0]['provider'];
                if ($provider=='Depositphoto') {
                    $subaccountId = $userinfo->deposit_userid;
                    if ($subaccountId == '') {
                        $subaccountId = $this->providers->createSubaccount($provider, $userinfo);
                        if ($subaccountId == 'Ya existe login') {
                            $subaccountCheck = false;
                            throw new Exception('Ya existe login');
                            //throw new Twocheckout_Error('Ya existe login');
                            //echo json_encode($charge);
                            //return;
                        }
                        else    
                            $this->membership_model->update_member($username, ['deposit_userid' => $subaccountId]);
                    }
                }
            }
        } catch (Exception $e) {
            //print_r($e->getMessage());
            echo json_encode($e);
            exit();
        }

        if ($subaccountCheck) {
             
            //Twocheckout procedure
            /* $charge = $this->TCO_pay($orderId, $userinfo);       
            if ($charge['response']['responseCode'] == 'APPROVED') {                
                $this->membership_model->record_transaction($username, $activity_type, $charge);
                echo json_encode($charge);
            } */
            
            $charge = $this->PayU_pay($orderId, $userinfo);
            if (isset($charge['state'])) {
                if ($charge['state'] == 'APPROVED') {
                    $this->membership_model->record_transaction($username, $activity_type, $charge);
                }
                echo json_encode($charge);
            } else {
                echo json_encode($charge[0]); 
            }
        }
    }

//    function confirmar_orden($orderId='', $username='', $monto=0, $description='', $activity_type='', $status='')
    public function confirmar_orden()
    {
        $this->load->library('membership');

        //$order_data = json_decode($this->input->get('order'), true);
        $order = [
            'orderId' => $this->input->get('orderId'),
            'totalId' => $this->input->get('totalId'),
            'username'=> $this->input->get('username'),
            'description'=> $this->input->get('tranType'),
            'images_status' => '',
            'planes_status' => '',
            'items' => json_decode($this->input->get('items'), true)
        ];
        //$product_id = $this->input->get('productId');

        $username = $order['username'];
        $fullname = $this->membership_model->get_fullname($username);
        $userinfo = $fullname->row();
        $resSubacc = [];
        $resSubs = [];

        $from_admin = $this->input->get('status');

        if (! $from_admin) {

            $subaccountId = $userinfo->deposit_userid;
            if (count($order['items']['planes']) > 0) {
                $order_data = $order['items']['planes'][0];
                //$order_data['status'] = ( $order_data['provider']=='Depositphoto' ? 'new' : 'ord' );
                if ($order_data['provider'] == 'Depositphoto') {
                    if ($subaccountId == '') {
                        $subaccountId = $this->providers->createSubaccount($order_data['provider'], $userinfo);
                        if ($subaccountId != 'Ya existe login') {
                            $resSubacc = $this->membership_model->update_member($username, ['deposit_userid' => $subaccountId]);
                        }
                    }
                    if ($subaccountId != 'Ya existe login') {
                        $status = 'new';
                        $resSubs = $this->providers->createSubscription($order_data['provider'], $order_data['idplan'], $subaccountId);
                    }
                } else {
                    $status = 'ord';
                }

                $order['list'][] = ['orderId' => floatval("{$order['orderId']}2"),
                                    'description' => $order['items']['planes'][0]['description'],
                                    'tranType' => 'compra_plan',
                                    'status' => $status];
            } 

            if (count($order['items']['images']) > 0) {
                $order['list'][] = ['orderId' => floatval("{$order['orderId']}1"),
                                    'description' => $order['items']['images'] > 1 ? 'Compra de imágenes' : 'Compra de imagen',
                                    'tranType' => $order['items']['images'] > 1 ? 'compra_imgs' : 'compra_img',
                                    'status' => 'new'];
            }
        } else {
            $order['list'][] = ['orderId' => $order['orderId'],
                                'description' => $this->input->get('description'),
                                'tranType' => $this->input->get('tranType'),
                                'status' => $from_admin];
        }
        //$res = $this->membership->confirmar_orden($orderId, $username, $monto, $description, $activity_type, $status);
        if ($subaccountId == 'Ya existe login') {
            http_response_code(500);
            $res = "El email del usuario ya esta registrado con Depositphoto. Debe proveer userID asignado.";
        } else {
            $res = $this->membership->confirmar_orden($order);
        }
        $res['subs'] = $resSubs;
        $res['subacc'] = $resSubacc;
        echo json_encode($res);
    }


}
