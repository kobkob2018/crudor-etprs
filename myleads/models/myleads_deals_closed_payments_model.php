<?php
  class myleads_deals_closed_payments extends TableModel{

    protected static $main_table = 'user_custom_paylist';

    public static $fields_collection = array(

        'description'=>array(
            'label'=>'תיאור התשלום',
            'type'=>'textbox',
            'validation'=>'required'
        ),
        'amount'=>array(
            'label'=>'סכום',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        )

    );

    public static function get_user_payments_data($user_id){

        $deals_closed_sum = 0;

        $deals_closed_sum_data = self::simple_find_by_table_name(
            array('user_id'=>$user_id),
            'user_leads',
            "sum(offer_amount) as sum_total "
        );

        if($deals_closed_sum_data && $deals_closed_sum_data['sum_total']!=''){
			$deals_closed_sum = $deals_closed_sum_data['sum_total'];
        }		
		
		$user_pay_total= 0;

        $user_pay_total_data = self::simple_find_by_table_name(
            array('user_id'=>$user_id,'pay_type'=>'deals_closed'),
			'user_custom_paylist',
            "sum(amount) as sum_total "
        );

        if($user_pay_total_data && $user_pay_total_data['sum_total']!=''){
				$user_pay_total = $user_pay_total_data['sum_total'];
        }
		
		$deals_closed_price = '0';
		
		$deals_closed_price_data = self::simple_find_by_table_name(array('user_id'=>$user_id),'user_bookkeeping','dealClosedPrice');
        
		if($deals_closed_price_data  && $deals_closed_price_data['dealClosedPrice']!=''){
			$deals_closed_price = $deals_closed_price_data['dealClosedPrice'];
		}
		
		$user_bill = 0;
		
		if($deals_closed_sum != '0' && $deals_closed_price != '0'){
			$user_bill = (floatval($deals_closed_sum)) * (floatval($deals_closed_price)) / 100;
		}
		
		$user_still_bill = $user_bill - $user_pay_total;
		
		$user_payments = self::get_list(
			array(            
			'user_id'=>$user_id,
            'pay_type'=>'deals_closed'
			)
		);
		
		if(!$user_payments){
			$user_payments = array();
		}
		
		return array(
			'payments'=>$user_payments,
			'deals_closed_sum'=>$deals_closed_sum,
			'deals_closed_price'=>$deals_closed_price,
			'bill'=>$user_bill,
			'pay_total'=>$user_pay_total,
			'still_bill'=>$user_still_bill,			
		);
		
    }

}
?>