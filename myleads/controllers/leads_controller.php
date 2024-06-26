<?php
  class LeadsController extends CrudController{
	public $add_models = array("leads","tags","leads_user","biz_categories");
    public function list() {
		$this->set_layout('leads_layout');
		$this->set_body('leads_body');
		return $this->leadList();
    }

	protected function handle_access($action){
		switch ($action){
		  case 'auth':
			return true;
			break;
		  default:
			return parent::handle_access(($action));
			break;
		  
		}
	}

    public function leadList() {
		$user = Users::get_loged_in_user();
		$user = Leads_user::get_leads_user_data($user);
		
		if($user['active'] == '0'){
			echo "User have no leads view";
			return;
		}
      	if(isset($_REQUEST['recordings_login']) && isset($_REQUEST['recording_pass'])){
        
        

        if($user['lead_visability']['records_password'] == $_REQUEST['records_password']){
          session__set('recordings_pass','1');
          session__set('show_row',$_REQUEST['row_id']);
          $this->redirect_to(inner_url('leads/list/'));
          return;
        }
      }
      

      
      $filter = $this->get_filter();
      
      // we store all the posts in a variable
      $leads_data = Leads::all($filter);
      $this->data['filter'] = $filter;
      $leads = $leads_data['list'];
      $pages_data = $leads_data['pages_data'];

	  $this->data['pages'] = $pages_data;
	  $this->data['leads'] = $leads;
      $show_row = false;
      
      if(session__isset('show_row')){
        $show_row = session__get('show_row');
        session__unset('show_row');
      }
	  $this->data['show_row'] = $show_row;
      //include('views/leads/all.php');
      $this->include_view('leads/list.php');
    }

    public function ajax_list() {
		$filter = $this->get_filter();
		$leads_data = Leads::all($filter);
		$leads_data['filter'] = $filter;
		
		$this->print_json_page($leads_data);
    }

	public function report(){
		$this->set_layout('blank');
		$filter = $this->get_filter();		
		$filter['leads_in_page'] = 'all';
		$leads_data = Leads::all($filter);
		$report_arr = array();
		$suming_arr = array(
			'sent'=>0,
			'closed'=>0,
			'open'=>0,
			'billed'=>0,
			'not_billed'=>0,
			'doubled'=>0,
			'forms'=>0,
			'phones'=>0,
		);

		$report_arr[] = array("דוח לידים");
		$report_arr[] = array("מתאריך",$filter['date_from_str'],"עד תאריך",$filter['date_to_str']);
		$report_arr[] = array("קטגוריה",$filter['cat_options_by_id'][$filter['cat']]['label']);
		$status_arr = array("סטטוסים");
		foreach($filter['status'] as $status){
			$status_arr[] = $filter['status_options'][$status]['str'];
		}
		if(count($status_arr) == '1'){
			$status_arr[] = "הכל";
		}
		$report_arr[] = $status_arr;
		$tag_arr = array("תיוגים");
		foreach($filter['tag'] as $tag){
			$tag_arr[] = $filter['tag_options'][$tag]['str'];
		}
		if(count($tag_arr) == '1'){
			$tag_arr[] = "הכל";
		}
		
		$report_arr[] = $tag_arr;
		if($filter['free']!=""){
			$report_arr[] = array("חיפוש חופשי",$filter['free']);
		}
		if($filter['deleted'] !=""){
			$report_arr[] = array("הוספת מחוקים");
		}
		else{
			$report_arr[] = array("ללא מחוקים");
		}
		$report_arr[] = array(		
				"#",
				"קטגוריה",
				"שם",
				"טלפון",
				"זמן שליחה",
				"סטטוס",
				"תיוג",
				"מצב חיוב",
				"סיבת ביטול",
				"אימייל",
				"הערות",		
		);
		foreach($leads_data['list'] as $lead_data){
			$lead = $lead_data->estimate_form_data;
			$suming_arr['sent']++;
			if($lead['resource']!= 'phone'){
				$suming_arr['forms']++;
			}
			else{
				$suming_arr['phones']++;
			}
			$bill_state_str = "חוייב";
			if($lead['open_state'] == '0'){
				$suming_arr['not_billed']++;
				$suming_arr['closed']++;
				$bill_state_str = "ליד סגור - לא חוייב";
				$bill_state = "closed";
			}
			else{
				$suming_arr['open']++;
				if($lead['billed'] != '1'){
					$suming_arr['not_billed']++;
					$bill_state_str = 'לא חוייב';
					$bill_state = "not_billed";
					if($lead['duplicate_id'] != ''){
						$suming_arr['doubled']++;
						$bill_state = "doubled";
						$bill_state_str = 'ליד כפול - לא חוייב';
					}			
				}
				else{
					$suming_arr['billed']++;
				}
			}
			$report_arr[] = array(
				$lead['row_id'],
				$lead['full_cat_name'],
				$lead['full_name'],
				$lead['phone'],
				$lead['date_in_str'],
				$lead['status_str'],
				$lead['tag_str'],
				$bill_state_str,
				$lead['refund_reason_str'],
				$lead['email'],
				$lead['note_full'],
			);
		}
		$report_arr[] = array("סיכום");
		$report_arr[] = array(
			'נשלחו',
			'מצב סגור',
			'מצב פתוח',
			'חוייבו',
			'לא חוייבו',
			'לידים כפולים (לא חוייבו)',
			'נשלחו מטופס',
			'שיחות טלפון',
		);
		
		$report_arr[] = array(
			$suming_arr['sent'],
			$suming_arr['closed'],
			$suming_arr['open'],
			$suming_arr['billed'],
			$suming_arr['not_billed'],
			$suming_arr['doubled'],
			$suming_arr['forms'],
			$suming_arr['phones'],
		);
		
		if(isset($_GET['check'])){
			$td_count = 0;
			foreach($report_arr as $arr){
				if(count($arr) > $td_count){
					$td_count = count($arr);
				}
			}
			
			echo "<table border='1'>";
				foreach($report_arr as $arr){
					echo "<tr>";
					$arr_count = 0;
					foreach($arr as $td){
						echo "<td>".$td."</td>";
						$arr_count++;
					}
					$td_left = $td_count - $arr_count;
					if($td_left > 0){
						echo "<td colspan='".$td_left."'></td>";
					}
					echo "</tr>";
				}
			echo "</table>";		
			return;		
		}

		return Helper::array_to_csv_download($report_arr,"leads_report.csv");
		
	}

    public function ajax_lead_data() {
		$lead_id = $_REQUEST['lead_id'];
		$this->print_json_page(array("lead"=>Leads::find($lead_id)));
    }
    public function ajax_lead_delete() {
		$lead_id = $_REQUEST['lead_id'];
		$this->print_json_page(array("lead"=>Leads::delete_lead($lead_id)));
    }	
    public function ajax_lead_update() {
		$lead_id = $_REQUEST['lead_id'];
		$data_arr = array();
		foreach($_REQUEST['data_arr'] as $key=>$val){
			$data_arr[$key]=$val;
		}
		$this->print_json_page(array("lead"=>Leads::update_lead($lead_id,$data_arr)));
    }
    public function ajax_send_lead_refund_request() {
		$lead_id = $_REQUEST['lead_id'];
		$data_arr = array();
		$data_arr['reason']=$_REQUEST['request_reason'];
		$data_arr['comment']=$_REQUEST['comment'];
		$this->print_json_page(array("lead"=>Leads::send_lead_refund_request($lead_id,$data_arr)));
    }	
		
    public function ajax_lead_buy() {
		$lead_id = $_REQUEST['lead_id'];
		$return_array = Leads::buy_lead($lead_id);
		$this->print_json_page($return_array);
    }		
    public function show() {
      // we expect a url of form ?controller=posts&action=show&id=x
      // without an id we just redirect to the error page as we need the post id to find it in the database
      if (!isset($_GET['id']))
        return call('pages', 'error');

      // we use the given id to get the right post
      $lead = Leads::find($_GET['id']);
      include('views/leads/show.php');
    }
	public function get_filter(){
		$user = Users::get_loged_in_user();
		$user_id = $user['id'];
		$period_keys = array("today","yesterday","current_month","previous_month","previous_quarter","current_quarter","all_time","custom");
		$period_options = array();
		foreach($period_keys as $key){
			$period_options[$key] = $this->get_filter_period_dates($key);
		}
		$filter = array(
			"date_from"=>$period_options['all_time']['start'],
			"date_to"=>$period_options['all_time']['end'],
			"date_from_str"=>"",
			"date_to_str"=>"",			
			"free"=>"",
			"status"=>array('0','5','1'),
			"tag"=>array(),
			"deleted"=>"",
			"period"=>"all_time",
			"period_text_class"=>"all_time",
			"leads_in_page"=>"20",
			"period_options"=>$period_options,
			"cat"=>'0',
		);
		
		if(isset($_REQUEST['leads_filter'])){
			$filter['request'] = $_REQUEST['leads_filter'];
			foreach($_REQUEST['leads_filter'] as $filter_key=>$filter_val){
				$filter[$filter_key] = $filter_val;
			}
			session__set('leads_filter',$filter);
		}
		elseif(session__isset('leads_filter')){
			foreach(session__get('leads_filter') as $filter_key=>$filter_val){
				$filter[$filter_key] = $filter_val;
			}
		}	

		if($filter['period'] != "custom" || $filter['date_from'] == ""){
			$filter['date_from'] = $filter['period_options'][$filter['period']]['start'];
			$filter['date_to'] = $filter['period_options'][$filter['period']]['end'];
		}
		$filter['date_from'] = str_replace("/","-",$filter['date_from']);
		$filter['date_to'] = str_replace("/","-",$filter['date_to']);	
		$filter['period_str'] = $filter['period_options'][$filter['period']]['str'];
		$filter['period_text_class'] = $filter['period'];
		$filter['date_from_str'] = str_replace("-","/",$filter['date_from']);
		$filter['date_to_str'] = str_replace("-","/",$filter['date_to']);	
		if($filter['period'] == "custom"){
			$filter['period_str'] = $filter['date_from_str']." - ".$filter['date_to_str'];
		}		
		$filter['period_options'][$filter['period']]['selected'] = 'selected';

		$filter['status_options'] = array(
			'0'=>array('selected'=>'','str'=>'מתעניין חדש','id'=>'0'),
			'5'=>array('selected'=>'','str'=>'מחכה לטלפון','id'=>'5'),
			'1'=>array('selected'=>'','str'=>'נוצר קשר','id'=>'1'),
			'2'=>array('selected'=>'','str'=>'סגירה עם לקוח','id'=>'2'),
			'3'=>array('selected'=>'','str'=>'לקוח רשום','id'=>'3'),
			'4'=>array('selected'=>'','str'=>'לא רלוונטי','id'=>'4'),
			'6'=>array('selected'=>'','str'=>'הליד זוכה','id'=>'5'),
		);
		if(isset($filter['status'])){
			foreach($filter['status'] as $option_key){
				if(isset($filter['status_options'][$option_key])){
					$filter['status_options'][$option_key]['selected'] = 'selected';
				}
			}
		}
		$tag_options =  Tags::get_user_tag_list();
		$filter['tag_options'] = array();
		foreach($tag_options  as $tag_id=>$tag){
			$filter['tag_options'][$tag_id] = array('selected'=>'','tag'=>$tag,'id'=>$tag_id);
		}
		if(isset($filter['tag'])){
			foreach($filter['tag'] as $option_key){
				if(isset($filter['tag_options'][$option_key])){
					$filter['tag_options'][$option_key]['selected'] = 'selected';
				}
			}
		}
		$cat_options = Leads_user::get_user_cat_options($user_id);

		
		if(!isset($filter['cat'])){
			$filter['cat'] = '0';
		}
		foreach($cat_options as $cat_key=>$cat){
			if($filter['cat'] == $cat['id']){
				$cat_options[$cat_key]['selected'] = 'selected';
			}
		}
		$filter['cat_options'] = $cat_options;
		$filter['cat_options_by_id'] = array();
		foreach($filter['cat_options'] as $option){
			$filter['cat_options_by_id'][$option['id']] = $option;
		}

		$filter['pagination_options'] = array(
			'10'=>array('selected'=>'','str'=>'10 שורות'),
			'20'=>array('selected'=>'','str'=>'20 שורות'),
			'50'=>array('selected'=>'','str'=>'50 שורות'),
			'100'=>array('selected'=>'','str'=>'100 שורות'),
		);	
			

		
		$filter['page_num'] = 1;
		if(isset($_REQUEST['page'])){
			$filter['page_num'] = $_REQUEST['page'];
		}
		return $filter;
	}

  public function resetfilter() {
      session__unset('leads_filter');
		  return $this->redirect_to(inner_url("leads/list/"));
	}

	private function get_quarter($i=0){
		$y = date('Y');
		$m = date('m');
		$str = "רבעון נוכחי";
		if($i == 1){
			$str = "רבעון קודם";
			for($x = 0; $x < $i; $x++){
				if($m <= 3) { $y--; }
				$diff = $m % 3;
				$m = ($diff > 0) ? $m - $diff:$m-3;
				if($m == 0) { $m = 12; }
			}
		}
		switch($m) {
			case $m >= 1 && $m <= 3:
				$start = '01-01-'.$y;
				$end = '31-03-'.$y;
				break;
			case $m >= 4 && $m <= 6:
				$start = '01-04-'.$y;
				$end = '30-06'.$y;
				break;
			case $m >= 7 && $m <= 9:
				$start = '01-07-'.$y;
				$end = '30-09-'.$y;
				break;
			case $m >= 10 && $m <= 12:
				$start = '01-10-'.$y;
				$end = '31-12-'.$y;
					break;
		}
		return array(
			'start' => $start,
			'end' => $end,
		);
	}
	
	private function get_filter_period_dates($selected="today"){
		switch($selected) {
			case "today":
				$str = "היום";
				$today = date('d-m-Y');
				$start = $today;
				$end = $today;
			break;
			case "yesterday":
				$str = "אתמול";
				$yesterday = date('d-m-Y',strtotime("-1 days"));
				$start = $yesterday;
				$end = $yesterday;
			break;
			case "current_month":
				$str = "חודש נוכחי";
				$start = date('01-m-Y');
				$end = date('d-m-Y');
			break;
			case "custom":
				$str = "בין תאריכים";
				$start = date('01-m-Y');
				$end = date('d-m-Y');
			break;			
			case "previous_month":
				$str = "חודש קודם";
				$start = date('01-m-Y',strtotime("last month"));
				$end = date("t-m-Y", strtotime("last month"));
			break;
			case "previous_quarter":
				$dates = $this-> get_quarter(1);
				$start = $dates['start'];
				$end = $dates['end'];
				$str = "רבעון קודם";
			break;	
			case "current_quarter":
				$dates = $this-> get_quarter(0);
				$start = $dates['start'];
				$end = $dates['end'];
				$str = "רבעון נוכחי";				
			break;	
			case "all_time":
				$str = "כל התקופה";
				$start = date('01-01-1970');
				$end = date('d-m-Y');
			break;				
		}
		return array("start"=>$start,"end"=>$end,"str"=>$str,'selected' => '');
	}
	
	public function auth(){
		$this->set_layout('blank');
		if(!isset($_REQUEST['user']) || !isset($_REQUEST['token']) || !isset($_REQUEST['lead'])){
			SystemMessages::add_err_message("בקשה לא תקינה");
			return $this->redirect_to(outer_url());
		}
		$this->add_model('user_leads');
		$lead = User_Leads::find(array(
			'id'=>$_REQUEST['lead'],
			'user_id'=>$_REQUEST['user'],
			'token'=>$_REQUEST['token'],
		));

		if($lead){
			User_Leads::update($lead['id'], array('token'=>''));
			$current_user_id = false;
			if($this->user){
				$current_user_id = $this->user['id'];
			}
			if($lead['user_id']!=$current_user_id){
				$user_id = $lead['user_id'];
				UserLogin::add_login_trace($user_id);

			}
			session__set('show_row',$lead['id']);
			
			return $this->redirect_to(inner_url(''));
		}
		else{
			SystemMessages::add_err_message("פג תוקף כניסה אוטומטית של הליד");
			return $this->redirect_to(inner_url(''));
		}
	}

  }
  
?>