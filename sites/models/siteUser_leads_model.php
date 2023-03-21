<?php
  class SiteUser_leads extends TableModel{

    protected static $main_table = 'user_leads';

    public static function add_user_lead($lead_info,$user){
        $lead_fixed_values = array(
            'user_id'=>$user['info']['id'],
            'full_name'=>$lead_info['full_name'],
            'email'=>$lead_info['full_name'],
            'phone'=>$lead_info['phone'],
            'note'=>$lead_info['note'],
            'extra'=>$lead_info['extra_info'],
            'open_state'=>$lead_info['open_state'],
            'request_id'=>$lead_info['request_id'],
            'token'=>$lead_info['token'],
            'send_state'=>$lead_info['send_state'],
            'resource'=>$lead_info['resource'],
            'billed'=>$lead_info['billed'],
        );
        return self::create($lead_fixed_values);
    }
}
?>