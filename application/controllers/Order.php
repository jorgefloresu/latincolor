<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'libraries/2CO/Twocheckout.php';
        require_once APPPATH . 'libraries/PayU.php';

        $this->load->driver('providers');
    }

    public function init_payu()
    {
        //PayU::$apiKey = "6u39nqhq8ftd0hlvnjfs66eh8c"; //Ingrese aquí su propio apiKey.
        //PayU::$apiLogin = "11959c415b33d0c"; //Ingrese aquí su propio apiLogin.
        //PayU::$merchantId = "500238"; //Ingrese aquí su Id de Comercio.
        PayU::$apiKey = "4Vj8eK4rloUd272L48hsrarnUA";
        PayU::$apiLogin = "pRRXKOl8ikMmt9u";
        PayU::$merchantId = "508029";
        PayU::$language = SupportedLanguages::ES; //Seleccione el idioma.
        PayU::$isTest = true; //Dejarlo True cuando sean pruebas.

        //Environment::setPaymentsCustomUrl("https://stg.api.payulatam.com/payments-api/4.0/service.cgi");
        //Environment::setReportsCustomUrl("https://stg.api.payulatam.com/reports-api/4.0/service.cgi");
        //Environment::setSubscriptionsCustomUrl("https://stg.api.payulatam.com/payments-api/rest/v4.3/");

        // URL de Pagos
        Environment::setPaymentsCustomUrl("https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi");
        // URL de Consultas
        Environment::setReportsCustomUrl("https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi");
        // URL de Suscripciones para Pagos Recurrentes
        Environment::setSubscriptionsCustomUrl("https://sandbox.api.payulatam.com/payments-api/rest/v4.3/");
    }

    public function test_payu()
    {
        $this->init_payu();
        $reference = "payment_test_00000097";
        $value = "20000";
        $deviceSessionId = md5(session_id() . microtime());
        //para realizar un pago con tarjeta de crédito---------------------------------
        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            //PayUParameters::ACCOUNT_ID => "500538",
            PayUParameters::ACCOUNT_ID => "512321",
            //Ingrese aquí el código de referencia.
            PayUParameters::REFERENCE_CODE => $reference,
            //Ingrese aquí la descripción.
            PayUParameters::DESCRIPTION => "payment test",

            // -- Valores --
            //Ingrese aquí el valor.
            PayUParameters::VALUE => $value,
            //Ingrese aquí el valor del IVA (Impuesto al Valor Agregado solo valido para Colombia) de la transacción,
            //si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales.
            //Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
            PayUParameters::TAX_VALUE => "3193",
            //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
            //En caso de que no tenga IVA debe enviarse en 0.
            PayUParameters::TAX_RETURN_BASE => "16806",

            //Ingrese aquí la moneda.
            PayUParameters::CURRENCY => "COP",

            // -- Comprador
            //Ingrese aquí el nombre del comprador.
            PayUParameters::BUYER_NAME => "First name and second buyer  name",
            //Ingrese aquí el email del comprador.
            PayUParameters::BUYER_EMAIL => "buyer_test@test.com",
            //Ingrese aquí el teléfono de contacto del comprador.
            PayUParameters::BUYER_CONTACT_PHONE => "7563126",
            //Ingrese aquí el documento de contacto del comprador.
            PayUParameters::BUYER_DNI => "5415668464654",
            //Ingrese aquí la dirección del comprador.
            PayUParameters::BUYER_STREET => "calle 100",
            PayUParameters::BUYER_STREET_2 => "5555487",
            PayUParameters::BUYER_CITY => "Medellin",
            PayUParameters::BUYER_STATE => "Antioquia",
            PayUParameters::BUYER_COUNTRY => "CO",
            PayUParameters::BUYER_POSTAL_CODE => "000000",
            PayUParameters::BUYER_PHONE => "7563126",

            // -- pagador --
            //Ingrese aquí el nombre del pagador.
            PayUParameters::PAYER_NAME => "APPROVED",
            //Ingrese aquí el email del pagador.
            PayUParameters::PAYER_EMAIL => "payer_test@test.com",
            //Ingrese aquí el teléfono de contacto del pagador.
            PayUParameters::PAYER_CONTACT_PHONE => "7563126",
            //Ingrese aquí el documento de contacto del pagador.
            PayUParameters::PAYER_DNI => "5415668464654",
            //Ingrese aquí la dirección del pagador.
            PayUParameters::PAYER_STREET => "calle 93",
            PayUParameters::PAYER_STREET_2 => "125544",
            PayUParameters::PAYER_CITY => "Bogota",
            PayUParameters::PAYER_STATE => "Bogota",
            PayUParameters::PAYER_COUNTRY => "CO",
            PayUParameters::PAYER_POSTAL_CODE => "000000",
            PayUParameters::PAYER_PHONE => "7563126",

            // -- Datos de la tarjeta de crédito --
            //Ingrese aquí el número de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_NUMBER => "4097440000000004",
            //Ingrese aquí la fecha de vencimiento de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_EXPIRATION_DATE => "2018/12",
            //Ingrese aquí el código de seguridad de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_SECURITY_CODE => "321",
            //Ingrese aquí el nombre de la tarjeta de crédito
            //VISA||MASTERCARD||AMEX||DINERS
            PayUParameters::PAYMENT_METHOD => "VISA",

            //Ingrese aquí el número de cuotas.
            PayUParameters::INSTALLMENTS_NUMBER => "1",
            //Ingrese aquí el nombre del pais.
            PayUParameters::COUNTRY => PayUCountries::CO,

            //Session id del device.
            PayUParameters::DEVICE_SESSION_ID => $deviceSessionId,
            //IP del pagadador
            PayUParameters::IP_ADDRESS => "127.0.0.1",
            //Cookie de la sesión actual.
            PayUParameters::PAYER_COOKIE => session_id(),
            //Cookie de la sesión actual.
            PayUParameters::USER_AGENT => "Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0",
        );

        //solicitud de autorización y captura
        $response = PayUPayments::doAuthorizationAndCapture($parameters);

        //  -- podrás obtener las propiedades de la respuesta --
        if ($response) {
            $response->transactionResponse->orderId;
            $response->transactionResponse->transactionId;
            $response->transactionResponse->state;
            if ($response->transactionResponse->state == "PENDING") {
                $response->transactionResponse->pendingReason;
            } elseif ($response->transactionResponse->state == "DECLINED") {
                $response->transactionResponse->paymentNetworkResponseCode;
                $response->transactionResponse->responseMessage;
                //$response->transactionResponse->paymentNetworkResponseErrorMessage;
            } else {
                $response->transactionResponse->trazabilityCode;
                $response->transactionResponse->responseCode;
            }
            echo "<pre>";
            print_r($response->transactionResponse);
            echo "</pre>";
        }
    }

    public function consulta_payu()
    {
        //Esta funcion devuelve muchos mas datos de la transaccion
        $this->init_payu();
        //Ingresa aquí el código de referencia de la orden.
        //history id: 7765626
        $parameters = array(PayUParameters::ORDER_ID => "7765642");

        $order = PayUReports::getOrderDetail($parameters);

        if ($order) {
            $order->accountId;
            $order->status;
            $order->referenceCode;
            $order->additionalValues->TX_VALUE->value;
            $order->additionalValues->TX_TAX->value;
            if ($order->buyer) {
                $order->buyer->emailAddress;
                $order->buyer->fullName;
            }
            $transactions = $order->transactions;
            foreach ($transactions as $transaction) {
                $transaction->type;
                $transaction->transactionResponse->state;
                //$transaction->transactionResponse->paymentNetworkResponseCode;
                $transaction->transactionResponse->trazabilityCode;
                $transaction->transactionResponse->responseCode;
                if ($transaction->payer) {
                    $transaction->payer->fullName;
                    $transaction->payer->emailAddress;
                }
            }
        }
        echo "<pre>";
        print_r($order);
        echo "</pre>";

    }

    public function trans_payu()
    {
        //Esta funcion devuelve los mismos datos que se obtienen al hacer un pago
        //history tran: 788ddc83-cf24-427f-b68a-5be75c5befa5
        $this->init_payu();
        $parameters = array(PayUParameters::TRANSACTION_ID => "e7cac6f4-a5f8-474c-8756-25517e405904");

        $response = PayUReports::getTransactionResponse($parameters);

        if ($response) {
            $response->state;
            $response->trazabilityCode;
            $response->authorizationCode;
            $response->responseCode;
            $response->operationDate;
        }
        echo "<pre>";
        print_r($response);
        echo "</pre>";
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

    public function testPay()
    {
        $this->load->model('membership_model');
        $username = $this->input->post('username');
        $activity_type = $this->input->post('tranType');
        $product_id = $this->input->post('productId');
        $provider = $this->input->post('provider');
        $description = $this->input->post('description');
        $fullname = $this->membership_model->get_fullname($username);
        $userinfo = $fullname->row();

        $subaccountCheck = true;

        try {
            if ($activity_type == 'compra_plan' && $provider=='Depositphoto') {
                $subaccountId = $userinfo->deposit_userid;
                if ($subaccountId == '') {
                    $subaccountId = $this->providers->createSubaccount($provider, $userinfo);
                    if ($subaccountId == 'Ya existe login') {
                        $subaccountCheck = false;
                        throw new Twocheckout_Error('Ya existe login');
                        //echo json_encode($charge);
                        //return;
                    }
                    else    
                        $this->membership_model->update_member($username, ['deposit_userid' => $subaccountId]);
                }
            }
        } catch (Twocheckout_Error $e) {
            print_r($e->getMessage());
            //echo json_encode($charge);
        }

        if ($subaccountCheck) {

            Twocheckout::privateKey('04327A71-E9DD-4350-9520-A09680D20C5D');
            Twocheckout::sellerId('901331385');
            Twocheckout::sandbox(true);

            try {
            
                $charge = Twocheckout_Charge::auth(array(
                        "merchantOrderId" => $this->input->post('orderId'),
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
                    ), 'array');
        
                if ($charge['response']['responseCode'] == 'APPROVED') {
                
                    //try {
                    //    $status = ( $provider=='Depositphoto' ? '' : 'ord' );
                    //$this->providers->createSubscription($provider, $product_id, $subaccountId);
                    //    $this->confirmar_orden($this->input->post('orderId'), $username,
                    //                                                 $this->input->post('totalId'), $description, $status);
                    //} catch (Exception $e) {
                    //    $charge['noemail'] = $e->getMessage();
                    //}
                    $this->membership_model->record_transaction($username, $activity_type, $product_id, $charge);
                    echo json_encode($charge);
                }
                        
                    //$this->assertEquals('APPROVED', $charge['response']['responseCode']);
                    //echo "<pre>";print_r($charge);echo "</pre>";
            } catch (Twocheckout_Error $e) {
                //$this->assertEquals('Bad request - parameter error', $e->getMessage());
                print_r($e->getMessage());
                echo json_encode($charge);
            }
        }
    }

//    function confirmar_orden($orderId='', $username='', $monto=0, $description='', $activity_type='', $status='')
    public function confirmar_orden()
    {
        $this->load->library('membership');

        $order_data = json_decode($this->input->get('order'), true);

        //$product_id = $this->input->get('productId');

        //$username = $this->input->get('username');
        //$fullname = $this->membership_model->get_fullname($username);
        //$userinfo = $fullname->row();
        //$subaccountId = $userinfo->deposit_userid;
        /*$resSubacc = [];
        if ($subaccountId == '') {
            $subaccountId = $this->providers->createSubaccount($order_data['provider'], $userinfo);
            $resSubacc = $this->membership_model->update_member($username, ['deposit_userid' => $subaccountId]);

        }
        $resSubs = [];*/

        if (!array_key_exists('status', $order_data)) {

            if ($order_data['tranType'] == 'compra_plan') {
                //$order_data['status'] = ( $order_data['provider']=='Depositphoto' ? 'new' : 'ord' );
                if ($order_data['provider'] == 'Depositphoto') {
                    $order_data['status'] = 'new';
                    //$resSubs = $this->providers->createSubscription($order_data['provider'], $order_data['idplan'], $subaccountId);
                } else {
                    $order_data['status'] = 'ord';
                }
            } else {
                $order_data['status'] = 'new';
            }
        }
        //$res = $this->membership->confirmar_orden($orderId, $username, $monto, $description, $activity_type, $status);
        $res = $this->membership->confirmar_orden($order_data);
        //$res['subs'] = $resSubs;
        //$res['subacc'] = $resSubacc;
        echo json_encode($res);
    }

    public function payment()
    {
        //echo $this->input->post('username');
        $this->load->model('membership_model');
        $fullname = $this->membership_model->get_fullname($this->input->post('username'));
        $userinfo = $fullname->row();

        Twocheckout::username('jfloresu');
        Twocheckout::password('Jafu6921');
        Twocheckout::privateKey('04327A71-E9DD-4350-9520-A09680D20C5D');
        Twocheckout::sellerId('901331385');
        Twocheckout::sandbox(true); // Set to false for production accounts.
        //echo $this->input->post('token')."<br>";
        //echo $this->input->post('orderId')."<br>";
        $params = array(
            "sellerId" => '901331385',
            "privateKey" => '04327A71-E9DD-4350-9520-A09680D20C5D',
            "merchantOrderId" => $this->input->post('orderId'),
            "token" => $this->input->post('token'),
            "currency" => 'USD',
            "lineItems" => array(array('name' => 'Demo Item',
                'price' => $this->input->post('totalId'),
                'type' => 'product',
                'quantity' => '1',
                'productId' => $this->input->post('imageCode'),
                'name' => $this->input->post('description'),
                'recurrence' => null,
                'startupFee' => '0.00',
            )),
            "billingAddr" => array(
                "name" => $userinfo->fname,
                "addrLine1" => '123 Test St',
                "city" => 'Columbus',
                "state" => 'OH',
                "zipCode" => '43123',
                "country" => 'USA',
                "email" => $userinfo->email_address,
                "phoneNumber" => '555-555-5555',
            ),
        );

        try {
            $charge = Twocheckout_Charge::auth($params);
            if ($charge['response']['responseCode'] == 'APPROVED') {
                //echo "Thanks for your Order!";
                //echo "<h3>Return Parameters:</h3>";
                //echo "<pre>";
                $this->membership_model->record_payment($charge);
                //print_r(json_encode($charge));
                echo json_encode($charge);
                //echo "</pre>";
            }
        } catch (Twocheckout_Error $e) {
            print_r(json_encode($params));
            print_r($e->getMessage());
        }
    }

    public function testChargeForm()
    {
        $params = array(
            'sid' => '901331385',
            'mode' => '2CO',
            'li_0_name' => 'Test Product',
            'li_0_price' => '0.01',
        );
        Twocheckout::sandbox(true);
        Twocheckout_Charge::form($params, "Click Here!");
    }

    public function testDirectAuto()
    {
        Twocheckout::sandbox(true);
        $params = array(
            'sid' => '901331385', //'901313445',
            'mode' => '2CO',
            'li_0_type' => 'Product',
            'li_0_name' => 'Test Product',
            'li_0_price' => '0.01',
            'card_holder_name' => 'Jorge Flores',
            'email' => 'jorgefloresu@gmail.com',
            'phone' => '614-921-2450',
            'street_address' => '123 Test St',
            'city' => 'Columbus',
            'state' => 'OH',
            'zip' => '43123',
            'country' => 'USA',
            'lang' => 'es_la',
        );
        Twocheckout_Charge::direct($params, 'Buy');
    }

    public function passback()
    {
        //Assign the returned parameters to an array.
        $params = array();
        foreach ($_REQUEST as $k => $v) {
            $params[$k] = $v;
        }

        //Check the MD5 Hash to determine the validity of the sale.
        $passback = Twocheckout_Return::check($params, "ZTA2OTFjNzAtYjQwNi00MzJiLThiNDQtYmQwZjFlZGZkZDk2");
        //print_r($passback);
        if ($passback['response_code'] == 'Success') {
            $order_number = $params['order_number'];
            $invoice_id = $params['invoice_id'];
            $credit_card_processed = $params['credit_card_processed'];
            $total = $params['total'];
        }
        $_POST['username'] = 'jfloresu';
        $_POST['activity_type'] = $order_number;
        $_POST['img_code'] = $invoice_id;
        $this->load->model('membership_model');
        $this->membership_model->record_transaction();
    }

    public function testChargeLink()
    {
        Twocheckout::sandbox(true);
        $params = array(
            'sid' => '901331385',
            'mode' => '2CO',
            'li_0_name' => 'Test Product',
            'li_0_price' => '0.01',
        );
        $link = Twocheckout_Charge::link($params);
        echo anchor($link, 'Check out');
    }

}
