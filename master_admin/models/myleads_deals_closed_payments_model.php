<?php
  class Myleads_deals_closed_payments extends TableModel{

    protected static $main_table = 'user_custom_paylist';

    public static $fields_collection = array(

        'display'=>array(
            'label'=>'הצג לפי שעות',
            'type'=>'select',
            'default'=>'0',
            'select_blank'=>false,
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'css_class'=>'display-toggle'
        ),
        'time_groups'=>array(
            'label'=>'שעות',
            'type'=>'build_method',
            'build_method'=>'build_time_groups',
            'default'=>'0'
        ), 

    );

    public static function get_user_payments_data($user_id){

        

        $deals_closed_sum = 0;

        $deals_closed_sum_data = self::simple_get_list_by_table_name(
            array('user_id'=>$user_id),
            'user_leads',
            "sum(offer_amount)"
        );

        if($deals_closed_sum_data){
            var_dump($deals_closed_sum_data);
        }
        exit("good job");

        $db = Db::getInstance();

        $filter_arr = array(
            'user_id'=>$user_id,
            'pay_type'=>'deals_closed'
        );
        $user_payments = self::get_list($filter_arr);
        $filter_sql_arr = self::simple_get_filter_sql($filter_arr);
        $filter_sql = $filter_sql_arr['fields_sql'];
        $execute_arr = $filter_sql_arr['execute_arr'];
        $sum_sql = "SELECT SUM(amount) as pay_total FROM user_custom_paylist WHERE $filter_sql";
        		
        $req = $db->prepare($sum_sql);
        $req->execute($execute_arr);

        $result = $req->fetch();
        $pay_total = 0;
        if($result){
            $pay_total = $result['pay_total'];
        }

        $deals_closed_sum = self::simple_get_list_by_table_name(
            array('user_id'=>$user_id),
            'user_leads',
            "sum(offer_amount)"
        );
        
        
    }

}
?>