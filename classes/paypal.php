<?php defined('SYSPATH') or die('No direct script access.');
abstract class PayPal {

        # @type SetExpressCheckout,DoExpressCheckout, DoDirectPayment ...
	public static function factory($type)
	{
                $class = "Paypal_" . $type;
		return new $class;
	}

	// API username
	protected $_username;
	// API password
	protected $_password;
	// API signature
	protected $_signature;
	// Environment type
	protected $_environment;
        // endpoint url
        protected $_endpoint;
        // version
        protected $_version;

	protected $_default = array(
		'PAYMENTACTION' => 'Sale',
	);

	public function __construct()
	{
                $config = Kohana::config("paypal");
		$this->_username = $config['username'];
		$this->_password = $config['password'];
		$this->_signature = $config['signature'];
		$this->_environment = $config['environment'];
		$this->_version = $config['version'];
                
                if(strtolower($this->_environment) == "live"):
                    $env = "";
                else:
                    $env = strtolower($this->_environment) . ".";
                endif;

                $this->_endpoint = "https://api-3t.{$env}paypal.com/nvp";
	}

	protected function _process($method, array $params)
	{
            $post = array(
                    'METHOD'    => $method,
                    'VERSION'   => $this->_version,
                    'USER'      => $this->_username,
                    'PWD'       => $this->_password,
                    'SIGNATURE' => $this->_signature,
            ) + $params;

            //setting the curl parametersERR_EMPTY_RESPONSE.
            $ch = curl_init($this->_endpoint);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);

            //turning off the server and peer verification(TrustManager Concept).
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($post));

            //getting response from server
            $response = curl_exec($ch);

            if ($response  === FALSE)
            {
                    // Get the error code and message
                    $code  = curl_errno($ch);
                    $error = curl_error($ch);
                    throw new Kohana_Exception('PayPal API request for :method failed: :error (:code)',
                            array(':method' => $method, ':error' => $error, ':code' => $code));
            }
            // Close curl
            curl_close($ch);
            // Parse the response
            parse_str($response, $data);
            return $data;
	}

        protected function _error($val){
            throw new Kohana_Exception('Error: :message',array(':message'=>$val['L_LONGMESSAGE0']));
        }
} // End PayPal