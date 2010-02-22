<?php defined('SYSPATH') or die('No direct script access.');
class Paypal_DoExpressCheckout extends PayPal{
	public function set(array $params,$amt)
	{
                $params += $this->_default;
		$details = $this->_process('GetExpressCheckoutDetails', $params);
                if(strpos(strtolower($details['ACK']), "success") !== FALSE):
                        $payment = $this->_process("DoExpressCheckoutPayment", $this->_default + $details + array("AMT"=>$amt));
                        if(strpos(strtolower($payment['ACK']), "success") !== FALSE):
                            return $payment;
                        else:
                            $this->_error($payment);
                        endif;
                else:
                    $this->_error($details);
                endif;
	}
}