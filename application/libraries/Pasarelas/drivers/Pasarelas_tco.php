<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasarelas_tco extends CI_Driver {

	function __construct() {
        require_once APPPATH . 'libraries/2CO/Twocheckout.php';
    }

    public function init_tco()
    {
        Twocheckout::privateKey('04327A71-E9DD-4350-9520-A09680D20C5D');
        Twocheckout::sellerId('901331385');
        Twocheckout::sandbox(true);
    }

    public function TCO_pay($pay)
    {
        $this->init_tco();

        try {
            $charge = Twocheckout_Charge::auth($pay, 'array');
        } catch (Twocheckout_Error $e) {
            //$this->assertEquals('Bad request - parameter error', $e->getMessage());
            return $e->getMessage();
            //echo json_encode($charge);
        }
        return $charge;
    }

    /*
    *** TEST ****
    */

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