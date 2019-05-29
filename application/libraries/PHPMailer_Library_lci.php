<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PHPMailer\PHPMailer\POP3;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;

use League\OAuth2\Client\Provider\Google;
// @see https://packagist.org/packages/hayageek/oauth2-yahoo
use Hayageek\OAuth2\Client\Provider\Yahoo;
// @see https://github.com/stevenmaguire/oauth2-microsoft
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;


require (APPPATH. 'vendor/autoload.php');

class PHPMailer_Library_lci extends PHPMailer
{
    public function __construct()
    {
        //log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function createPHPMailer() {
      /**
  		 * This example shows how to send via Google's Gmail servers using XOAUTH2 authentication.
  		 */
  		//Import PHPMailer classes into the global namespace
  		//use PHPMailer\PHPMailer\PHPMailer;
  		//use PHPMailer\PHPMailer\OAuth;
  		// Alias the League Google OAuth2 provider class
  		//use League\OAuth2\Client\Provider\Google;
  		//SMTP needs accurate times, and the PHP time zone MUST be set
  		//This should be done in your php.ini, but this is how to do it if you don't have access to that
  		date_default_timezone_set('Etc/UTC');
      $mail = new PHPMailer();
      //$mail = new PHPMailer;
  		//Tell PHPMailer to use SMTP
  		$mail->isSMTP();
  		//Enable SMTP debugging
  		// 0 = off (for production use)
  		// 1 = client messages
  		// 2 = client and server messages
  		$mail->SMTPDebug = 2;
  		//Set the hostname of the mail server
  		$mail->Host = 'smtp.gmail.com';
  		//$mail->Host = 'mail.codemar.net';
  		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
  		//$mail->Port = 465;
  		$mail->Port = 587;
  		//Set the encryption system to use - ssl (deprecated) or tls
  		$mail->SMTPSecure = 'tls';
  		//Whether to use SMTP authentication
  		$mail->SMTPAuth = true;
  		//Set AuthType to use XOAUTH2
  		//$mail->AuthType = 'XOAUTH2';
  		//Fill in authentication details here
  		//Either the gmail account owner, or the user that gave consent
  		//$email = 'jorgefloresu@gmail.com';
  		//$clientId = '321222775646-88u7c7jepn1j046k086qv2r1pfmk75pg.apps.googleusercontent.com';
  		//$clientId = '987744642536-gvtpepf2mv4us93dh0njve8kpge4jopc.apps.googleusercontent.com';
  		//$clientSecret = '_Qf71uR0GIB1gSJS8ItVLeDf';
  		//$clientSecret = 'F8NcctssCSaGnPzWAu9bXR6j';
  		//Obtained by configuring and running get_oauth_token.php
  		//after setting up an app in Google Developer Console.
  		//$refreshToken = '1/vE2XEIgjcAEoUJW4LPrjW27xlrJIa9sf3aTZnadDmz4';
  		//Create a new OAuth2 provider instance
  		//$provider = $this->createGoogle($clientId, $clientSecret);
  		//Pass the OAuth provider instance to PHPMailer
  		//$mail->setOAuth(
  		//    $this->createOauth($provider,$clientId,$clientSecret,$refreshToken,$email)
  		//);
  		//Set who the message is to be sent from
  		//For gmail, this generally needs to be the same as the user you logged in as
  		//$mail->setFrom($email, 'Jorge Flores');
		$mail->Username = 'gerencia@latincolorimages.com';
		$mail->Password = 'Latin215001$';
		$mail->setFrom('gerencia@latincolorimages.com','Latin Color Images');

      return $mail;
    }

    public function createGoogle($clientId, $clientSecret) {
      return new Google(
        [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret
        ]
      );
    }

    public function createOauth($provider, $clientId, $clientSecret, $refreshToken, $email) {
      return new OAuth(
          [
              'provider' => $provider,
              'clientId' => $clientId,
              'clientSecret' => $clientSecret,
              'refreshToken' => $refreshToken,
              'userName' => $email
          ]
      );
    }

    public function load()
    {
      //$objMail = new PHPMailer();
      //return $objMail;


      if (!isset($_GET['code']) && !isset($_GET['provider'])) {
      ?>
      <html>
      <body>Select Provider:<br/>
      <a href='?provider=Google'>Google</a><br/>
      <a href='?provider=Yahoo'>Yahoo</a><br/>
      <a href='?provider=Microsoft'>Microsoft/Outlook/Hotmail/Live/Office365</a><br/>
      </body>
      </html>
      <?php
      exit;
      }

      //session_start();

      $providerName = '';

      if (array_key_exists('provider', $_GET)) {
          $providerName = $_GET['provider'];
          $_SESSION['provider'] = $providerName;
      } elseif (array_key_exists('provider', $_SESSION)) {
          $providerName = $_SESSION['provider'];
      }
      if (!in_array($providerName, ['Google', 'Microsoft', 'Yahoo'])) {
          exit('Only Google, Microsoft and Yahoo OAuth2 providers are currently supported in this script.');
      }

      //These details are obtained by setting up an app in the Google developer console,
      //or whichever provider you're using.
      $clientId = '987744642536-gvtpepf2mv4us93dh0njve8kpge4jopc.apps.googleusercontent.com';
      $clientSecret = 'F8NcctssCSaGnPzWAu9bXR6j';

      //If this automatic URL doesn't work, set it yourself manually to the URL of this script
      $redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
      //$redirectUri = 'http://localhost/PHPMailer/redirect';

      $params = [
          'clientId' => $clientId,
          'clientSecret' => $clientSecret,
          'redirectUri' => $redirectUri,
          'accessType' => 'offline'
      ];

      $options = [];
      $provider = null;

      switch ($providerName) {
          case 'Google':
              $provider = new Google($params);
              $options = [
                  'scope' => [
                      'https://mail.google.com/'
                  ]
              ];
              break;
          case 'Yahoo':
              $provider = new Yahoo($params);
              break;
          case 'Microsoft':
              $provider = new Microsoft($params);
              $options = [
                  'scope' => [
                      'wl.imap',
                      'wl.offline_access'
                  ]
              ];
              break;
      }

      if (null === $provider) {
          exit('Provider missing');
      }

      if (!isset($_GET['code'])) {
          // If we don't have an authorization code then get one
          $authUrl = $provider->getAuthorizationUrl($options);
          $_SESSION['oauth2state'] = $provider->getState();
          header('Location: ' . $authUrl);
          exit;
      // Check given state against previously stored one to mitigate CSRF attack
      } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
          unset($_SESSION['oauth2state']);
          unset($_SESSION['provider']);
          exit('Invalid state');
      } else {
          unset($_SESSION['provider']);
          // Try to get an access token (using the authorization code grant)
          $token = $provider->getAccessToken(
              'authorization_code',
              [
                  'code' => $_GET['code']
              ]
          );
          // Use this to interact with an API on the users behalf
          // Use this to get a new access token if the old one expires
          echo 'Refresh Token: ', $token->getRefreshToken();
      }

    }


}
