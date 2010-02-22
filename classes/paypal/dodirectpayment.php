<?php defined('SYSPATH') or die('No direct script access.');
class Paypal_DoDirectPayment extends PayPal{
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

                $payment = $this->_process('DoDirectPayment', $params);
                if(strpos(strtolower($payment['ACK']), "success") !== FALSE):
                    return $payment;
                else:
                    $this->_error($payment);
                endif;
	}
}
