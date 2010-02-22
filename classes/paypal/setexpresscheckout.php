<?php defined('SYSPATH') or die('No direct script access.');
class Paypal_SetExpressCheckout extends PayPal{
	public function set(array $params = NULL)
	{
		if ($params === NULL)
		{
			// Use the default parameters
			$params = $this->_default;
		}
		else
		{
			// Add the default parameters
			$params += $this->_default;
		}

		$temp = $this->_process('SetExpressCheckout', $params);
                if(strpos(strtolower($temp['ACK']), "success") !== FALSE):
                        Request::instance()->redirect($this->redirect_url($temp['TOKEN']));
                else:
                    $this->_error($payment);
                endif;
	}
        
	private function redirect_url($token)
	{
		$params = array('cmd' => '_express-checkout',"token"=>$token);
		if (strtolower($this->_environment) == 'sandbox'):
                    $url = 'https://www.sandbox.paypal.com/webscr?'.http_build_query($params);
		else:
                    $url = 'https://www.paypal.com/webscr?'.http_build_query($params);
                endif;
		return $url;
	}            
}
