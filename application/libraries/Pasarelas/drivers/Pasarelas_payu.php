<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasarelas_payu extends CI_Driver {

	function __construct() {
        require_once APPPATH . 'libraries/PayU.php';
    }
    
    public function init_payu($testInProd=FALSE)
    {
        if ( strpos(base_url(), 'latincolorimages.com') || $testInProd)
        {
            // PARA PRODUCCION
            PayU::$apiKey = "7n4NihX9sYjJXtNJMWQpN8SuOQ";
            PayU::$apiLogin = "KSL1JO3V5g6w0Hc";
            PayU::$merchantId = "766521";
            PayU::$isTest = false; //Dejarlo True cuando sean pruebas.
            Environment::setPaymentsCustomUrl("https://api.payulatam.com/payments-api/4.0/service.cgi");
            Environment::setReportsCustomUrl("https://api.payulatam.com/reports-api/4.0/service.cgi");
            Environment::setSubscriptionsCustomUrl("https://api.payulatam.com/payments-api/rest/v4.9/");
        }
        else
        {
            // PARA PRUEBAS 
            PayU::$apiKey = "4Vj8eK4rloUd272L48hsrarnUA";
            PayU::$apiLogin = "pRRXKOl8ikMmt9u";
            PayU::$merchantId = "508029";
            PayU::$isTest = true; //Dejarlo True cuando sean pruebas.
            Environment::setPaymentsCustomUrl("https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi");
            Environment::setReportsCustomUrl("https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi");
            Environment::setSubscriptionsCustomUrl("https://sandbox.api.payulatam.com/payments-api/rest/v4.3/");
        }

        PayU::$language = SupportedLanguages::ES; //Seleccione el idioma.

    }

    function pay_parameters($pay)
    {
        $pay['currency'] = 'USD';
        $pay['deviceSessionId'] = md5(session_id() . microtime());

        //para realizar un pago con tarjeta de crédito---------------------------------
        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            //PayUParameters::ACCOUNT_ID => "500538",
            PayUParameters::ACCOUNT_ID => "773138",
            //Ingrese aquí el código de referencia.
            PayUParameters::REFERENCE_CODE => $pay['orderId'],
            //Ingrese aquí la descripción.
            PayUParameters::DESCRIPTION => "Pago de la orden ". $pay['orderId'],

            // -- Valores --
            //Ingrese aquí el valor.
            PayUParameters::VALUE => $pay['totalId'],
            //Ingrese aquí el valor del IVA (Impuesto al Valor Agregado solo valido para Colombia) de la transacción,
            //si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales.
            //Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
            PayUParameters::TAX_VALUE => 0, //"3193",
            //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
            //En caso de que no tenga IVA debe enviarse en 0.
            PayUParameters::TAX_RETURN_BASE => 0, //"16806",

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
            //PayUParameters::BUYER_DNI => "5415668464654",
            PayUParameters::BUYER_DNI => $pay['userinfo']->dni,
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
            //PayUParameters::PAYER_NAME => "APPROVED",
            PayUParameters::PAYER_NAME => $pay['userinfo']->fname,
            //Ingrese aquí el email del pagador.
            PayUParameters::PAYER_EMAIL => $pay['userinfo']->email_address,
            //Ingrese aquí el teléfono de contacto del pagador.
            PayUParameters::PAYER_CONTACT_PHONE => $pay['userinfo']->phone,
            //Ingrese aquí el documento de contacto del pagador.
            //PayUParameters::PAYER_DNI => "5415668464654",
            PayUParameters::PAYER_DNI => $pay['userinfo']->dni,
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

    public function PayU_pay($pay)
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
            $message = explode("\n", $mess);
            $response = count($message)>1 ? substr($message[0], strpos($message[0], 'message:')+9, strlen($message[0])) : $message[0];

            return $response;
        }
        //  -- podrás obtener las propiedades de la respuesta --        
        return $response->transactionResponse;
            
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

    public function PayU_token($data)
    {
        $this->init_payu();

        $parameters = array(
            //Ingrese aquí el nombre del pagador.
            PayUParameters::PAYER_NAME => "APPROVED",
            //Ingrese aquí el identificador del pagador.
            PayUParameters::PAYER_ID => $data['userinfo']->id,
            //Ingrese aquí el documento de identificación del comprador.
            PayUParameters::PAYER_DNI => "32144457",
        );

        $parameters = array_merge($parameters, $this->cc_parameters($data));

        try {    
            $response = PayUTokens::create($parameters);   
        } catch (Exception $e) {
            $response = $e->getMessage();
            //$message = explode(', ', $mess);
            //$response = count($message)>1 ? substr($message[1], strpos($message[1], ':')+1, strlen($message[1])) : $message[0];
        }
         return $response;
            
    }

    public function PayU_pay_token($pay)
    {
        $this->init_payu();

        $parameters = $this->pay_parameters($pay);

        //DATOS DEL TOKEN
        $parameters[PayUParameters::TOKEN_ID] = $pay['userinfo']->cc_token;
                
        try {
            $response = PayUPayments::doAuthorizationAndCapture($parameters);
        } catch (Exception $e) {
            $mess = $e->getMessage();
            if ($mess == 'Error inesperado') {
                return 'Error en la pasarela. Favor intente de nuevo';
            } else {
                return $mess;
            }
            exit();
        }

        return $response->transactionResponse;
    }

    public function banks()
    {
        $this->init_payu();
        //Ingrese aquí el nombre del medio de pago
        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            PayUParameters::PAYMENT_METHOD => "PSE",
            //Ingrese aquí el nombre del pais.
            PayUParameters::COUNTRY => PayUCountries::CO,
        );
        $array=PayUPayments::getPSEBanks($parameters);
        return $array->banks;

    }

    public function persona() 
    {
        return [['tipo'=>'Persona Natural', 'code'=>'N'],
                ['tipo'=>'Persona Jurídica', 'code'=>'J']];
    }
  
    public function documento() 
    {
        return [['code'=>'CC', 'tipo'=>'Cédula de ciudadanía'],
                ['code'=>'CE', 'tipo'=>	'Cédula de extranjería'],
                ['code'=>'NIT', 'tipo'=>	'Número de Identificación Tributaria'],
                ['code'=>'TI', 'tipo'=>	'Tarjeta de Identidad'],
                ['code'=>'PP', 'tipo'=>	'Pasaporte'],
                ['code'=>'IDC', 'tipo'=>	'Identificador único de cliente'],
                ['code'=>'CEL', 'tipo'=>	'Identificador de la línea del móvil'],
                ['code'=>'RC', 'tipo'=>	'Registro civil de nacimiento'],
                ['code'=>'DE', 'tipo'=>	'Documento de identificación extranjero']];
    }
  
    public function tarjeta() 
    {
        return [['code'=>'VISA', 'tipo'=>'VISA'],
                ['code'=>'MASTERCARD', 'tipo'=>'MASTERCARD'],
                ['code'=>'DINERS', 'tipo'=>'DINERS'],
                ['code'=>'AMEX', 'tipo'=>'AMEX'],
                ['code'=>'VISA_DEBIT', 'tipo'=>'VISA_DEBIT']];
    }
  


    /*
    *** TEST ****
    */

    public function token_payu()
    {
        $this->pasarelas->init_payu();

        $parameters = array(
            //Ingrese aquí el nombre del pagador.
            PayUParameters::PAYER_NAME => "APPROVED",
            //Ingrese aquí el identificador del pagador.
            PayUParameters::PAYER_ID => "10",
            //Ingrese aquí el documento de identificación del comprador.
            PayUParameters::PAYER_DNI => "32144457",
            //Ingrese aquí el número de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_NUMBER => "4111111111111111",
            //Ingrese aquí la fecha de vencimiento de la tarjeta de crédito
            PayUParameters::CREDIT_CARD_EXPIRATION_DATE => "2020/10",
            //Ingrese aquí el nombre de la tarjeta de crédito
            PayUParameters::PAYMENT_METHOD => "VISA"
        );
            
        $response = PayUTokens::create($parameters);   
        if($response){
            //podrás obtener el token de la tarjeta
            $response->creditCardToken->creditCardTokenId;
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }
    }

    public function test_token_payu($token)
    {
        $this->init_payu();
        $string = bin2hex(random_bytes(10));
        $reference = "payment_$string";
        $value = "10000";
        $deviceSessionId = md5(session_id() . microtime());

        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            PayUParameters::ACCOUNT_ID => "512321",
            //Ingrese aquí el código de referencia.
            PayUParameters::REFERENCE_CODE => $reference,
            //Ingrese aquí la descripción.
            PayUParameters::DESCRIPTION => "payment test",
            
            // -- Valores --
            //Ingrese aquí el valor.        
            PayUParameters::VALUE => $value,
            //Ingrese aquí la moneda.
            PayUParameters::CURRENCY => "COP",
            
            // -- Comprador 
            //Ingrese aquí el nombre del comprador.
            PayUParameters::BUYER_NAME => "First name and second buyer name",
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
            
            //DATOS DEL TOKEN
            PayUParameters::TOKEN_ID => "$token",
            
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
            PayUParameters::PAYER_COOKIE=> session_id(),
            //Cookie de la sesión actual.        
            PayUParameters::USER_AGENT=>"Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0"
        );
            
        $response = PayUPayments::doAuthorizationAndCapture($parameters);

        if ($response) {
            $response->transactionResponse->orderId;
            $response->transactionResponse->transactionId;
            $response->transactionResponse->state;
            if ($response->transactionResponse->state=="PENDING") {
                $response->transactionResponse->pendingReason;	
            }
            //$response->transactionResponse->paymentNetworkResponseCode;
            //$response->transactionResponse->paymentNetworkResponseErrorMessage;
            //$response->transactionResponse->trazabilityCode;
            $response->transactionResponse->responseCode;
            //$response->transactionResponse->responseMessage;

            echo "<pre>";
            print_r($response->transactionResponse);
            echo "</pre>";
   	
        }
    }

    public function test_payu($value, $currency)
    {
        $this->init_payu();
        $reference = "payment_test_00000097";
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
            //PayUParameters::TAX_VALUE => "", //"3193",
            //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
            //En caso de que no tenga IVA debe enviarse en 0.
            //PayUParameters::TAX_RETURN_BASE => "", //"16806",

            //Ingrese aquí la moneda.
            PayUParameters::CURRENCY => $currency,

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
            PayUParameters::CREDIT_CARD_EXPIRATION_DATE => "2020/12",
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

    public function trans_payu($transactionId)
    {
        //Esta funcion devuelve los mismos datos que se obtienen al hacer un pago
        //history tran: 788ddc83-cf24-427f-b68a-5be75c5befa5
        $this->init_payu();
        $parameters = array(PayUParameters::TRANSACTION_ID => "$transactionId");

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



}