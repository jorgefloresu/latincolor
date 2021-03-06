<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testpayu extends CI_Controller {

    function __construct() {
        parent::__construct();
        require_once APPPATH . 'libraries/PayU.php';
		$this->load->model('membership_model');

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

    public function PayU_pay($orderId, $username)
    {
        $fullname = $this->membership_model->get_fullname($username);
        $userinfo = $fullname->row();

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

        $response = (array) $this->PayU_Auth($pay);
        if (isset($response['state'])) {
            $response['merchantOrderId'] = $pay['orderId'];
        }

		header('Content-Type: application/json');
        echo json_encode($response);           
    }

    public function PayU_Auth($pay)
    {
        $this->init_payu();

        $parameters = array_merge($this->pay_parameters($pay), $this->cc_parameters($pay));
        //Ingrese aquí el código de seguridad de la tarjeta de crédito
        $parameters[PayUParameters::CREDIT_CARD_SECURITY_CODE] = $pay['cvv'];

        //solicitud de autorización y captura
        try {
            $response = PayUPayments::doAuthorizationAndCapture($parameters);
        } catch (Exception $e) {
            $mess = $e->getMessage();
            //$message = explode("\n", $mess);
            //$response = count($message)>1 ? substr($message[0], strpos($message[0], 'message:')+9, strlen($message[0])) : $message[0];

            return $mess;
        }
        //  -- podrás obtener las propiedades de la respuesta --        
        return $response->transactionResponse;
            
    }

    function pay_parameters($pay)
    {
        $pay['currency'] = 'USD';
        $pay['deviceSessionId'] = md5(session_id() . microtime());

        //para realizar un pago con tarjeta de crédito---------------------------------
        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            //PayUParameters::ACCOUNT_ID => "500538",
            PayUParameters::ACCOUNT_ID => "512321",
            //Ingrese aquí el código de referencia.
            PayUParameters::REFERENCE_CODE => $pay['orderId'],
            //Ingrese aquí la descripción.
            PayUParameters::DESCRIPTION => "payment test ". $pay['orderId'],

            // -- Valores --
            //Ingrese aquí el valor.
            PayUParameters::VALUE => $pay['totalId'],
            //Ingrese aquí el valor del IVA (Impuesto al Valor Agregado solo valido para Colombia) de la transacción,
            //si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales.
            //Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
            PayUParameters::TAX_VALUE => 0, //"3193",
            //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
            //En caso de que no tenga IVA debe enviarse en 0.
            PayUParameters::TAX_RETURN_BASE => $pay['totalId'], //"16806",

            //Ingrese aquí la moneda.
            PayUParameters::CURRENCY => $pay['currency'],

            // -- Comprador
            //Ingrese aquí el nombre del comprador.
            PayUParameters::BUYER_NAME => $pay['userinfo']->fname,
            //Ingrese aquí el email del comprador.
            PayUParameters::BUYER_EMAIL => $pay['userinfo']->email_address,
            //Ingrese aquí el teléfono de contacto del comprador.
            PayUParameters::BUYER_CONTACT_PHONE => $pay['userinfo']->phone,
            //Ingrese aquí el documento de contacto del comprador.
            PayUParameters::BUYER_DNI => "5415668464654",
            //Ingrese aquí la dirección del comprador.
            PayUParameters::BUYER_STREET => $pay['userinfo']->address,
            PayUParameters::BUYER_STREET_2 => "",
            PayUParameters::BUYER_CITY => $pay['userinfo']->city,
            PayUParameters::BUYER_STATE => $pay['userinfo']->state,
            PayUParameters::BUYER_COUNTRY => $pay['userinfo']->code,
            PayUParameters::BUYER_POSTAL_CODE => $pay['userinfo']->zip,
            PayUParameters::BUYER_PHONE => $pay['userinfo']->phone,

            // -- pagador --
            //Ingrese aquí el nombre del pagador.
            PayUParameters::PAYER_NAME => "APPROVED",
            //Ingrese aquí el email del pagador.
            PayUParameters::PAYER_EMAIL => $pay['userinfo']->email_address,
            //Ingrese aquí el teléfono de contacto del pagador.
            PayUParameters::PAYER_CONTACT_PHONE => $pay['userinfo']->phone,
            //Ingrese aquí el documento de contacto del pagador.
            PayUParameters::PAYER_DNI => "5415668464654",
            //Ingrese aquí la dirección del pagador.
            PayUParameters::PAYER_STREET => $pay['userinfo']->address,
            PayUParameters::PAYER_STREET_2 => "",
            PayUParameters::PAYER_CITY => $pay['userinfo']->city,
            PayUParameters::PAYER_STATE => $pay['userinfo']->state,
            PayUParameters::PAYER_COUNTRY => $pay['userinfo']->code,
            PayUParameters::PAYER_POSTAL_CODE => $pay['userinfo']->zip,
            PayUParameters::PAYER_PHONE => $pay['userinfo']->phone,

            //Ingrese aquí el nombre de la tarjeta de crédito
            //VISA||MASTERCARD||AMEX||DINERS
            //PayUParameters::PAYMENT_METHOD => $pay['userinfo']->cc_method,
            PayUParameters::PAYMENT_METHOD => $pay['tipoTarjeta'],

            //Ingrese aquí el número de cuotas.
            PayUParameters::INSTALLMENTS_NUMBER => "1",
            //Ingrese aquí el nombre del pais.
            PayUParameters::COUNTRY => PayUCountries::CO,

            //Session id del device.
            PayUParameters::DEVICE_SESSION_ID => $pay['deviceSessionId'],
            //IP del pagadador
            PayUParameters::IP_ADDRESS => $_SERVER['REMOTE_ADDR'],
            //Cookie de la sesión actual.
            PayUParameters::PAYER_COOKIE=> session_id(),
            //Cookie de la sesión actual.        
            PayUParameters::USER_AGENT=> $_SERVER['HTTP_USER_AGENT']
        );
        return $parameters;
    }

    function cc_parameters($pay)
    {
        //para realizar un pago con tarjeta de crédito---------------------------------
        // -- Datos de la tarjeta de crédito --
        $parameters = array(
            //Ingrese aquí el número de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_NUMBER => $pay['ccNo'],
            //Ingrese aquí la fecha de vencimiento de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $pay['expire'],
        );

        return $parameters;

    }

    public function consulta_payu($orderId)
    {
        //Esta funcion devuelve muchos mas datos de la transaccion
        $this->init_payu();
        //Ingresa aquí el código de referencia de la orden.
        //history id: 7765626
        $parameters = array(PayUParameters::ORDER_ID => $orderId);

        try {
            $order = PayUReports::getOrderDetail($parameters);
        } catch (Exception $e) {
            $order = $e->getMessage();
        }

        return $order;

    }

}