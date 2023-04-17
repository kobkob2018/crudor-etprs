<?php
  class Biz_requestsController extends CrudController{
    public $add_models = array('biz_categories','masterBiz_requests');


    
    public function list(){
        
        $filter_str = array(
            'limit_count'=>$this->get_request_filter_param('limit_count','0'),
            'status'=>$this->get_request_filter_param('status','0'),
            'date_s'=>$this->get_request_filter_param('date_s', $this->get_default_date_s()),
            'date_e'=>$this->get_request_filter_param('date_e'),
            'referrer'=>$this->get_request_filter_param('referrer'),
            'ip'=>$this->get_request_filter_param('ip'),
            'free'=>$this->get_request_filter_param('free'),
            'filter_campaign_types'=>$this->get_request_filter_param('filter_campaign_types','0'),
            'campaign_types'=>$this->get_request_filter_param('campaign_types',array()),
        );

        $filter = array(
            'limit_count'=>$filter_str['limit_count'],
            'status'=>$filter_str['status'],
            'date_s'=>$this->get_en_date($filter_str['date_s']),
            'date_e'=>$this->get_en_date($filter_str['date_e']),
            'referrer'=>$filter_str['referrer'],
            'ip'=>$filter_str['ip'],
            'free'=>$filter_str['free'],
            'filter_campaign_types'=>$filter_str['filter_campaign_types'],
            'campaign_types'=>$filter_str['campaign_types'],
        );

        $filter_campaign_types_checked = "";
        if($filter['filter_campaign_types']){
            $filter_campaign_types_checked = "checked";
        }

        $campaign_types_checkboxes = array(
            '0'=>array(
                'label'=>'ללא',
                'value'=>'0',
                'checked_str'=>'',
            ),
            '1'=>array(
                'label'=>'פייסבוק',
                'value'=>'1',
                'checked_str'=>'',
            ),
            '2'=>array(
                'label'=>'גוגל',
                'value'=>'2',
                'checked_str'=>'',
            ),            
        );

        foreach($filter['campaign_types'] as $campaign_key=>$checked){
            $campaign_types_checkboxes[$campaign_key]['checked_str'] = "checked";
        }

        $status_options = array(
            'all'=>array(
                'label'=>'הכל',
                'value'=>'all',
                'selected_str'=>''
            ),
            '0'=>array(
                'label'=>'הצעות חדשות',
                'value'=>'0',
                'selected_str'=>''
            ),
            '1'=>array(
                'label'=>'הצעות בטיפול',
                'value'=>'1',
                'selected_str'=>''
            ),
            '2'=>array(
                'label'=>'הצעות שמחכות',
                'value'=>'2',
                'selected_str'=>''
            ),
            '3'=>array(
                'label'=>'הצעות שנשלחו',
                'value'=>'3',
                'selected_str'=>''
            ),
            '4'=>array(
                'label'=>'הצעות לא רלוונטיות',
                'value'=>'4',
                'selected_str'=>''
            ),
            '5'=>array(
                'label'=>'הצעות מחוקות',
                'value'=>'5',
                'selected_str'=>''
            ),
        );

        if($filter['status'] == '' || !isset($status_options[$filter['status']])){
            $status_options['all']['selected_str'] = 'selected';
        }
        else{
            $status_options[$filter['status']]['selected_str'] = 'selected';
        }

        $referrer_options_arr = MasterBiz_requests::get_referrer_options($filter);
        $referrer_options = array();
        foreach($referrer_options_arr as $site_ref){
            $referrer_options[$site_ref] = array(
                'label'=>$site_ref,
                'value'=>$site_ref,
                'selected_str'=>''
            );
        }

        if($filter['referrer'] != ''){
            if(isset($referrer_options[$filter['referrer']])){
                $referrer_options[$filter['referrer']]['selected_str'] = 'selected';
            }
        }

        $biz_requests = MasterBiz_requests::get_request_list($filter);

        $info = array(
            'filter'=>$filter,
            'filter_str'=>$filter_str,
            'status_options'=>$status_options,
            'referrer_options'=>$referrer_options,
            'biz_requests'=>$biz_requests,
            'campaign_types_checked'=>$filter_campaign_types_checked,
            'campaign_types_checkboxes'=>$campaign_types_checkboxes
        );
        $this->include_view('biz_requests/list.php',$info);
    }

    protected function get_request_filter_param($param_name,$default_val = ""){
        if(isset($_REQUEST['filter']) && isset($_REQUEST['filter'][$param_name])){
            return $_REQUEST['filter'][$param_name];
        }
        return $default_val;
    }

    protected function get_en_date($heb_date){
        if(!$heb_date){
            return "";
        }
        $date = DateTime::createFromFormat("d-m-Y",$heb_date);
        return $date->format('Y-m-d');
    }

    protected function get_default_date_s(){
        return date('d-m-Y', strtotime('-30 days'));
    }

  }
?>