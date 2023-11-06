<?php
  class Daily_incomeController extends CrudController{

    protected function get_cat_id_in($cat_filter){

        if($cat_filter == '0'){
            return "";
        }
        $this->add_model('biz_categories');
        
        $cat_offsprings = Biz_categories::simple_get_item_offsprings($cat_filter,'id,parent,label');
        $cat_id_arr = array($cat_filter);
        foreach($cat_offsprings as $cat){
            $cat_id_arr[] = $cat['id'];
        }
		$cat_parents = Biz_categories::get_item_parents_tree($cat_filter,'id,parent,label');
		
		foreach($cat_parents as $cat){
            $cat_id_arr[] = $cat['id'];
        }
		
        $cat_id_in = implode(",",$cat_id_arr);
        return $cat_id_in;
    }

    public function report(){
      	$db = Db::getInstance();
	$date_from_str = date("d-m-Y");
	if(isset($_GET['date_from'])){
		$date_from_str = trim($_GET['date_from']);
	}
	$date_from_arr = explode("-",$date_from_str);
	$date_from_sql = $date_from_arr[2]."-".$date_from_arr[1]."-".$date_from_arr[0];
	$date_from_sort = $date_from_sql;
    $date_to_str = date("d-m-Y");
	if(isset($_GET['date_to'])){
		$date_to_str = trim($_GET['date_to']);
	}

	$date_to_arr = explode("-",$date_to_str);
	$date_to_sql_1 = $date_to_arr[2]."-".$date_to_arr[1]."-".$date_to_arr[0];
	$date_to_sql = date('Y-m-d', strtotime("+1 day", strtotime($date_to_sql_1)));
    $date_to_sort = date('Y-m-d', strtotime("+1 day", strtotime($date_to_sql_1)));


    $cats_sql = "SELECT id,label,parent FROM  biz_categories WHERE 1 ORDER BY label";	
    $req = $db->prepare($cats_sql);
    $req->execute();
    $cats_res = $req->fetchAll();
                
    $cat_list = array();
    $cat_list_by_name = array();
    $cat_list_names = array();
    foreach($cats_res as $cat){	
      $cat_list_names[$cat['id']] = $cat['label'];
      $cat_list[$cat['parent']][$cat['id']] = $cat['label']; 
      $cat_list_by_name[$cat['parent']][] = $cat['id'];
    }
    
	$cat_filter = "0";
    if(isset($_GET['cat_selected'])){
        if(isset($_GET['cat_selected'][0]) && $_GET['cat_selected'][0] != '0'){
          $cat_selected[0] = $_GET['cat_selected'][0];
          $cat_filter = $_GET['cat_selected'][0];
          if(isset($_GET['cat_selected'][1]) && $_GET['cat_selected'][1] != '0'){
            $cat_selected[1] = $_GET['cat_selected'][1];
            $cat_filter = $_GET['cat_selected'][1];
            if(isset($_GET['cat_selected'][2]) && $_GET['cat_selected'][2] != '0'){
              $cat_selected[2] = $_GET['cat_selected'][2];
              $cat_filter = $_GET['cat_selected'][2];
            }
          }
        }
      }

      $cat_id_in = $this->get_cat_id_in($cat_filter);

      $user_cat_sql = "";
      if($cat_filter != "0"){
        $user_ids = array();
        
        $user_ids_sql = "SELECT DISTINCT user_id FROM user_cat WHERE cat_id IN ($cat_id_in)";
        
        $req = $db->prepare($user_ids_sql);
        $req->execute();
        $user_ids_res = $req->fetchAll();
        
        foreach($user_ids_res as $user_i){
          $user_ids[] = $user_i['user_id'];
        }
    
        $user_ids_sql = "SELECT DISTINCT user_id FROM user_cat_city WHERE cat_id IN ($cat_id_in)";
        $req = $db->prepare($user_ids_sql);
        $req->execute();
        $user_ids_res = $req->fetchAll();
        	
        foreach($user_ids_res as $user_i){
          $user_ids[] = $user_i['user_id'];
        }
        
        if(empty($user_ids)){
          $user_cat_sql = " AND 0=1 ";
        }
        else{
          $user_ids_in = implode(",",$user_ids);
          $user_cat_sql = " AND user.id IN($user_ids_in) ";
        }
      }
      $user_name_sql = "";
      if(isset($_GET['user_name']) && $_GET['user_name'] != ""){
        $user_name_sql = ' AND user.full_name LIKE ("%'.$_GET['user_name'].'%") ';
      }
	$row_date = $date_from_sort;

    $sql = "SELECT user.id as user_id, full_name,biz_name,  advertisingPrice ,advertisingStartDate,lead_price,domainEndDate,hostPriceMon,domainPrice,end_date 
    FROM users user 
    LEFT JOIN user_bookkeeping book ON book.user_id = user.id
    LEFT JOIN user_lead_settings uls ON uls.user_id = user.id
    LEFT JOIN user_lead_visability ulv ON ulv.user_id = user.id
    WHERE uls.end_date > '$date_from_sql' $user_name_sql $user_cat_sql AND show_in_income_reports = 1 AND user.active = 1 AND uls.active = 1";	

    $req = $db->prepare($sql);
    $req->execute();
    $res = $req->fetchAll();
	$income_arr = array();

							
	$user_list = array();
	$status_list = array(
        '0'=>array('str'=>'מתעניין חדש','id'=>'0'),
        '5'=>array('str'=>'מחכה לטלפון','id'=>'5'),
        '1'=>array('str'=>'נוצר קשר','id'=>'1'),
        '2'=>array('str'=>'סגירה עם לקוח','id'=>'2'),
        '3'=>array('str'=>'לקוח רשום','id'=>'3'),
        '4'=>array('str'=>'לא רלוונטי','id'=>'4'),
        '6'=>array('str'=>'הליד זוכה','id'=>'5'),
	);	
	$selected_status = "all";
	if(isset($_REQUEST['lead_status'])){
		$selected_status = $_REQUEST['lead_status'];
	}
	foreach($res as $user){
		if($user['domainPrice'] == ""){
			$user['domainPrice'] = 0;
		}
		if($user['hostPriceMon'] == ""){
			$user['hostPriceMon'] = 0;
		}
		if($user['advertisingPrice'] == ""){
			$user['advertisingPrice'] = 0;
		}
		if($user['lead_price'] == ""){
			$user['lead_price'] = 0;
		}		
		$user_static_costs = array(
			
			"domainPrice"=>$user['domainPrice']/365,
			//days in month is 28, 30 or 31
			"hostPriceMon"=>array(28=>$user['hostPriceMon']/28
							,29=>$user['hostPriceMon']/29
							,30=>$user['hostPriceMon']/30
							,31=>$user['hostPriceMon']/31),
							
			"advertisingPrice"=>array(28=>$user['advertisingPrice']/28
				,29=>$user['advertisingPrice']/29
				,30=>$user['advertisingPrice']/30
				,31=>$user['advertisingPrice']/31),
			"leads"=>$user['lead_price'],
		);
		$user['static_costs'] = $user_static_costs;
		$user['leads_count_total'] = 0;
		$user['billed_leads_count_total'] = 0;
		$user['deal_closed_count'] = 0;
		$user_list[$user['user_id']] = $user;
		$user_id_list[$user['user_id']] = $user['user_id'];
	} 
	if(!empty($user_id_list)){
		$user_id_in_sql = implode(",",$user_id_list);
	}
	else{
		$user_id_in_sql = "-1";
	}
    $lead_campaign_sql = "";	
    if(isset($_REQUEST['add_campaign_leads'])){
      $lead_campaign_sql = " AND (uld.resource = 'none'";
      if(isset($_REQUEST['add_reg_leads'])){
        $lead_campaign_sql .= " OR req.campaign_type = '0'  OR uld.campaign_type = '0' ";
      }
      if(isset($_REQUEST['add_gl_leads'])){
        $lead_campaign_sql .= " OR req.campaign_type = '1'  OR uld.campaign_type = '1' ";
      }		
      if(isset($_REQUEST['add_fb_leads'])){
        $lead_campaign_sql .= " OR req.campaign_type = '2'  OR uld.campaign_type = '2' ";
      }
      
      $lead_campaign_sql .= ")";
    }

	$lead_status_sql = "";
	if($selected_status != "all" && $selected_status != ""){
		$lead_status_sql = " AND uld.status = $selected_status ";
	}
	$phone_leads_sql = "";
	if(isset($_REQUEST['phone_leads_remove'])){
		$phone_leads_sql = " AND uld.resource = 'form' ";
	}	
	$form_leads_sql = "";
	if(isset($_REQUEST['form_leads_remove'])){
		$form_leads_sql = " AND uld.resource != 'form' ";
	}	
    $lead_cat_sql = "";	
    if(isset($_REQUEST['cat_leads_only']) && $cat_filter != '0'){
      $lead_cat_sql = " AND ((uld.resource != 'form' AND user.id IN($user_id_in_sql)) OR req.cat_id IN ($cat_id_in)) ";
    }
	
	$sql = "SELECT uld.id, user.id as user_id,uld.date_in,uld.status as lead_status,req.ip,uld.campaign_name as campaign_name,open_state,request_id,resource,billed,phone_id,req.c1, req.c2, req.c3, req.c4, uld.full_name,uld.phone,req.campaign_type as request_campaign_type ,uld.campaign_type as campaign_type,uld.tag,tagin.tag_name,uld.offer_amount
			FROM user_leads uld 
			LEFT JOIN users user ON user.id = uld.user_id 
            LEFT JOIN user_lead_settings uls ON user.id = uls.user_id
			LEFT JOIN biz_requests req ON req.id = uld.request_id 
			LEFT JOIN user_lead_tag tagin ON uld.tag = tagin.id 
            LEFT JOIN user_lead_visability ulv ON ulv.user_id = user.id
			WHERE uls.end_date > '$date_from_sql' $phone_leads_sql $form_leads_sql $lead_cat_sql $lead_status_sql $lead_campaign_sql $user_name_sql AND show_in_income_reports = 1 AND user.active = 1 AND uld.date_in > '$date_from_sql' AND uld.date_in < '$date_to_sql' AND uld.open_state = 1";	
			
	
    $req = $db->prepare($sql);
    $req->execute();
    $res = $req->fetchAll();

	$lead_list = array();
	$campaign_str = array('0'=>'רגיל','1'=>'גוגל','2'=>'פייסבוק');
	$refunded_leads = array();
	$closed_deal_leads = array();
	foreach($res as $lead){
		if($lead['resource'] == 'phone'){
			$lead['campaign_str'] = $campaign_str[$lead['campaign_type']];
			if(isset($_GET['show_leads']) && $lead['phone_id'] != ""){
				$phone_lead_sql = "SELECT * FROM user_phone_calls WHERE id = '".$lead['phone_id']."' ";
                $req = $db->prepare($phone_lead_sql);
                $req->execute();
                $phone_lead_data = $req->fetch();
				$lead['call_info'] = $phone_lead_data;
			}
		}
		else{
			if(isset($campaign_str[$lead['campaign_type']])){
				$lead['campaign_str'] = $campaign_str[$lead['campaign_type']];
			}
			else{
				$lead['campaign_str'] = "";
			}
			if((int)$lead['campaign_type'] > 999  && false){
                /*
				$lead['campaign_str'] = "aff - ";
				$aff_id = (int)$lead['campaign_type'] - 1000;
				$aff_sql = "SELECT * FROM affiliates WHERE id = '".$aff_id."'";
				$aff_res = mysql_db_query(DB,$aff_sql);
				$aff_data = mysql_fetch_array($aff_res);
				$lead['campaign_str'] .= $aff_data['biz_name'];
				//$lead['campaign_str'] = $aff_sql;
                */
			}
		}
		$lead_date_arr = explode(" ",$lead['date_in']);
		$lead_date = $lead_date_arr[0];
		if($lead['lead_status'] != '6'){
			$lead_list[$lead_date][$lead['user_id']][] = $lead;
			if($lead['lead_status'] == '2'){
				$closed_deal_leads[$lead_date][$lead['user_id']][] = $lead;
			}
		}
		else{
			$refunded_leads[$lead_date][$lead['user_id']][] = $lead;
		}
	
	}

//payByPassword 0 leads only here -----
	$sql = "SELECT uld.id,user.id as user_id,uld.date_in,uld.status as lead_status,req.ip,uld.campaign_name as campaign_name,open_state,request_id,resource,billed,phone_id,req.c1, req.c2, req.c3, req.c4,uld.full_name,uld.phone,req.campaign_type,uld.campaign_type,uld.tag,tagin.tag_name as tag_name
			FROM user_leads uld 
			LEFT JOIN users user ON user.id = uld.user_id
			LEFT JOIN biz_requests req ON req.id = uld.request_id 
			LEFT JOIN user_lead_tag tagin ON uld.tag = tagin.id 
            LEFT JOIN user_lead_settings uls ON user.id = uls.user_id 
            LEFT JOIN user_lead_visability ulv ON ulv.user_id = user.id
			WHERE uls.end_date > '$date_from_sql' $phone_leads_sql $form_leads_sql $lead_cat_sql $lead_status_sql $lead_campaign_sql $user_name_sql AND show_in_income_reports = 1 AND user.active = 1 AND uls.active = 1 AND uld.date_in > '$date_from_sql' AND uld.date_in < '$date_to_sql' AND uld.open_state = 0";	
			

    $req = $db->prepare($sql);
    $req->execute();
    $res = $req->fetchAll();
							
	$payByPassword0_lead_list = array();
	
	foreach($res as $lead){
        if($lead['campaign_type'] == ""){
            $lead['campaign_type'] = "0";
        }
		if($lead['resource'] == 'phone'){
			$lead['campaign_str'] = $campaign_str[$lead['campaign_type']];
			if(isset($_GET['show_leads']) && $lead['phone_id'] != ""){
				$phone_lead_sql = "SELECT * FROM user_phone_calls WHERE id = '".$lead['phone_id']."' ";
                $req = $db->prepare($phone_lead_sql);
                $req->execute();
                $res = $req->fetch();
				$lead['call_info'] = $res;
			}
		}
		else{
			$lead['campaign_str'] = $campaign_str[$lead['campaign_type']];
		}
		$lead_date_arr = explode(" ",$lead['date_in']);
		$lead_date = $lead_date_arr[0];
		$payByPassword0_lead_list[$lead_date][$lead['user_id']][] = $lead;	
	}
//payByPassword 0 leads only untill here -----	
	//echo "<pre>";
	//print_r($lead_list);
	//echo "</pre>";
	$all_days_income = array(
		"domain"=>0, //domainPrice
		"hosting"=>0, //hostPriceMon
		"advertyzing_global"=>0, //advertisingPrice
		"leads_count"=>0, //leads
		"billed_leads_count"=>0, //leads
		"deal_closed_count"=>0, //leads
		"payByPassword0"=>0,
		"tracking_count"=>0,
		"tracking_cookie_count"=>0,
		"leads"=>0, //leads
		"sum_all"=>0, //leads
	); 
	while($row_date < $date_to_sort){
		$daily_income_arr = array(
			"domain"=>0, //domainPrice
			"hosting"=>0, //hostPriceMon
			"advertyzing_global"=>0, //advertisingPrice
			"leads"=>0, //leads
			"payByPassword0"=>0,
			"tracking_count"=>0,
			"tracking_cookie_count"=>0,			
			"deal_closed_count"=>0,
            "leads_count"=>0,
			"billed_leads_count"=>0,
		); 
		$row_date_arr = explode("-",$row_date);
		$days_in_row_mont = cal_days_in_month ( CAL_GREGORIAN , $row_date_arr[1] , $row_date_arr[0] );

		
		foreach($user_list as $key=>$user){
			
			if($user['end_date'] >= $row_date){
				$user_income_row = array();
				$user_income_row['full_name'] = "<a target='_blank' href='".inner_url('users/edit/?row_id='.$user['user_id'])."'>".$user['full_name']."</a>";
				$user_income_row['user_id'] = $user['user_id'];
				$user_income_row['biz_name'] = $user['biz_name'];
                $user_income_row['deal_closed_count'] = 0;
				if(isset($lead_list[$row_date][$user['user_id']])){
					
					$user_lead_count = count($lead_list[$row_date][$user['user_id']]);
					$user_billed_leads_count = 0;
					foreach($lead_list[$row_date][$user['user_id']] as $lead){
						if($lead['billed'] != '0'){
							$user_billed_leads_count++;
						}
					}	
					if($user['lead_price'] != 0 && $user['lead_price'] != ""){
						$user_lead_daily_outcome = $user_billed_leads_count*$user['lead_price'];
						$daily_income_arr['leads']+=$user_lead_daily_outcome;
						$user_income_row['leads']=$user_lead_daily_outcome;
					}
				
					$user_income_row['leads_count']=$user_lead_count;
					$user_income_row['billed_leads_count']=$user_billed_leads_count;
					$user_income_row['lead_list'] = $lead_list[$row_date][$user['user_id']];
					if(isset($closed_deal_leads[$row_date][$user['user_id']])){				
						$user_income_row['closed_deal_leads'] = $closed_deal_leads[$row_date][$user['user_id']];
						$user_income_row['deal_closed_count'] = count($user_income_row['closed_deal_leads']);
						$daily_income_arr['deal_closed_count']+=$user_income_row['deal_closed_count'];
						// $user_list[$key]['leads_count_total']+=$user_income_row['leads_count_total'];
					}
					else{
						$user_income_row['deal_closed_count'] = 0;
					}
				}
				else{
					$user_income_row['leads_count']=0;
					$user_income_row['billed_leads_count']=0;	
				}

	
				$daily_income_arr['leads_count']+=$user_income_row['leads_count'];
				$daily_income_arr['billed_leads_count']+=$user_income_row['billed_leads_count'];
				
				
				//payByPassword 0 leads only here -----	
				if(isset($payByPassword0_lead_list[$row_date][$user['user_id']])){
					
					$user_payByPassword0_count = count($payByPassword0_lead_list[$row_date][$user['user_id']]);					
					$user_income_row['payByPassword0']=$user_payByPassword0_count;
					$user_income_row['payByPassword0_lead_list'] = $payByPassword0_lead_list[$row_date][$user['user_id']];
				}
				else{
					$user_income_row['payByPassword0']=0;		
				}
				$daily_income_arr['payByPassword0']+=$user_income_row['payByPassword0'];

				//payByPassword 0 leads only untill here -----	
				
				
				if(isset($refunded_leads[$row_date][$user['user_id']])){				
					$user_income_row['refunded_leads'] = $refunded_leads[$row_date][$user['user_id']];
				}
			
				$user_list[$key]['leads_count_total']+=$user_income_row['leads_count'];
				$user_list[$key]['billed_leads_count_total']+=$user_income_row['billed_leads_count'];
				
				$daily_income_arr['hosting']+=$user['static_costs']['hostPriceMon'][$days_in_row_mont];
				$user_income_row['hosting']=$user['static_costs']['hostPriceMon'][$days_in_row_mont];
				if($user['domainEndDate'] >= $row_date){
					$daily_income_arr['domain']+=$user['static_costs']['domainPrice'];
					$user_income_row['domain']=$user['static_costs']['domainPrice'];
				}
				else{
					$user_income_row['domain']=0;
				}
				if($user['advertisingStartDate'] <= $row_date){
					$daily_income_arr['advertyzing_global']+=$user['static_costs']['advertisingPrice'][$days_in_row_mont];
					$user_income_row['advertyzing_global']=$user['static_costs']['advertisingPrice'][$days_in_row_mont];
				}

                if(!isset($user_income_row['leads'])){
                    $user_income_row['leads'] = 0;
                }
                if(!isset($user_income_row['advertyzing_global'])){
                    $user_income_row['advertyzing_global'] = 0;
                }
                if(!isset($user_income_row['hosting'])){
                    $user_income_row['hosting'] = 0;
                }
                if(!isset($user_income_row['domain'])){
                    $user_income_row['domain'] = 0;
                }
				$user_income_row['sum_all'] = 
				
					$user_income_row['hosting']+
					$user_income_row['domain']+ 
					$user_income_row['advertyzing_global']+
					$user_income_row['leads']
					; 
					
				if($user_income_row['sum_all'] != 0 || isset($user_income_row['lead_list']) || $user_income_row['payByPassword0']!='0')
				{
					$daily_income_arr['user'][$user['user_id']] = $user_income_row;
				}
			}
		}
		
		$daily_income_arr['sum_all'] = $daily_income_arr['hosting'] + $daily_income_arr['domain'] + $daily_income_arr['advertyzing_global'] + $daily_income_arr['leads'];
		$income_arr[$row_date] = $daily_income_arr;
		$row_date = date('Y-m-d', strtotime("+1 day", strtotime($row_date)));	
		
		$all_days_income['domain']+=$daily_income_arr['domain'];
		$all_days_income['hosting']+=$daily_income_arr['hosting'];
		$all_days_income['advertyzing_global']+=$daily_income_arr['advertyzing_global'];
		$all_days_income['leads_count']+=$daily_income_arr['leads_count'];
		$all_days_income['billed_leads_count']+=$daily_income_arr['billed_leads_count'];

		
		$all_days_income['payByPassword0']+=$daily_income_arr['payByPassword0'];	  			
		$all_days_income['tracking_count']+=$daily_income_arr['tracking_count']; // ----- tracking_count tracking_cookie_count
		$all_days_income['tracking_cookie_count']+=$daily_income_arr['tracking_cookie_count'];
		$all_days_income['deal_closed_count']+=$daily_income_arr['deal_closed_count'];
		$all_days_income['leads']+=$daily_income_arr['leads'];
		$all_days_income['sum_all']+=$daily_income_arr['sum_all'];
		
		
	}
    
	$all_days_income['sum_all_2'] = $all_days_income['domain']+$all_days_income['hosting']+$all_days_income['advertyzing_global']+$all_days_income['leads'];
	?>
	
	
	<h3>
    דוח הכנסות יומיות
	</h3>
	
	&nbsp;&nbsp;&nbsp;&nbsp;
	
    <!-- sub categories helpers -->
    <div class="hidden" id="cat_select_sub_mokups">
        <?php foreach($cat_list_by_name['0'] as $cat_id_f): ?>
          <?php echo  $cat_list['0'][$cat_id_f]; ?>
          <select id="sub_cat_select_<?php echo $cat_id_f; ?>_mokup" class='input_style cat_selected_sub cat_selected_trig'  data-father="0" data-id="<?php echo  $cat_id_f; ?>" style="width:130px;" >
            <option value='0'>בחר קטגוריה</option>
            <?php foreach($cat_list_by_name[$cat_id_f] as $cat_id): ?>
              <option value='<?php echo $cat_id; ?>'><?php echo $cat_list[$cat_id_f][$cat_id]; ?></option>
            <?php endforeach; ?>
          </select>	
          <?php if(isset($cat_list_by_name[$cat_id_f])): foreach($cat_list_by_name[$cat_id_f] as $cat_id_s): 
            if(!empty($cat_list_by_name[$cat_id_s])): ?>
            <?php echo  $cat_list[$cat_id_f][$cat_id_s]; ?>
            <select id="sub_cat_select_<?php echo $cat_id_s; ?>_mokup" class='input_style cat_selected_sub cat_selected_trig' style="width:130px;" data-father="<?php echo $cat_id_f; ?>"  data-id="<?php echo  $cat_id_s; ?>">
              <option value='0'>בחר קטגוריה</option>
              <?php foreach($cat_list_by_name[$cat_id_s] as $cat_id_spaec): ?>
                <option value='<?php echo $cat_id_spaec; ?>'><?php echo $cat_list[$cat_id_s][$cat_id_spaec]; ?></option>
              <?php endforeach;?>
            </select>				
          <?php endif;  endforeach;  endif;  ?>					
        <?php endforeach; ?>
      </div>	
	
      <script type="text/javascript">

        document.addEventListener("DOMContentLoaded",()=>{

          document.querySelectorAll(".cat_selected_trig").forEach(el=>{
            el.addEventListener("change",function(){
              
              const el_id = el.dataset.id;
              update_sub_cat_select(el_id);
            });			
          });
          <?php if(isset($cat_selected[0])): ?>
            trigger_cat_select("0","<?php echo $cat_selected[0]; ?>");
          <?php endif; ?>
          <?php if(isset($cat_selected[1])): ?>
            setTimeout(function(){
              trigger_cat_select("<?php echo $cat_selected[0]; ?>","<?php echo $cat_selected[1]; ?>");
            },300);
          <?php endif; ?>		
          <?php if(isset($cat_selected[2])): ?>
            setTimeout(function(){
              trigger_cat_select("<?php echo $cat_selected[1]; ?>","<?php echo $cat_selected[2]; ?>");
            },600);
          <?php endif; ?>				
        });	
    
        function trigger_cat_select(cat_id,select_val){
          console.log("#sub_cat_select_"+cat_id);
          const cat_el = document.querySelector("#sub_cat_select_"+cat_id);
          cat_el.value = select_val;
            
            const el_id = cat_el.dataset.id;
            update_sub_cat_select(el_id);
        }
        
        function remove_select_sons(el_id){
          
            document.querySelector("#sub_cat_place_holder").querySelectorAll(".cat_selected_sub").forEach(sub=>{
              
              const father_cat = sub.dataset.father;
              if(father_cat == el_id){
                remove_select_sons(sub.dataset.id);
                sub.remove();
              }
            });	
          		
        }
        
        function update_sub_cat_select(el_id){
          remove_select_sons(el_id);
          console.log("#sub_cat_select_"+el_id);
          const select_el = document.querySelector("#sub_cat_select_"+el_id);	
          
          const selected_cat = select_el.value;
          
          const mokup = document.querySelector("#sub_cat_select_"+selected_cat+"_mokup");
          
          
          
          if(mokup){
            
            var select_parent = select_el.dataset.father;
            var new_select_mokup = mokup.cloneNode(true);
            
            var new_select_sub = new_select_mokup.cloneNode(true);
            
            new_select_sub.id = "sub_cat_select_"+selected_cat;
            new_select_sub.name = "cat_selected[]";
            document.querySelector("#sub_cat_place_holder").append(new_select_sub);
            new_select_sub.addEventListener("change",function(){
              update_sub_cat_select(new_select_sub.dataset.id);
            });
            
          }
        }
      
      </script>
	
    <div style="padding:20px;">
        <form action="<?= inner_url("daily_income/report/") ?>" method="GET">
          מתאריך <input type="text" name="date_from" value="<?php echo $date_from_str; ?>" />&nbsp&nbsp&nbsp
          עד תאריך  <input type="text" name="date_to" value="<?php echo $date_to_str; ?>" />&nbsp&nbsp&nbsp
          שם לקוח  <input type="text" name="user_name" value="<?php echo isset($_GET['user_name'])? $_GET['user_name']: ""; ?>" />&nbsp&nbsp&nbsp
          <br/><br/>
          <?php
            $add_campaign_leads_style_class = " hidden ";
            $add_campaign_leads_checked = "";
            $add_reg_leads_checked = "checked";
            $add_fb_leads_checked = "checked";
            $add_gl_leads_checked = "checked";
            
            if(isset($_GET['add_campaign_leads'])){
              $add_campaign_leads_style_class = "  ";
              $add_campaign_leads_checked = "checked";
              $add_reg_leads_checked = "";
              $add_fb_leads_checked = "";
              $add_gl_leads_checked = "";
              if(isset($_GET['add_reg_leads'])){
                $add_reg_leads_checked = "checked";
              }
              if(isset($_GET['add_fb_leads'])){
                $add_fb_leads_checked = "checked";
              }
              if(isset($_GET['add_gl_leads'])){
                $add_gl_leads_checked = "checked";
              }
            }				
          ?>
			
			
            <br/>
          <input type="checkbox" id="add_campaign_leads_door" name="add_campaign_leads" value="1" <?php echo $add_campaign_leads_checked; ?>/>הוסף סינון לידים לפי קמפיין:  &nbsp&nbsp&nbsp
          <div id="add_campaign_leads_wrap" class="<?php echo $add_campaign_leads_style_class; ?>"><br/><b>הוסף לחישוב לידים מסוג: </b>
            <input type="checkbox"  name="add_reg_leads" value="1" <?php echo $add_reg_leads_checked; ?>/> ללא קמפיין &nbsp&nbsp&nbsp
            <input type="checkbox"  name="add_fb_leads" value="1" <?php echo $add_fb_leads_checked; ?>/> מקמפיין פייסבוק &nbsp&nbsp&nbsp
            <input type="checkbox"  name="add_gl_leads" value="1" <?php echo $add_gl_leads_checked; ?>/> מקמפיין גוגל &nbsp&nbsp&nbsp
            
          </div>
			<br/><br/>
            <script type="text/javascript">
            document.querySelector("#add_campaign_leads_door").addEventListener(
              "change",
              function(event){
                const el = event.target;
                if(el.checked){
                  document.querySelector("#add_campaign_leads_wrap").classList.remove("hidden");
                }
                else{
                  document.querySelector("#add_campaign_leads_wrap").classList.add("hidden");
                }
              }
            );
            
          </script>
			קטגוריה
            <select id="sub_cat_select_0" name='cat_selected[]' class='input_style cat_selected_f cat_selected_trig' data-id='0' data-father='0' style='width:130px;'>
            <option value='0'>בחר קטגוריה</option>
            <?php foreach($cat_list_by_name['0'] as $cat_id): ?>
              <option value='<?php echo $cat_id; ?>'><?php echo $cat_list['0'][$cat_id]; ?></option>
            <?php endforeach; ?>
          </select>
			<?php
				
				$cat_leads_only_checked = "";
				
				if(isset($_GET['cat_leads_only'])){
					$cat_leads_only_checked = "checked";
				}
				$phone_leads_remove_checked = "";
				$form_leads_remove_checked = "";
				
				if(isset($_GET['phone_leads_remove'])){
					$phone_leads_remove_checked = "checked";
				}
				if(isset($_GET['form_leads_remove'])){
					$form_leads_remove_checked = "checked";
				}				
			?>			
			
			<span id="sub_cat_place_holder">
			
			</span>
			<input type="checkbox"  name="cat_leads_only" value="1" <?php echo $cat_leads_only_checked; ?>/> הוסף רק לידים ששייכים לקטגוריה &nbsp&nbsp&nbsp
          <input type="checkbox"  name="phone_leads_remove" value="1" <?php echo $phone_leads_remove_checked; ?>/> הסר לידים טלפוניים &nbsp&nbsp&nbsp
			<input type="checkbox"  name="form_leads_remove" value="1" <?php echo $form_leads_remove_checked; ?>/>הסר לידים מטפסים&nbsp&nbsp&nbsp
			<br/><br/>
			<?php
				$show_customers_checked = "";
				if(isset($_GET['show_customers'])){
					$show_customers_checked = "checked";
				}
				$show_leads_checked = "";
				if(isset($_GET['show_leads'])){
					$show_leads_checked = "checked";
				}				
			?>
			<input type="checkbox"  name="show_customers" value="1" <?php echo $show_customers_checked; ?>/>  הצג פרוט לקוחות &nbsp&nbsp&nbsp
			<input type="checkbox"  name="show_leads" value="1" <?php echo $show_leads_checked; ?>/>הצג פרוט לידים ללקוח &nbsp&nbsp&nbsp
			בחר סטטוס ליד
			<select id="status_select" name="lead_status" class='input_style' style="width:130px;" >
				<?php $selected_str = ($selected_status == "all")? "selected":""; ?> 
				<option value='all' <?php echo $selected_str; ?>>כל הסטטוסים</option>
				<?php foreach($status_list as $status_id=>$status_data): ?>
					<?php $selected_str = ($selected_status ==  $status_data['id'])? "selected":""; ?> 
					<option value='<?php echo $status_id; ?>' <?php echo $selected_str; ?>><?php echo $status_data['str']; ?></option>
				<?php endforeach; ?>
			</select>
			&nbsp&nbsp&nbsp
			<input type="submit" value="הצג" />
		</form>
	</div>
	
	<table class="maintext-oldsys" border="1" cellpadding="3" style="border-collapse: collapse;">
		<tr>
			<th>יום</th>
			<th>אחסון</th>
			<th>דומיין</th>
			<th>פרסום</th>
			<td>כמות לידים</td>
			<th>לידים מחוייבים</th>
			<th>לידים</th>
			<th>סגירה עם לקוח</th>
			<th>לידים בכוכביות</th>
			<th>מספר כניסות לעמודים</th>
			<th>מספר גולשים</th>
			<th>סך הכל</th>
		</tr>
		<?php foreach($income_arr as $day=>$day_income_arr): ?>
			<?php $day_arr = explode("-",$day); $day_str = $day_arr[2]."-".$day_arr[1]."-".$day_arr[0]; ?>
			<tr>
				<th><?php echo $day_str; ?></td>
				<td><?php echo number_format ($day_income_arr['hosting'],2); ?></td>
				<td><?php echo number_format ($day_income_arr['domain'],2); ?></td>
				<td><?php echo number_format ($day_income_arr['advertyzing_global'],2); ?></td>
				<td><?php echo $day_income_arr['leads_count']; ?></td>
				<td><?php echo $day_income_arr['billed_leads_count']; ?></td>
				
				<td><?php echo number_format ($day_income_arr['leads'],2); ?></td>
				<td><?php echo $day_income_arr['deal_closed_count']; ?></td>
				<td><?php echo $day_income_arr['payByPassword0']; ?></td>
				<td><?php echo $day_income_arr['tracking_count']; ?></td>
				<td><?php echo $day_income_arr['tracking_cookie_count']; ?></td>
				<td><?php echo number_format ($day_income_arr['sum_all'],2); ?></td>
			</tr>
			
			
		<?php endforeach; ?>
		<tr>
			<th style="color:green">סיכום</td>
			<td style="color:green"><?php echo number_format ($all_days_income['hosting'],2); ?></td>
			<td style="color:green"><?php echo number_format ($all_days_income['domain'],2); ?></td>
			<td style="color:green"><?php echo number_format ($all_days_income['advertyzing_global'],2); ?></td>
			<td style="color:green"><?php echo $all_days_income['leads_count']; ?></td>
			<td style="color:green"><?php echo $all_days_income['billed_leads_count']; ?></td>
			
			<td style="color:green"><?php echo number_format ($all_days_income['leads'],2); ?></td>
			<td style="color:green"><?php echo $all_days_income['deal_closed_count']; ?></td>
			<td style="color:green"><?php echo $all_days_income['payByPassword0']; ?></td>
			<td style="color:green"><?php echo $all_days_income['tracking_count']; ?></td>
			<td style="color:green"><?php echo $all_days_income['tracking_cookie_count']; ?></td>			
			<td style="color:green">
				<?php echo number_format ($all_days_income['sum_all'],2); ?>
				<br/>
				<small>
					<?php echo number_format ($all_days_income['sum_all_2'],2); ?>
				</small>
			</td>
		</tr>
	</table>

	<?php if(isset($_GET['show_customers'])): ?>
		<h3>פירוט לקוחות ליום</h3>
		<?php foreach($income_arr as $day=>$day_income_arr): ?>
			<?php $day_arr = explode("-",$day); $day_str = $day_arr[2]."-".$day_arr[1]."-".$day_arr[0]; ?>
			<br/><br/>
			<table class="maintext-oldsys" border="1" cellpadding="3"  style="border-collapse: collapse;">
			<tr>
                <th>יום</th>
                <th>אחסון</th>
                <th>דומיין</th>
                <th>פרסום</th>
                <td>כמות לידים</td>
				<td>לידים מחוייבים</td>
                <th>לידים</th>
                <th>סגירה עם לקוח</th>
                <th>לידים בכוכביות</th>
                <th>מספר כניסות לעמודים</th>
                <th>מספר גולשים</th>
                <th>סך הכל</th>
			</tr>
			<tr>
				<td><?php echo $day_str; ?></td>
				<td><?php echo number_format ($day_income_arr['hosting'],2); ?></td>
				<td><?php echo number_format ($day_income_arr['domain'],2); ?></td>
				<td><?php echo number_format ($day_income_arr['advertyzing_global'],2); ?></td>
				<td><?php echo $day_income_arr['leads_count']; ?></td>
				<td><?php echo $day_income_arr['billed_leads_count']; ?></td>		
				<td><?php echo number_format ($day_income_arr['leads'],2); ?></td>
				<td><?php echo $day_income_arr['deal_closed_count']; ?></td>
				<td><?php echo $day_income_arr['payByPassword0']; ?></td>
				<td><?php echo $day_income_arr['tracking_count']; ?></td>
				<td><?php echo $day_income_arr['tracking_cookie_count']; ?></td>					
				<td><?php echo number_format ($day_income_arr['sum_all'],2); ?></td>
			</tr>
			</table>
			<br/>
			<table class="maintext-oldsys" border="1" cellpadding="3"  style="border-collapse: collapse;">
			<tr>
            <th>לקוח</th>
			<th>אחסון</th>
			<th>דומיין</th>
			<th>פרסום</th>
			<td>כמות לידים</td>
			<td>לידים מחוייבים</td>
			<th>סגירה עם לקוח</th>
			<th>לידים בכוכביות</th>
			<th>כמות לידים לכל התקופה</th>
			<td>מחוייבים</td>
			<th>מחיר ליד</th>
			<th>לידים</th>
			<th>סך הכל</th>
			</tr>
			
			<?php if(isset($day_income_arr['user'])): foreach($day_income_arr['user'] as $user_id=>$user_income_arr): ?>

			<tr>
            <th>לקוח</th>
			<th>אחסון</th>
			<th>דומיין</th>
			<th>פרסום</th>
			<td>כמות לידים</td>
			<td>לידים מחוייבים</td>
			<th>סגירה עם לקוח</th>
			<th>לידים בכוכביות</th>
			<th>כמות לידים לכל התקופה</th>
			<th>מחוייבים</th>
			<th>מחיר ליד</th>
			<th>לידים</th>
			<th>סך הכל</th>
			</tr>				
				<tr>
					<td>
						<?php echo $user_income_arr['biz_name']; ?>
						<br/>
						<small>
							<?php echo $user_income_arr['full_name']; ?>
						</small>
					</td>
					<td><?php echo number_format ($user_income_arr['hosting'],2); ?></td>
					<td><?php echo number_format ($user_income_arr['domain'],2); ?></td>
					<td><?php echo number_format ($user_income_arr['advertyzing_global'],2); ?></td>
					<td><?php echo $user_income_arr['leads_count']; ?></td>
					<td><?php echo $user_income_arr['billed_leads_count']; ?></td>
					
					<td><?php echo $user_income_arr['deal_closed_count']; ?></td>
					<td><?php echo $user_income_arr['payByPassword0']; ?></td>
						
					<td><?php echo $user_list[$user_id]['leads_count_total']; ?></td>
					<td><?php echo $user_list[$user_id]['billed_leads_count_total']; ?></td>
					
					<td><?php echo number_format ($user_list[$user_id]['lead_price'],2); ?></td>
					<td><?php echo number_format ($user_income_arr['leads'],2); ?></td>
					<td><?php echo number_format ($user_income_arr['sum_all'],2); ?></td>
				</tr>
				<?php if((isset($user_income_arr['lead_list']) || isset($user_income_arr['refunded_leads']) || isset($user_income_arr['payByPassword0_lead_list'])) && isset($_GET['show_leads'])): ?>
					
					<tr>
						<td colspan="9">
							<?php
								$call_sector_style = ' style="background:#a6ffde;" ';
								$call_sector_style_red = ' style="background:#ffd1d1;" ';
								$call_sector_style_yellow = ' style="background:#a6d1ff;" '; 
							?>
							<table border="1"  cellpadding="3"  style="margin:10px;border-collapse: collapse;" >

								<tr>
									<th>שעה</th>
									<th>קטגוריה</th>
									<th>תיוג</th>
									<th>שם</th>
									<th>טלפון</th>
									<th>הגיע מ</th>
									<th>IP</th>
									<th>קמפיין</th>
									<th>סטטוס</th>
									<th>הצעת מחיר</th>
									<th <?php echo $call_sector_style; ?>>סטטוס שיחה</th>
									<th <?php echo $call_sector_style; ?>>זמן שיחה/הקלטה</th>
									<th <?php echo $call_sector_style; ?>>מספר שיחות ללקוח ממקור זה</th>
									<th <?php echo $call_sector_style; ?>>הפך לליד</th>
									
								</tr>
								<?php if(isset($user_income_arr['lead_list'])): foreach($user_income_arr['lead_list'] as $lead): ?>
									<?php 
										if($lead['resource'] == 'phone'){
											$times_called = '0';
											if($lead['phone'] == '0Anonymous'){
												$check_ef_data = false;
											}
											else{
												$check_ef_sql = "SELECT id FROM biz_requests WHERE phone = '".$lead['phone']."' LIMIT 1";
												$req = $db->prepare($check_ef_sql);
												$req->execute();
												$check_ef_data = $req->fetch();
												
												
												$times_called_sql = "SELECT count(id) as 'times_called' FROM user_phone_calls WHERE call_from = '".$lead['phone']."' AND user_id = ".$user_id."";
												$req = $db->prepare($times_called_sql);
												$req->execute();
												$times_called_data = $req->fetch();
												if($times_called_data){
													$times_called = $times_called_data['times_called'];
												}
                                            }
                                            
											$lead['has_ef'] = false;
											$lead['has_ef_str'] = "לא";
											$lead['has_ef_bg'] = "#fbc8c8";
											if($check_ef_data){
												$lead['has_ef'] = true;
												$lead['has_ef_str'] = "כן";
												$lead['has_ef_bg'] = "#7fcc7f";
											}
											else{
												$check_ef_sql = "SELECT lead_by_phone FROM misscalls_comments WHERE lead_id = ".$lead['id']." LIMIT 1";
												
                                                $req = $db->prepare($check_ef_sql);
                                                $req->execute();
                                                $check_ef_data = $req->fetch();	
												if($check_ef_data){
													$lead['has_ef'] = true;
													$lead['has_ef_str'] = $check_ef_data['lead_by_phone'];
													$lead['has_ef_bg'] = "#37ff37";
												}												
											}
										}
									?>
									<tr class="billed-0<?= $lead['billed'] ?>">
										<td><?php $date_in_arr = explode(" ",$lead['date_in']); echo $date_in_arr[1]; ?>
											<br/>
											<a target='_BLANK' href='<?= inner_url('myleads/quick_access/') ?>?user_id=<?= $user_income_arr['user_id'] ?>&lead_id=<?= $lead['id']; ?>'>צפייה במערכת ניהול לידים</a>
										</td>
                                        <td>
                                            <?php if($lead['c1'] != 0 && $lead['c1'] != ""): ?>
                                                <?php echo $cat_list_names[$lead['c1']]; ?><br/>
                                            <?php endif; ?>
                                            <?php if($lead['c2'] != 0 && $lead['c2'] != ""): ?>
                                                &nbsp&nbsp<?php echo $cat_list_names[$lead['c2']]; ?><br/>
                                            <?php endif; ?>	
                                            <?php if($lead['c3'] != 0 && $lead['c3'] != ""): ?>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $cat_list_names[$lead['c3']]; ?><br/>
                                            <?php endif; ?>											
                                        </td>
										<td><?php echo $lead['tag_name']; ?></td>
										<td><a target="_blank" href="<?= inner_url('biz_requests/view/') ?>?row_id=<?= $lead['request_id']; ?>"><?= $lead['full_name'] ?></a></td>
										<td>
											<?php echo $lead['phone']; ?>
											<?php if(!$lead['billed']): ?>
												<br/> לא חוייב
											<?php endif; ?>
										</td>
										<td><?php echo $lead['resource']; ?></td>
										<td><?php echo $lead['ip']; ?></td>
										<td><?php echo $lead['campaign_str']; ?> [<?php echo $lead['campaign_name']; ?>]</td>
										<td><?php echo $status_list[$lead['lead_status']]['str']; ?></td>
										<td><?php echo $lead['offer_amount']; ?></td>
										<?php 
                                            if(isset($lead['call_info'])): 
                                                $no_answer_call_style = ($lead['call_info']['answer'] == "MESSEGE")? $call_sector_style_yellow: $call_sector_style_red;
                                                $call_style = ($lead['call_info']['answer'] == "ANSWERED")? $call_sector_style: $no_answer_call_style; ?>
											<td <?php echo $call_style; ?>>
												<?php echo $lead['call_info']['answer']; ?>
												<?php if($lead['call_info']['extra'] != ""): ?>
													<br/>
													<?php echo $lead['call_info']['extra']; ?>
												<?php endif; ?>
											</td>										
											<td <?php echo $call_style; ?>>
												<?php if($lead['call_info']['billsec']!='0'): ?>
													<a target='_blank' href='<?= inner_url("link_recordings/download/") ?>?filename=<?php echo $lead['call_info']['recordingfile']; ?>'>
												<?php endif; ?>
													<?php echo $lead['call_info']['billsec']; ?>
												<?php if($lead['call_info']['billsec']!='0'): ?>
													</a>
												<?php endif; ?>
	
											</td>
											<td style='background:<?php echo $lead['has_ef_bg']; ?>'><?php echo $times_called ?></td>
											<td style='background:<?php echo $lead['has_ef_bg']; ?>'><?php echo $lead['has_ef_str']; ?></td>

		
											
										<?php endif; ?>
									</tr>									
								<?php endforeach; endif; ?>
								<?php if(isset($user_income_arr['refunded_leads'])): ?>
									<tr>

										<th colspan="5" style='color:red;'>זיכויים</th>
									
									</tr>
									<?php foreach($user_income_arr['refunded_leads'] as $refunded_lead): ?>
										<tr>
											<td><?php echo $refunded_lead['date_in']; ?></td>
											<td>
												<?php if($refunded_lead['c1'] != 0 && $refunded_lead['c1'] != ""): ?>
													<?php echo $cat_list_names[$refunded_lead['c1']]; ?><br/>
												<?php endif; ?>
												<?php if($refunded_lead['c2'] != 0 && $refunded_lead['c2'] != ""): ?>
													&nbsp&nbsp<?php echo $cat_list_names[$refunded_lead['c2']]; ?><br/>
												<?php endif; ?>	
												<?php if($refunded_lead['c3'] != 0 && $refunded_lead['c3'] != ""): ?>
													&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $cat_list_names[$refunded_lead['c3']]; ?><br/>
												<?php endif; ?>											
											</td>
											<td><?php echo $refunded_lead['tag_name']; ?></td>
											<?php
											
											/*
											echo "<a target='_blank' href='https://212.143.60.5/index.php?menu=monitoring&action=display_record&id=".$phone_lead_data['uniqueid']."&rawmode=yes' class='maintext'>��� ��� ������ �����</a><br/>";
											*/
											
											?>
                                            <td><a target="_blank" href="<?= inner_url('biz_requests/view/') ?>?row_id=<?= $refunded_lead['request_id']; ?>"><?= $refunded_lead['full_name'] ?></a></td>
											
											<td><?php echo $refunded_lead['phone']; ?></td>
											<td><?php echo $refunded_lead['resource']; ?></td>
											<td><?php echo $refunded_lead['campaign_str']; ?></td>	
											<?php if(isset($refunded_lead['call_info'])): 
                                                 $no_answer_call_style = ($refunded_lead['call_info']['answer'] == "MESSEGE")? $call_sector_style_yellow: $call_sector_style_red;
                                                 $call_style = ($refunded_lead['call_info']['answer'] == "ANSWERED")? $call_sector_style: $no_answer_call_style; ?>
 
                                                
                                               
												<td <?php echo $call_style; ?>>
													<?php echo $refunded_lead['call_info']['answer']; ?>
												</td>										
												<td <?php echo $call_style; ?>>
													<?php if($refunded_lead['call_info']['billsec']!='0'): ?>
                                                        <a target='_blank' href='<?= inner_url("link_recordings/download/") ?>?filename=<?php echo $refunded_lead['call_info']['recordingfile']; ?>'>
													<?php endif; ?>
													<?php echo $refunded_lead['call_info']['billsec']; ?>
													<?php if($refunded_lead['call_info']['billsec']!='0'): ?>
														</a>
													<?php endif; ?>													
												</td>
											<?php endif; ?>
										</tr>									
									<?php endforeach; ?>
											
								<?php endif; ?>		




								<?php /* payByPassword 0 leads only here ----- */ ?>
								<?php if(isset($user_income_arr['payByPassword0_lead_list'])): ?>
									<tr>

										<th colspan="5" style='color:red;'>מצב סגור</th>
									
									</tr>
									<?php foreach($user_income_arr['payByPassword0_lead_list'] as $payByPassword0_lead): ?>
										<tr>
											<td><?php echo $payByPassword0_lead['date_in']; ?></td>
											<td>
												<?php if($payByPassword0_lead['c1'] != 0 && $payByPassword0_lead['c1'] != ""): ?>
													<?php echo $cat_list_names[$payByPassword0_lead['c1']]; ?><br/>
												<?php endif; ?>
												<?php if($payByPassword0_lead['c2'] != 0 && $payByPassword0_lead['c2'] != ""): ?>
													&nbsp&nbsp<?php echo $cat_list_names[$payByPassword0_lead['c2']]; ?><br/>
												<?php endif; ?>	
												<?php if($payByPassword0_lead['c3'] != 0 && $payByPassword0_lead['c3'] != ""): ?>
													&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $cat_list_names[$payByPassword0_lead['c3']]; ?><br/>
												<?php endif; ?>											
											</td>
											<td><?php echo $payByPassword0_lead['tag_name']; ?></td>
											<?php
											
											/*
											echo "<a target='_blank' href='https://212.143.60.5/index.php?menu=monitoring&action=display_record&id=".$phone_lead_data['uniqueid']."&rawmode=yes' class='maintext'>��� ��� ������ �����</a><br/>";
											*/
											
											?>
											<td><a target="_blank" href="<?= inner_url('biz_requests/view/') ?>?row_id=<?= $payByPassword0_lead['request_id']; ?>"><?= $payByPassword0_lead['full_name'] ?></a></td>
											<td><?php echo $payByPassword0_lead['phone']; ?></td>
											<td><?php echo $payByPassword0_lead['resource']; ?></td>
											<td><?php echo $payByPassword0_lead['campaign_str']; ?></td>	
											
											<?php if(isset($payByPassword0_lead['call_info'])): 
                                                 $no_answer_call_style = ($payByPassword0_lead['call_info']['answer'] == "MESSEGE")? $call_sector_style_yellow: $call_sector_style_red;
                                                 $call_style = ($payByPassword0_lead['call_info']['answer'] == "ANSWERED")? $call_sector_style: $no_answer_call_style;  ?>
 
                                                

												<td <?php echo $call_style; ?>>
													<?php echo $payByPassword0_lead['call_info']['answer']; ?>
												</td>										
												<td <?php echo $call_style; ?>>
													<?php if($payByPassword0_lead['call_info']['billsec']!='0'): ?>
                                                        <a target='_blank' href='<?= inner_url("link_recordings/download/") ?>?filename=<?php echo $payByPassword0_lead['call_info']['recordingfile']; ?>'>
													<?php endif; ?>
													<?php echo $payByPassword0_lead['call_info']['billsec']; ?>
													<?php if($payByPassword0_lead['call_info']['billsec']!='0'): ?>
														</a>
													<?php endif; ?>													
												</td>
											<?php endif; ?>
										</tr>									
									<?php endforeach; ?>
											
								<?php endif; ?>	

								<?php /* payByPassword 0 leads only untill here ----- */ ?>


								
							</table>
						</td>
					</tr>

				<?php endif; ?>
			
			<?php endforeach; endif; ?>
			
			</table>
			
		<?php endforeach; ?>
	<?php endif; ?>
	
	<?php
    }
  }

?>
<style type="text/css">

	tr.billed-00 td{
		background: #ffb4b4;
	}
</style>