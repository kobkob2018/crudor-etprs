<?php
  class Leads extends Model{
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly
    public $id;
    public $estimate_form_data;
    public function __construct($lead_data,$add_refund_history=false) {
		$db = Db::getInstance();
		$status = array(
			'0'=>'מתעניין חדש',
			'5'=>'מחכה לטלפון',
			'1'=>'נוצר קשר',
			'2'=>'סגירה עם לקוח',
			'3'=>'לקוח רשום',
			'4'=>'לא רלוונטי',
			'6'=>'הליד זוכה',
		);

		$tag = Tags::get_user_tag_list();	
		if(is_null($lead_data['note'])){
			$lead_data['note']= "";
		}
		if(is_null($lead_data['tag'])){
			$lead_data['tag']= "";
		}
		if(is_null($lead_data['full_name'])){
			$lead_data['full_name']= "";
		}
		$lead = array(
			'row_id'=>$lead_data['id'],
			'date_in'=>$lead_data['date_in'],
			'last_update'=>$lead_data['date_in'],
			'full_name'=>trim($lead_data['full_name']),
			'phone'=>($lead_data['phone']),
			'email'=>($lead_data['email']),
			'note'=>trim(mb_substr($lead_data['note'],0,60))."...",
			'note_full'=>trim($lead_data['note']),
			'status'=>trim($lead_data['status']),
			'status_str'=>$status[$lead_data['status']],
			'tag'=>trim($lead_data['tag']),
			'tag_str'=>isset($tag[$lead_data['tag']])?$tag[$lead_data['tag']]['tag_name']:"",
			'tag_color'=>isset($tag[$lead_data['tag']])?$tag[$lead_data['tag']]['color_id']:"",			
			'view_state'=>$lead_data['view_state'],
			'deleted'=>$lead_data['deleted'],
			'open_state'=>$lead_data['open_state'],
			'resource'=>$lead_data['resource'],
			'request_id'=>$lead_data['request_id'],
			'phone_id'=>$lead_data['phone_id'],
			'refund_ok'=>'ok',
			'no_refund_reason'=>'',
			'bill_state_str'=>'חוייב',
			'billed'=>$lead_data['billed'],
			'duplicate_id'=>$lead_data['duplicate_id'],
			'offer_amount'=>$lead_data['offer_amount'],			
			'refund_request_sent_str'=>'',
			'refund_request_sent'=>'0',
			'fb_moredata'=>'0',
		);

		$lead['date_in_str'] = date('d/m/Y H:i',  strtotime($lead['date_in']));
		if($lead_data['view_time'] != ""){
			$lead['date_in_str'] = date('d/m/Y H:i',  strtotime($lead_data['view_time']));
		}
		if($lead_data['resource'] == 'phone'){
			$lead['resource_str']="התקבל טלפונית";
		}
		else{
			$lead['resource_str']="";
		}
		if($lead_data['last_update'] != "" && $lead_data['last_update'] != "0000-00-00 00:00:00"){
			$lead['last_update'] = $lead_data['last_update'];
		}	
		$lead['last_update_str'] = date('d/m/Y H:i',  strtotime($lead['last_update']));
		if($lead['open_state'] == '0'){
			$lead['phone'] = substr_replace( $lead['phone'] , "****" , 4 , 4 );
			$lead['email'] = '****@****';
		}
		$lead['final_cat']	= '0';	
		if($lead_data['request_id'] != "" && $lead_data['resource'] == "form"){
			//echo $lead_data['id'];
			if( $lead_data['request_id'] != "0" ){
				$cats_list = $this->get_cat_list();
				$sql = "SELECT * FROM biz_requests WHERE id = :request_id";
				$req = $db->prepare($sql);
				$req->execute(array('request_id'=>$lead_data['request_id']));
				$biz_request_data = $req->fetch();
				$lead['final_cat']	= $biz_request_data['cat_id'];
				$cat_tree = Biz_categories::get_item_parents_tree($lead['final_cat'],'id, parent, label');
				$cat_name = "";
				$full_cat_name_arr = array();

				foreach($cat_tree as $cat){
					$full_cat_name_arr[] = $cat['label'];
					$cat_name = $cat['label'];
				}
				$full_cat_name = implode(" > ",$full_cat_name_arr);
			}
			else{
				$cat_name = "טופס צור קשר";
				$full_cat_name = "טופס צור קשר";
			}
			$lead['full_cat_name'] = $full_cat_name;
			$lead['cat_name'] = $cat_name;		
		}
		$lead['recording_link'] = "0";
		$user = Users::get_loged_in_user();
		$user = Leads_user::get_leads_user_data($user);
		if($lead_data['resource'] == "phone"){
			
			$sql = "SELECT * FROM user_phone_calls WHERE id = :phone_id";
			$req = $db->prepare($sql);
			$req->execute(array('phone_id'=>$lead_data['phone_id']));
			$phone_lead_data = $req->fetch();
			$lead['answer'] = $phone_lead_data['answer'];
			$lead['cat_name'] = "שיחת טלפון";
			$lead['full_cat_name'] = "שיחת טלפון";
			if($lead['answer'] == "NO ANSWER"){
				$lead['cat_name'] = "שיחה שלא נענתה";
				$lead['full_cat_name'] = "שיחה שלא נענתה";
			}
			elseif($lead['answer'] == "MESSEGE"){
				$lead['cat_name'] = "טלפון לחזרה - ".$phone_lead_data['extra']."";
				$lead['full_cat_name'] = "טלפון לחזרה - ".$phone_lead_data['extra']."";
			}			
			else{
				$lead['cat_name'] .= "(".$phone_lead_data['billsec']."שנ')";
				$lead['full_cat_name'] .= "(".$phone_lead_data['billsec']."שנ')";
				if(isset($phone_lead_data['recordingfile']) && $phone_lead_data['recordingfile']!=""){
					if($user['access_records'] == '1'){
						if($user['records_password'] != ""){
							$lead['recording_link'] = "pass";
							if(session__isset('recordings_pass')){
								$lead['recording_link'] = inner_url("link_recordings/download/")."?filename=".$phone_lead_data['recordingfile'];
							}
						}
						else{
							$lead['recording_link'] = inner_url("link_recordings/download/")."?filename=".$phone_lead_data['recordingfile'];
						}
					}
				}
				
			}
		}
		$sql = "SELECT * FROM lead_refund_requests WHERE row_id = :row_id ORDER BY id DESC LIMIT 1";
		$req = $db->prepare($sql);
		$req->execute(array('row_id'=>$lead['row_id']));
		$refund_request = $req->fetch();
		$lead['refund_reason_str'] = "";
		if(isset($refund_request['reason'])){

			$reason = self::get_refund_reason_by_id($refund_request['reason']);
			
			$lead['refund_reason_str'] = $reason['label'];
			$lead['refund_request_sent'] = "1";
			if($refund_request['denied'] == '1'){
				$lead['refund_request_sent_str'] = "בקשה לזיכוי נדחתה";				
			}
			else{
				$lead['refund_request_sent_str'] = "נשלחה בקשה לזיכוי";
			}
			if($add_refund_history){
				$sql = "SELECT * FROM lead_refund_requests WHERE row_id = :row_id ORDER BY id";
				$req = $db->prepare($sql);
				$req->execute(array('row_id'=>$lead['row_id']));
				$refund_request_history_data = $req->fetchAll();
				$refund_request_history = array();
				$lead['has_refund_history'] = '0';
				foreach($refund_request_history_data as $refund_request){
					$lead['has_refund_history'] = '1';

					$reason_data = $this->get_refund_reason_by_id($refund_request['reason']);
					
					$reason_str = $reason_data['label'];
					$admin_comment_str = "---ממתין לתשובה---";
					if($refund_request['denied'] == '1'){
						$admin_comment_str = "הבקשה נדחתה";
						if($refund_request['admin_comment'] != ''){
							$admin_comment_str .= " - ".$refund_request['admin_comment'];
						}
					}

					$refund_request_history[$refund_request['id']] = array(
						"reason"=>$refund_request['reason'],
						"reason_str"=>$reason_str,
						"comment"=>$refund_request['comment'],
						"denied"=>$refund_request['denied'],
						"admin_comment"=>$admin_comment_str,
					);
				}
				$lead['refund_history'] = $refund_request_history;
			}
		}
		if($lead['open_state'] != '1'){
			$lead['refund_ok'] = 'no';
			$lead['no_refund_reason'] = 'closed';
			$lead['bill_state_str'] = 'לא חוייב(ליד סגור)';
		}
		elseif($lead['billed'] != '1'){
			$lead['refund_ok'] = 'no';
			$lead['no_refund_reason'] = 'not_billed';
			$lead['bill_state_str'] = 'לא חוייב';
			if($lead['duplicate_id'] == '-1'){
				$lead['bill_state_str'] = 'לא חוייב(טופס צור קשר)';
			}
			if($lead['duplicate_id'] != '' && $lead['duplicate_id'] != '-1'){
				$lead['no_refund_reason'] = 'doubled';
				$lead['bill_state_str'] = 'לא חוייב(ליד כפול)';
				
			}			
		}
		elseif($lead['status'] == '6'){
			$lead['refund_ok'] = 'no';
			$lead['no_refund_reason'] = 'refunded';
			$lead['bill_state_str'] = 'חוייב וזוכה';
		}
		else{
			$start = new DateTime($lead_data['date_in']);
			if($lead_data['send_state'] == '0'){
				if($lead_data['view_time'] != ""){
					$start = new DateTime($lead_data['view_time']); 
				}
			}
			$end = new DateTime();
			$hours = round(($end->format('U') - $start->format('U')) / (60*60));
			if($hours > 73){
				$lead['refund_ok'] = 'no';
				$lead['no_refund_reason'] = '72_hours';
			}
		}
		$this->estimate_form_data = $lead;		
    }

    public static function all($filter){
		
		$user = Users::get_loged_in_user();
		if(!$user){
			return false;
		}
		$user_id = $user['id'];
		$prepare_arr = array();
		$filter_sql = " user_id = :user_id ";
		$profit_filter_sql =  " user_id = :user_id ";
		$prepare_arr['user_id'] = $user_id;
		
		if($filter['date_from'] != ""){
			$filter_date_from_obj =  new DateTime($filter['date_from']);
			$filter_date_from = $filter_date_from_obj->format('Y-m-d');
			$filter_sql .= " AND ul.date_in >= :date_from ";
			$profit_filter_sql .= " AND ul.date_in >= :date_from ";
			$prepare_arr['date_from'] = $filter_date_from;
		}
		if($filter['date_to'] != ""){

			$filter_date_to_obj =  new DateTime($filter['date_to']." +1 day");
			$filter_date_to = $filter_date_to_obj->format('Y-m-d');
			$filter_sql .= " AND ul.date_in <= :date_to ";
			$profit_filter_sql .= " AND ul.date_in <= :date_to ";
			$prepare_arr['date_to'] = $filter_date_to;
		}
		if($filter['free'] != ""){
			$filter_sql .= " AND( ul.phone LIKE (:free) OR ul.full_name LIKE (:free) OR ul.email LIKE (:free) OR ul.note LIKE (:free))";
			$profit_filter_sql .= " AND( ul.phone LIKE (:free) OR ul.full_name LIKE (:free) OR ul.email LIKE (:free) OR ul.note LIKE (:free))";
			$filter_free = $filter['free'];
			$prepare_arr['free'] = '%'.$filter_free.'%';
		}
		if(!empty($filter['status'])){
			$status_in = implode(",",$filter['status']);
			$filter_sql .= " AND ul.status IN($status_in) ";
			$profit_filter_sql .= " AND ul.status = 2 ";
		}
		if(!empty($filter['tag'])){
			$tag_in = implode(",",$filter['tag']);
			$filter_sql .= " AND ul.tag IN($tag_in) ";
		}		
		$deleted_filter_sql = " AND ul.deleted = '0' ";
		if($filter['deleted'] != ''){
			$deleted_filter_sql = "";
		}
		if($filter['cat'] != '' && $filter['cat'] != '0'){
			$cat_offsprings = Biz_categories::simple_get_item_offsprings($filter['cat'],'id, parent, label');
			$cat_in_arr = array($filter['cat']);
			foreach($cat_offsprings as $cat_in){
				$cat_in_arr[] = $cat_in['id'];
			}
			if(empty($cat_in_arr)){
				$cat_in_arr[] = $filter['cat'];
			}
			$cat_in_sql = implode(",",$cat_in_arr);

			$filter_sql .= " AND brq.cat_id IN ($cat_in_sql)  ";
		}
		
		$filter_sql .= $deleted_filter_sql;
		$pending_qry = " AND ((ul.send_state != '0' OR ul.send_state IS NULL) OR (ul.view_time IS NOT NULL)) ";		
		$list = array();
		$db = Db::getInstance();

		$sql = "SELECT count(ul.id) as lead_count FROM user_leads ul LEFT JOIN biz_requests brq ON brq.id = ul.request_id WHERE $filter_sql $pending_qry";

		$req = $db->prepare($sql);
		$req->execute($prepare_arr);
		$lead_count_data = $req->fetch();
		$lead_count = $lead_count_data['lead_count'];
		$limit_sql = ""; 
		if($filter['leads_in_page'] != 'all'){
			$leads_in_page = $filter['leads_in_page'];
			$page_num = $filter['page_num'];
			$page_count = ceil($lead_count/$leads_in_page);
			$limit_from = ($page_num-1)*$leads_in_page;
			$limit_to = $limit_from+$leads_in_page;
			if($limit_to > $lead_count){
				$limit_to = $lead_count;
			}

			$limit_sql = "LIMIT $limit_from,$leads_in_page";
		}
		else{
			$leads_in_page = $filter['leads_in_page'];
			$page_num = '1';
			$page_count = '1';
			$limit_from = '0';
			$limit_to = $lead_count;
		}
		
		$sql = "SELECT ul.* FROM user_leads ul LEFT JOIN biz_requests brq ON brq.id = ul.request_id WHERE $filter_sql $pending_qry ORDER BY id desc $limit_sql";
		$pages_data = array("lead_count"=>$lead_count,"page_num"=>$page_num,"leads_in_page"=>$leads_in_page,"page_count"=>$page_count,"limit_from"=>($limit_from+1),"limit_to"=>$limit_to);
		try{
			$req = $db->prepare($sql);
			$req->execute($prepare_arr);
		}
		catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		$users_send_totals = array();
		// we create a list of Post objects from the database results
		foreach($req->fetchAll() as $lead) {
			$lead_data =  new Leads($lead);
			$list[] = $lead_data;
		}
		
		//sum profits for selection based on offer_amount
		$filter_sql.=" AND ul.status = 2 ";
		
		$sql = "SELECT sum(ul.offer_amount) as profits FROM user_leads ul LEFT JOIN biz_requests brq ON brq.id = ul.request_id WHERE $profit_filter_sql";

		$req = $db->prepare($sql);
		
		$req->execute($prepare_arr);
		
		$profits_data = $req->fetch();
		
		$profits = $profits_data['profits'];
		if($profits == ""){
			$profits = "0";
		}
		$pages_data['profits'] = $profits;
		return array("list"=>$list,"pages_data"=>$pages_data,);
    }

    public static function find($id) {
		$db = Db::getInstance();
		// we make sure $id is an integer
		$id = intval($id);
		$req = $db->prepare('SELECT * FROM user_leads WHERE id = :id');
		// the query was prepared, now we replace :id with our actual $id value
		$req->execute(array('id' => $id));
		$lead = $req->fetch();
		
		$lead_data = new Leads($lead,true);
		$lead_cat = $lead_data->estimate_form_data['final_cat'];
		$user = Users::get_loged_in_user();
		if($lead_data->estimate_form_data['resource'] == 'phone'){
			$all_refund_reasons = self::get_user_refund_reasons($user['id'],"'phone', 'all'");
		}
		else{
			$all_refund_reasons = self::get_cat_refund_reasons($lead_cat);
			$user_refund_reasons = self::get_user_refund_reasons($user['id'],"'form', 'all'");
			foreach($user_refund_reasons as $key=>$reason){
				$all_refund_reasons[$key] = $reason;
			}
		}
		$lead_data->estimate_form_data['cat_refund_reasons'] = $all_refund_reasons;
		$req = $db->prepare("UPDATE user_leads SET view_state ='1' WHERE id = :id");
		// the query was prepared, now we replace :id with our actual $id value
		$req->execute(array('id' => $id));		
		return $lead_data;
    }
    public static function delete_lead($id) {
		$user = Users::get_loged_in_user();
		if(!$user){
			return false;
		}
		$user_id = $user['id'];
		$db = Db::getInstance();
		// we make sure $id is an integer
		$id = intval($id);
		$req = $db->prepare("UPDATE user_leads set deleted = '1',last_update=NOW() WHERE user_id=:user_id AND id = :id");
		// the query was prepared, now we replace :id with our actual $id value
		$req->execute(array('id' => $id,'user_id' => $user_id));
		return self::find($id);
    }	
	
    public static function buy_lead($id) {
		$db = Db::getInstance();
		$user = Users::get_loged_in_user();
		if(!$user){
			return false;
		}

		$user_id = $user['id'];
		$user_lead_settings = Leads_user::get_leads_user_data($user);
		if($user_lead_settings['lead_credit'] > 0){
			$req = $db->prepare('SELECT * FROM user_leads WHERE id = :id');
			$req->execute(array('id' => $id));
			$lead = $req->fetch();


			$bill_array = array(
				"row_id" => $id,
				"open_state" => '1',
				"billed" => "1",
				"duplicate_id" => "0",
			);

			if(isset($lead['phone'])){
				$bill_sql = "SELECT id as billed_id FROM user_leads WHERE phone = :phone AND billed = 1 AND user_id = :user_id AND date_in > (CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE)) LIMIT 1";
				$req = $db->prepare($bill_sql);
				$req->execute(array('phone' => $lead['phone'],'user_id'=>$user_id));
				$bill_data = $req->fetch();
				if(isset($bill_data['billed_id'])){
					$bill_array['billed'] = '0';
					$bill_array['duplicate_id'] = $bill_data['billed_id'];
				}			
			}		
			
			$sql = "UPDATE user_leads SET open_state = :open_state ,billed = :billed, duplicate_id = :duplicate_id WHERE id = :row_id";
			$req = $db->prepare($sql);
			$effected_rows =  $req->execute($bill_array);
			if($effected_rows){
				if($bill_array['billed'] == '1'){
					$req = $db->prepare("UPDATE user_lead_settings SET lead_credit = lead_credit - 1 WHERE user_id = :user_id");
					$req->execute(array('user_id'=>$user_id));
				}
			}
			$return_array['success'] = '1';
		}
		else{
			$return_array['success'] = '0';
			$return_array['fail_reason'] = 'no_credit';
		}
		$return_array['lead'] = self::find($id);
		return $return_array;
    }

    public static function update_lead($id,$data_arr){
		$db = Db::getInstance();
		$user = Users::get_loged_in_user();
		if(!$user){
			return false;
		}
		$user_id = $user['id'];
		
		$set_sql_arr = array();
		foreach($data_arr as $key=>$val){
			$set_sql_arr[] = "$key=:$key";
			$data_arr[$key] = $val;
		}
		$set_sql_arr[] = "last_update=NOW()";
		$set_sql = implode(",",$set_sql_arr);
		$data_arr['row_id'] = $id;
		$sql = "UPDATE user_leads SET $set_sql WHERE id = :row_id";
		$req = $db->prepare($sql);
		$effected_rows =  $req->execute($data_arr);

		$return_array['success'] = '1';
	
		$return_array['lead'] = self::find($id);
		return $return_array;
    }
    public static function send_lead_refund_request($id,$data_arr){
		//print_r($data_arr);
		$db = Db::getInstance();
		$user = Users::get_loged_in_user();
		if(!$user){
			return false;
		}
		$user_id = $user['id'];		
		$insert_array = array();
		foreach($data_arr as $key=>$val){
			if($key == "comment"){
				$val = $val;
			}
			$insert_array[$key] = $val;
		}
		$insert_array['user_id'] = $user_id;
		$insert_array['lead_id'] = $id;
		
		$sql = "INSERT INTO lead_refund_requests (user_id, row_id, reason, comment,request_time) VALUES (:user_id,:lead_id,:reason,:comment,NOW())";
		$req = $db->prepare($sql);
		$effected_rows =  $req->execute($insert_array);
		$return_array['success'] = '1';	
		$return_array['lead'] = self::find($id);
		return $return_array;
    }	
	public static $cats_list = array();
	private function get_cat_list(){
		if(!empty(self::$cats_list)){
			return self::$cats_list;
		}
		$db = Db::getInstance();
		$cats_list = array();
		$sql = "SELECT * FROM biz_categories";
		$req = $db->prepare($sql);
		$req->execute();
		foreach($req->fetchAll() as $cat) {
			$cats_list[$cat['id']] = $cat;
		}
		self::$cats_list = $cats_list;
		return self::$cats_list;
	}	
	public static function get_cat_refund_reasons($cat_id = '0'){
		$db = Db::getInstance();
		$reason_list = array();
		$sql = "SELECT * FROM  refund_reasons WHERE user_id IS NULL AND (cat_id = '0' OR cat_id IS NULL OR cat_id = $cat_id)";
		
		
		$req = $db->prepare($sql);
		$req->execute();
		$cat_has_reasons = false;
		foreach($req->fetchAll() as $reason) {
			if($reason['cat_id'] != '0'){
				$cat_has_reasons = true;
			}
			$reason['title'] = $reason['label'];
			$reason_list[$reason['id']] = $reason;
		}
		if(!$cat_has_reasons && $cat_id!='0'){
			$sql = "SELECT parent FROM  biz_categories WHERE id = $cat_id";
			$req = $db->prepare($sql);
			$req->execute();
			$cat_parent_data = $req->fetch();
			if($cat_parent_data['parent'] != '0'){
				return self::get_cat_refund_reasons($cat_parent_data['parent']);
			}
		}
		return $reason_list;
	}
	private static function get_user_refund_reasons($user_id = '0',$lead_types = "'form', 'phone', 'all'"){
		$db = Db::getInstance();
		$reason_list = array();
		
		$sql = "SELECT * FROM  refund_reasons WHERE (user_id IS NULL OR user_id = '0' OR user_id = $user_id) AND lead_type IN($lead_types)";
		$req = $db->prepare($sql);
		$req->execute();
		$user_has_reasons = false;
		foreach($req->fetchAll() as $reason) {
			if($reason['user_id'] != '0'){
				$user_has_reasons = true;
			}
			$reason['label'] = $reason['label'];
			$reason_list[$reason['id']] = $reason;
		}
		return $reason_list;
	}	
	
	private function get_refund_reason_by_id($reason_id){
		
		$db = Db::getInstance();
		$refund_reason_list = array();
		$sql = "SELECT * FROM refund_reasons WHERE id = :reason_id";
		$req = $db->prepare($sql);
		$req->execute(array('reason_id'=>$reason_id));
		$reason = $req->fetch();
		if(!$reason){
			$all_reasons = self::get_user_refund_reasons();
			return $all_reasons[0];
		}
		return $reason;
		
	}
	

	
  }
?>