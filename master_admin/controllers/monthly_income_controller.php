<?php
  class Monthly_incomeController extends CrudController{


    protected function get_cat_id_in($cat_filter){
      if($cat_filter == '0'){
        return "";
      }
      $this->add_model('biz_categories');
      $cat_filter = "51";
      $cat_offsprings = Biz_categories::simple_get_item_offsprings($cat_filter,'id,parent,label');
      $cat_id_arr = array($cat_filter);
      foreach($cat_offsprings as $cat){
        $cat_id_arr[] = $cat['id'];
      }
      $cat_id_in = implode(",",$cat_id_arr);
      return $cat_id_in;
    }

    public function report(){

      $db = Db::getInstance();
      $date_from_str = date("m-Y");
      if(isset($_GET['date_from'])){
        $date_from_str = trim($_GET['date_from']);
      }
      $date_from_arr = explode("-",$date_from_str);
      $date_from_sql =$date_from_arr[1]."-".$date_from_arr[0]."-01";
      $date_from_sort = $date_from_arr[1]."-".$date_from_arr[0];
      $date_to_str = date("m-Y");
      if(isset($_GET['date_to'])){
        $date_to_str = trim($_GET['date_to']);
      }
      $date_to_arr = explode("-",$date_to_str);
      $date_to_sql_1 = $date_to_arr[1]."-".$date_to_arr[0];    
      $date_to_sql = date('Y-m-01', strtotime("+1 month", strtotime($date_to_sql_1)));
      $date_to_sort = date('Y-m', strtotime("+1 month", strtotime($date_to_sql_1)));
      
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
      
      $sql = "SELECT user.id as user_id, full_name, advertisingPrice ,advertisingStartDate,lead_price,domainEndDate,hostPriceMon,domainPrice,end_date 
          FROM users user 
          LEFT JOIN user_bookkeeping book ON book.user_id = user.id
          LEFT JOIN user_lead_settings uls ON uls.user_id = user.id
          LEFT JOIN user_lead_visability ulv ON ulv.user_id = user.id
          WHERE uls.end_date > '$date_from_sql' $user_name_sql $user_cat_sql AND show_in_income_reports = 1 AND user.active = 1 AND uls.active = 1";	
      
      $req = $db->prepare($sql);
      $req->execute();
      $res = $req->fetchAll();
//ok here
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
      $user_id_list = array();
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
          
          "domainPrice"=>$user['domainPrice']/12,
          
          "hostPriceMon"=>$user['hostPriceMon'],
                  
          "advertisingPrice"=>$user['advertisingPrice'],
          "leads"=>$user['lead_price'],
        );
        $user['static_costs'] = $user_static_costs;
        $user['leads_count_total'] = 0;
        $user['deal_closed_count'] = 0;
        $user_list[$user['user_id']] = $user;
        $user_id_list[$user['user_id']] = $user['user_id'];
      } 
      $user_id_in_sql = implode(",",$user_id_list);
      
      $lead_campaign_sql = "";	
      if(isset($_REQUEST['add_campaign_leads'])){
        $lead_campaign_sql = " AND (lead.resource = 'none'";
        if(isset($_REQUEST['add_reg_leads'])){
          $lead_campaign_sql .= " OR req.campaign_type = '0'  OR lead.campaign_type = '0' ";
        }
        if(isset($_REQUEST['add_gl_leads'])){
          $lead_campaign_sql .= " OR req.campaign_type = '1'  OR lead.campaign_type = '1' ";
        }		
        if(isset($_REQUEST['add_fb_leads'])){
          $lead_campaign_sql .= " OR req.campaign_type = '2'  OR lead.campaign_type = '2' ";
        }
        
        $lead_campaign_sql .= ")";
      }
      
      $phone_leads_sql = "";
      if(isset($_REQUEST['phone_leads_remove'])){
        $phone_leads_sql = " AND lead.resource = 'form' ";
      }		
      $lead_cat_sql = "";	
      if(isset($_REQUEST['cat_leads_only'])){
        $lead_cat_sql = " AND ((lead.resource != 'form' AND user.id IN($user_id_in_sql)) OR req.cat_id IN ($cat_id_in)) ";
      }
      $lead_billed_sql = "";
      if(!isset($_REQUEST['add_unbilled_leads'])){
        $lead_billed_sql = " AND lead.billed = 1 ";
      }	
      
      $sql = "SELECT req.id as req_id, req.c1, req.c2, req.c3, req.c4, user.id as user_id,lead.date_in,lead.status as lead_status,open_state,request_id,resource,billed,phone_id,cat_id,lead.full_name,lead.phone,req.campaign_type as req_campaign_type, lead.campaign_type as campaign_type, upc.billsec,upc.answer
          FROM user_leads lead 
          LEFT JOIN users user ON user.id = lead.user_id
          LEFT JOIN user_lead_settings uls ON user.id = uls.user_id
          LEFT JOIN user_lead_visability ulv ON ulv.user_id = user.id
          LEFT JOIN biz_requests req ON req.id = lead.request_id
          LEFT JOIN user_phone_calls upc ON upc.id = lead.phone_id
          WHERE uls.end_date > '$date_from_sql' $phone_leads_sql $lead_cat_sql $lead_campaign_sql $user_name_sql AND show_in_income_reports = 1 AND user.active = 1 AND lead.date_in > '$date_from_sql' AND lead.date_in < '$date_to_sql' $lead_billed_sql";	      
      
      $req = $db->prepare($sql);
      $req->execute();
      $res = $req->fetchAll();

      $lead_list = array();
      $customer_leads_list = array();
      $campaign_str = array('0'=>'רגיל','1'=>'גוגל','2'=>'פייסבוק');
      $refunded_leads = array();
      $notbilled_leads = array();
      $closed_deal_leads = array();
      $customer_phone_billsec = array();
      foreach($res as $lead){
        if($lead['campaign_type'] == ""){
          $lead['campaign_type'] = "0";
        }
        $lead['billsec_str'] = "--";
        if($lead['resource'] == 'phone'){

          $lead['campaign_str'] = $campaign_str[$lead['campaign_type']];
          if($lead['answer'] == "NO ANSWER"){
            $lead['billsec_str'] = "לא נענתה";
          }
          else{
            $lead['billsec_str'] = $lead['billsec'];
          }
        }
        else{
          $lead['campaign_str'] = $campaign_str[$lead['campaign_type']];
          $lead['billsec'] = 0;
        }
        if($lead['billsec'] == ""){
          $lead['billsec'] = 0;
        }
    
        $lead_date_arr = explode(" ",$lead['date_in']);
        
        $lead_date = $lead_date_arr[0];
        $lead_month_arr = explode("-",$lead_date);
        $lead_month = $lead_month_arr[0]."-".$lead_month_arr[1];
        if($lead['billed'] == '1'){
          if($lead['lead_status'] != '6'){
            $lead_list[$lead_month][$lead['user_id']][] = $lead;
            $customer_leads_list[$lead['user_id']][$lead_month]['good'][] = $lead;
            if($lead['lead_status'] == '2'){
              $closed_deal_leads[$lead_month][$lead['user_id']][] = $lead;
            }			
          }
          else{
            $refunded_leads[$lead_month][$lead['user_id']][] = $lead;
            $customer_leads_list[$lead['user_id']][$lead_month]['refunded'][] = $lead;
          }
        }
        else{
          $notbilled_leads[$lead_month][$lead['user_id']][] = $lead;
          $customer_leads_list[$lead['user_id']][$lead_month]['notbilled'][] = $lead;			
        }
        if(!isset($customer_phone_billsec[$lead['user_id']])){
          $customer_phone_billsec[$lead['user_id']] = array();
        }
        if(!isset($customer_phone_billsec[$lead['user_id']][$lead_month])){
          $customer_phone_billsec[$lead['user_id']][$lead_month] = 0;
        }
        $customer_phone_billsec[$lead['user_id']][$lead_month] += $lead['billsec'];
      }
      //echo "<pre>";
      //print_r($lead_list);
      //echo "</pre>";
     
      foreach($user_id_list as $user_id){
        if(!isset($customer_leads_list[$user_id])){
          $customer_leads_list[$user_id] = array();  
        }
      }
      $all_months_income = array();
      $monthly_income_arr = array();
      foreach($user_list as $user_id=>$user_details){
        
        $sql = "SELECT * FROM user_lead_settings WHERE user_id = $user_id";
        $req = $db->prepare($sql);
        $req->execute();
        $settings_data = $req->fetch();
        $user_list[$user_id]['user_lead_settings'] = $settings_data;
      }
      foreach($customer_leads_list as $user_id=>$month_leads){
        
        if(!isset($user_list[$user_id])){
          $missing_user_sql = "SELECT user.id as user_id,full_name, advertisingPrice ,advertisingStartDate,lead_price,domainEndDate,hostPriceMon,domainPrice,end_date 
          FROM users user 
          LEFT JOIN user_bookkeeping book ON book.user_id = user.id
          LEFT JOIN user_lead_settings uls ON uls.user_id = user.id
          WHERE user.id = $user_id ";
          

          $req = $db->prepare($missing_user_sql);
          $req->execute();
          $missing_user_data = $req->fetch();
          
          $missing_user_data['missing'] = '1';
          if($missing_user_data['domainPrice'] == ""){
            $missing_user_data['domainPrice'] = 0;
          }
          if($missing_user_data['hostPriceMon'] == ""){
            $missing_user_data['hostPriceMon'] = 0;
          }
          if($missing_user_data['advertisingPrice'] == ""){
            $missing_user_data['advertisingPrice'] = 0;
          }
          if($missing_user_data['lead_price'] == ""){
            $missing_user_data['lead_price'] = 0;
          }		
          $user_static_costs = array(
            
            "domainPrice"=>$missing_user_data['domainPrice']/12,
            
            "hostPriceMon"=>$missing_user_data['hostPriceMon'],
                    
            "advertisingPrice"=>$missing_user_data['advertisingPrice'],
            "leads"=>$missing_user_data['lead_price'],
          );
          $missing_user_data['static_costs'] = $user_static_costs;
          $missing_user_data['leads_count_total'] = 0;
          $user_list[$missing_user_data['user_id']] = $missing_user_data;
        }
          
        $row_date = $date_from_sort;	
          $all_months_income[$user_id] = array(
            "domain"=>0, //domainPrice
            "hosting"=>0, //hostPriceMon
            "advertyzing_global"=>0, //advertisingPrice
            "leads_count"=>0, //leads
            "deal_closed_count"=>0, //leads
            "refunded_leads_count"=>0,//refunded leads
            "refunded_leads_precent"=>0,//refunded leads
            "leads"=>0, //leads
            "sum_all"=>0, //leads
            "billsec_sum"=>0, //time talking on the phone
          );
        $last_user_lead_count = 0;
        while($row_date < $date_to_sort){
          //echo $row_date."<br/>";
          $monthly_income_arr[$user_id] = array(
            "domain"=>0, //domainPrice
            "hosting"=>0, //hostPriceMon
            "advertyzing_global"=>0, //advertisingPrice
            "leads"=>0, //leads
            "deal_closed_count"=>0,
            "billsec_sum"=>0,
            "leads_count"=>0,
            "refunded_leads_count"=>0
          ); 
          
          $user = $user_list[$user_id];
          
          if($user['end_date'] >= $row_date){
            
            $user_income_row = array();
            $user_income_row['full_name'] = "<a target='_blank' href='".inner_url('users/edit/?row_id='.$user['user_id'])."'>".$user['full_name']."</a>";
            if(isset($customer_leads_list[$user_id][$row_date]['good'])){
    
              $user_lead_count = count($month_leads[$row_date]['good']);
              if($user['lead_price'] != 0 && $user['lead_price'] != ""){
                $user_lead_monthly_outcome = $user_lead_count*$user['lead_price'];
                $monthly_income_arr[$user_id]['leads']+=$user_lead_monthly_outcome;
                $user_income_row['leads']=$user_lead_monthly_outcome;
              }					
              $user_income_row['leads_count']=$user_lead_count;
              $user_income_row['lead_list'] = $month_leads;
              if(isset($closed_deal_leads[$row_date][$user['user_id']])){						
                $user_income_row['closed_deal_leads'] = $closed_deal_leads[$row_date][$user['user_id']];
                $user_income_row['deal_closed_count'] = count($user_income_row['closed_deal_leads']);
                $monthly_income_arr['deal_closed_count']+=$user_income_row['deal_closed_count'];
              }
              else{
                $user_income_row['deal_closed_count'] = 0;
              }					
            }
            else{
              $user_income_row['leads_count']=0;		
            }
            
            if(isset($customer_leads_list[$user_id][$row_date]['refunded'])){
              $user_refunded_lead_count = count($month_leads[$row_date]['refunded']);
              $user_income_row['refunded_leads_count']=$user_refunded_lead_count;
            }
            else{
              $user_income_row['refunded_leads_count']=0;		
            }	
            $user_income_row['refunded_leads_precent']=0;
            if($user_income_row['refunded_leads_count'] != 0){
              if($user_income_row['leads_count'] == 0){
                $user_income_row['refunded_leads_precent'] = 100;
              }
              else{
                $lead_count = $user_income_row['leads_count'];
                $refunded_leads_count = $user_income_row['refunded_leads_count'];
                $refunded_leads_precent = $refunded_leads_count*100/($lead_count+ $refunded_leads_count);
                $user_income_row['refunded_leads_precent'] = $refunded_leads_precent;
              }
            }	
                      
            $user_income_row['lead_count_compare'] = '0';
            
            if($user_income_row['leads_count'] > $last_user_lead_count){
              $user_income_row['lead_count_compare'] = '1';
            }
            if($user_income_row['leads_count'] < $last_user_lead_count){
              $user_income_row['lead_count_compare'] = '-1';
            }
                    
            $last_user_lead_count = $user_income_row['leads_count'];
            $monthly_income_arr[$user_id]['leads_count']+=
            $user_income_row['leads_count'];
            $monthly_income_arr[$user_id]['deal_closed_count']+=
            isset($user_income_row['deal_closed_count'])?$user_income_row['deal_closed_count']:0;
            
            $monthly_income_arr[$user_id]['refunded_leads_count']+=$user_income_row['refunded_leads_count'];
            if(isset($refunded_leads[$row_date][$user['user_id']])){				
              $user_income_row['refunded_leads'] = $refunded_leads[$row_date][$user['user_id']];
            }
            $monthly_income_arr[$user_id]['lead_count_compare'] = $user_income_row['lead_count_compare'];
            $user_list[$user_id]['leads_count_total']+=$user_income_row['leads_count'];
            $monthly_income_arr[$user_id]['hosting']+=$user['static_costs']['hostPriceMon'];
            $user_income_row['hosting']=$user['static_costs']['hostPriceMon'];
            if($user['domainEndDate'] >= $row_date){
              $monthly_income_arr[$user_id]['domain']+=$user['static_costs']['domainPrice'];
              $user_income_row['domain']=$user['static_costs']['domainPrice'];
            }
            else{
              $user_income_row['domain']=0;
            }
            if($user['advertisingStartDate'] <= $row_date){
              $monthly_income_arr[$user_id]['advertyzing_global']+=$user['static_costs']['advertisingPrice'];
              $user_income_row['advertyzing_global']=$user['static_costs']['advertisingPrice'];
            }
            $lead_count = isset($user_income_row['leads'])?$user_income_row['leads']:0;
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
              $lead_count; 
              
            if($user_income_row['sum_all'] != 0 || isset($user_income_row['lead_list']))
            {
              $monthly_income_arr[$user_id]['user'][$user['user_id']] = $user_income_row;
            }
          }
          
          if(isset($customer_phone_billsec[$user_id][$row_date])){
            $monthly_income_arr[$user_id]['billsec_sum'] = $customer_phone_billsec[$user_id][$row_date];
          }
          
          $monthly_income_arr[$user_id]['sum_all'] = $monthly_income_arr[$user_id]['hosting'] + $monthly_income_arr[$user_id]['domain'] + $monthly_income_arr[$user_id]['advertyzing_global'] + $monthly_income_arr[$user_id]['leads'];
          $income_arr[$user_id][$row_date] = $monthly_income_arr[$user_id];
          $row_date = date('Y-m', strtotime("+1 month", strtotime($row_date)));	
          
          $all_months_income[$user_id]['domain']+=$monthly_income_arr[$user_id]['domain'];
          $all_months_income[$user_id]['hosting']+=$monthly_income_arr[$user_id]['hosting'];
          $all_months_income[$user_id]['advertyzing_global']+=$monthly_income_arr[$user_id]['advertyzing_global'];
          $all_months_income[$user_id]['leads_count']+=$monthly_income_arr[$user_id]['leads_count'];
          $all_months_income[$user_id]['refunded_leads_count']+=$monthly_income_arr[$user_id]['refunded_leads_count'];
          $all_months_income[$user_id]['deal_closed_count']+=$monthly_income_arr[$user_id]['deal_closed_count'];
          $all_months_income[$user_id]['leads']+=$monthly_income_arr[$user_id]['leads'];
          $all_months_income[$user_id]['billsec_sum']+=$monthly_income_arr[$user_id]['billsec_sum'];
          $all_months_income[$user_id]['sum_all']+=$monthly_income_arr[$user_id]['sum_all'];
          
          
        }
        $all_months_income[$user_id]['sum_all_2'] = $all_months_income[$user_id]['domain']+$all_months_income[$user_id]['hosting']+$all_months_income[$user_id]['advertyzing_global']+$all_months_income[$user_id]['leads'];
        if($all_months_income[$user_id]['leads_count']+$all_months_income[$user_id]['refunded_leads_count'] == '0'){
          $all_months_income[$user_id]['refunded_leads_precent'] = 0;
        }
        else{

          $all_months_income[$user_id]['refunded_leads_precent'] = 100*$all_months_income[$user_id]['refunded_leads_count']/($all_months_income[$user_id]['leads_count']+$all_months_income[$user_id]['refunded_leads_count']);
        }
      }
      $compare_colors = array(
        '0'=>'#efefef',
        '1'=>'#c4ffc4',
        '-1'=>'#ffdfdf',
      );
      ?>
      
      
      <h3>
        דוח הכנסות חודשיות
      </h3>
    
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
        <form action="<?= inner_url("monthly_income/report/") ?>" method="GET">
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
            $add_unbilled_leads_checked	= "";
            if(isset($_GET['add_unbilled_leads'])){
              $add_unbilled_leads_checked = "checked";
            }				
          ?>
          
          
          <br/>
          <input type="checkbox"  name="add_unbilled_leads" value="0" <?php echo $add_unbilled_leads_checked; ?>/> צפה בלידים לא מחוייבים(לחישוב זמני שיחות טלפון כללי) &nbsp&nbsp&nbsp
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
            
            if(isset($_GET['phone_leads_remove'])){
              $phone_leads_remove_checked = "checked";
            }				
          ?>			
          
          <span id="sub_cat_place_holder">
          
          </span>
          <input type="checkbox"  name="cat_leads_only" value="1" <?php echo $cat_leads_only_checked; ?>/> הוסף רק לידים ששייכים לקטגוריה &nbsp&nbsp&nbsp
          <input type="checkbox"  name="phone_leads_remove" value="1" <?php echo $phone_leads_remove_checked; ?>/> הסר לידים טלפוניים &nbsp&nbsp&nbsp
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
          
          <input type="checkbox"  name="show_leads" value="1" <?php echo $show_leads_checked; ?>/> הצג פרוט לידים ללקוח &nbsp&nbsp&nbsp
          <input type="submit" value="הצג" />
        </form>
      </div>
      
      <?php foreach($income_arr as $user_id=>$user_income_arr): ?>
        
        <h3><?php echo $user_list[$user_id]['full_name']; ?>
          <?php if(isset($user_list[$user_id]['missing'])): ?>
          <b style="color:red">(הלקוח לא התקבל בתוצאות החיפוש, והתווסף בגלל לידים שקיבל)</b>
          <?php endif; ?>
        </h3>
        
        <hr/>
        <table border="1" cellpadding="3" style="border-collapse: collapse;">
          <tr>
            <th>חודש</th>
            <th>אחסון</th>
            <th>דומיין</th>
            <th>פרסום</th>
            <td>כמות לידים</td>
            <td>החזרים</td>
            <td>החזרים באחוזים</td>
            <td>יתרת לידים</td>
            <td>מחיר ליד</td>
            <th>לידים</th>
            <th>סגירה עם לקוח</th>
            <th>זמן טלפון</th>
            <th>סך הכל</th>
          </tr>
          <?php foreach($user_income_arr as $month=>$month_income_arr): ?>
            
            <?php $month_arr = explode("-",$month); $month_str = $month_arr[1]."-".$month_arr[0]; ?>
            <tr style='background:<?php echo $compare_colors[$month_income_arr['lead_count_compare']]; ?>;'>
              <th><?php echo $month_str; ?></td>
              <td><?php echo number_format ($month_income_arr['hosting'],2); ?></td>
              <td><?php echo number_format ($month_income_arr['domain'],2); ?></td>
              <td><?php echo number_format ($month_income_arr['advertyzing_global'],2); ?></td>
              <td><?php echo $month_income_arr['leads_count']; ?></td>
              <td><?php echo $month_income_arr['user'][$user_id]['refunded_leads_count']; ?></td>
              <td><?php echo number_format($month_income_arr['user'][$user_id]['refunded_leads_precent'],2); ?>%</td>					
              <td></td>
              <td><?php echo number_format ($user_list[$user_id]['lead_price'],2); ?></td>
              <td><?php echo number_format ($month_income_arr['leads'],2); ?></td>
              <td><?php echo $month_income_arr['deal_closed_count']; ?></td>
              <td><?php echo $month_income_arr['billsec_sum']; ?></td>
              <td><?php echo number_format ($month_income_arr['sum_all'],2); ?></td>
            </tr>
            
            
          <?php endforeach; ?>
          <tr>
            <th style="color:green">סיכום</td>
            <td style="color:green"><?php echo number_format ($all_months_income[$user_id]['hosting'],2); ?></td>
            <td style="color:green"><?php echo number_format ($all_months_income[$user_id]['domain'],2); ?></td>
            <td style="color:green"><?php echo number_format ($all_months_income[$user_id]['advertyzing_global'],2); ?></td>
            <td style="color:green"><?php echo $all_months_income[$user_id]['leads_count']; ?></td>
            <td><?php echo $all_months_income[$user_id]['refunded_leads_count']; ?></td>
            <td><?php echo number_format ($all_months_income[$user_id]['refunded_leads_precent'],2); ?>%</td>
            <td><?php echo $user_list[$user_id]['user_lead_settings']['lead_credit']; ?></td>
            <td><?php echo number_format ($user_list[$user_id]['lead_price'],2); ?></td>
            <td style="color:green"><?php echo number_format ($all_months_income[$user_id]['leads'],2); ?></td>
            <td style="color:green"><?php echo $all_months_income[$user_id]['deal_closed_count']; ?></td>
            <td><?php echo $all_months_income[$user_id]['billsec_sum']; ?></td>
            <td style="color:green">
              <?php echo number_format ($all_months_income[$user_id]['sum_all'],2); ?>
              <br/>
              <small>
                <?php echo number_format ($all_months_income[$user_id]['sum_all_2'],2); ?>
              </small>
            </td>
          </tr>
        </table>
        
        <?php if((isset($customer_leads_list[$user_id]) || isset($customer_leads_list[$user_id])) && isset($_GET['show_leads'])): ?>
          <h5>פרוט לידים</h5>
    
            <table border="1"  cellpadding="3"  style="margin:10px;border-collapse: collapse;" >
              <tr>
                <th colspan="7">לידים</th>
              </tr>
              <tr>
                <th>יום</th>
                <th>שעה</th>
                <th>קטגוריה</th>
                <th>שם</th>
                <th>טלפון</th>
                <th>הגיע מ</th>
                <th>זמן שיחה</th>						
                <th>קמפיין</th>
                <th>סטטוס</th>
              </tr>
              <?php foreach($customer_leads_list[$user_id] as $month=>$lead_list): ?>
                <?php if(isset($lead_list['good'])): foreach($lead_list['good'] as $lid=>$lead): ?>
                  <tr>
                    <?php $date_in_arr = explode(" ",$lead['date_in']); ?>
                    <td><?php echo $date_in_arr[0]; ?></td>
                    <td><?php echo $date_in_arr[1]; ?></td>
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
                    <td><a target="_blank" href="<?= inner_url('biz_requests/view/') ?>?row_id=<?= $lead['request_id']; ?>"><?= $lead['full_name'] ?></a></td>
                    <td><?php echo $lead['phone']; ?></td>
                    <td><?php echo $lead['resource']; ?></td>
                    <td><?php echo $lead['billsec_str']; ?></td>
                    <td><?php echo $lead['campaign_str']; ?></td>
                    <td><?php echo $status_list[$lead['lead_status']]['str']; ?></td>
                  </tr>	
                <?php endforeach; endif; ?>						
              <?php endforeach; ?>
    
                <tr>
    
                  <th colspan="7" style='color:red;'>זיכויים</th>
                
                </tr>
                <?php foreach($customer_leads_list[$user_id] as $month=>$lead_list): ?>
                  <?php if(isset($lead_list['refunded'])): foreach($lead_list['refunded'] as $lid=>$lead): ?>
                    <tr>
                      <?php $date_in_arr = explode(" ",$lead['date_in']); ?>
                      <td><?php echo $date_in_arr[0]; ?></td>
                      <td><?php echo $date_in_arr[1]; ?></td>
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
                      <td><a target="_blank" href="<?= inner_url('biz_requests/view/') ?>?row_id=<?= $lead['request_id']; ?>"><?php echo $lead['full_name']; ?></a></td>
                      <td><?php echo $lead['phone']; ?></td>
                      <td><?php echo $lead['resource']; ?></td>
                      <td><?php echo $lead['billsec_str']; ?></td>
                      <td><?php echo $lead['campaign_str']; ?></td>
                      <td><?php echo $status_list[$lead['lead_status']]['str']; ?></td>
                    </tr>	
                  <?php endforeach;  endif; ?>						
                <?php endforeach; ?>
    
    
                <tr>
    
                  <th colspan="7" style='color:red;'>לידים לא מחוייבים</th>
                
                </tr>
                <?php foreach($customer_leads_list[$user_id] as $month=>$lead_list): ?>
                  <?php foreach($lead_list['notbilled'] as $lid=>$lead): ?>
                    <tr>
                      <?php $date_in_arr = explode(" ",$lead['date_in']); ?>
                      <td><?php echo $date_in_arr[0]; ?></td>
                      <td><?php echo $date_in_arr[1]; ?></td>
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
                      <td><a target="_blank" href="<?= inner_url('biz_requests/view/') ?>?row_id=<?= $lead['request_id']; ?>"><?php echo $lead['full_name']; ?></a></td>
                      <td><?php echo $lead['phone']; ?></td>
                      <td><?php echo $lead['resource']; ?></td>
                      <td><?php echo $lead['billsec_str']; ?></td>
                      <td><?php echo $lead['campaign_str']; ?></td>
                      <td><?php echo $status_list[$lead['lead_status']]['str']; ?></td>
                    </tr>	
                  <?php endforeach; ?>						
                <?php endforeach; ?>						
                            
            </table>
              
        <?php endif; ?>		
        
        
      <?php endforeach; ?>
    
      
      <?php
    }
  }

?>