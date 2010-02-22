<?php defined('SYSPATH') or die('No direct script access.');
class Paypal_RecurringPayment extends PayPal{
	public function create(array $params)
	{
                $payment = $this->_process('CreateRecurringPaymentsProfile', $params);
                if(strpos(strtolower($payment['ACK']), "success") !== FALSE):
                    return $payment;
                else:
                    $this->_error($payment);
                endif;
	}
        public function manage($id,$action="Cancel"){
                $tmp = array("PROFILEID"=>$id,"ACTION"=>$action);
                $payment = $this->_process('ManageRecurringPaymentsProfileStatus', $tmp);
                if(strpos(strtolower($payment['ACK']), "success") !== FALSE):
                    return $payment;
                else:
                    $this->_error($payment);
                endif;
        }
        public function profile($id){
                $tmp = array("PROFILEID"=>$id);
                $payment = $this->_process('GetRecurringPaymentsProfileDetails', $tmp);
                if(strpos(strtolower($payment['ACK']), "success") !== FALSE):
                    return $payment;
                else:
                    $this->_error($payment);
                endif;
        }
}
