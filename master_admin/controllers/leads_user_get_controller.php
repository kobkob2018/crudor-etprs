<?php
  class leads_user_getController extends CrudController{
    public $add_models = array("biz_categories","net_directories");

    function report()
    {
        $db = Db::getInstance();
        $client_name_sql = (isset($_GET['clientName']) &&  $_GET['clientName'] != "" ) ? " AND user.full_name LIKE '%".$_GET['clientName']."%' AND " : "";
        
        $defualt_s_date = ( isset($_GET['s_date']) && $_GET['s_date'] != "" ) ? $_GET['s_date'] : date('01-m-Y');
        $defualt_e_date = ( isset($_GET['e_date']) && $_GET['e_date'] != "" ) ? $_GET['e_date'] : date('d-m-Y');
        
        $ex_s = explode("-",$defualt_s_date);
        $s_date = ( $defualt_s_date != "" ) ? "AND sent.date_in >= '".$ex_s[2]."-".$ex_s[1]."-".$ex_s[0]."' " : "";
        $ex_e = explode("-",$defualt_e_date);
        $e_date = ( $defualt_e_date != "" ) ? "AND sent.date_in <= '".$ex_e[2]."-".$ex_e[1]."-".$ex_e[0]."' " : "";
        
        $sql = "SELECT ulv.* ,uls.*, user.full_name AS clientName
            FROM users user 
			LEFT JOIN user_lead_visability ulv ON ulv.user_id = user.id 
			LEFT JOIN user_lead_settings uls ON uls.user_id = user.id 
			WHERE 
                ulv.show_in_leads_report = '1' 
                ".$client_name_sql."
        ";
        
        $req = $db->prepare($sql);
        $req->execute();
        $res = $req->fetchAll();
        


        $sum_total_leads = 0;
        $sum_total_leads_to_pay = 0;
        
        $status_list = array(
            '0'=>array('str'=>'מתעניין חדש','id'=>'0'),
            '5'=>array('str'=>'מחכה לטלפון','id'=>'5'),
            '1'=>array('str'=>'נוצר קשר','id'=>'1'),
            '2'=>array('str'=>'סגירה עם לקוח','id'=>'2'),
            '3'=>array('str'=>'לקוח רשום','id'=>'3'),
            '4'=>array('str'=>'לא רלוונטי','id'=>'4'),
            '6'=>array('str'=>'הליד זוכה','id'=>'5'),
        );		
        
        echo "<table class=\"maintext-oldsys\" cellpadding=0 cellspacing=0>";
		echo "<tr><td colspan=6 height=10></td></tr>";
		echo "<tr>";
			echo "<td colspan=6>";
            echo "<form action='".inner_url("leads_user_get/report/")."' name='serachForm' method='get' style='padding:0;margin:0'>";

				echo "<table class=\"maintext-oldsys\" cellpadding=0 cellspacing=0>";
					echo "<tr>";
						echo "<td>שם לקוח</td>";
						echo "<td width=10></td>";
						echo "<td><input type='text' name='clientName' value='".(isset($_GET['clientName'])?$_GET['clientName']:"")."' class='input_style' style='width: 100px;' /></td>";
						echo "<td width=40></td>";
						echo "<td>מספור לידים בין התאריך</td>";
						echo "<td width=10></td>";
						echo "<td><input type='text' name='s_date' value='".$defualt_s_date."' class='input_style' style='width: 100px;' /></td>";
						echo "<td width=20></td>";
						echo "<td>לתאריך</td>";
						echo "<td width=10></td>";
						echo "<td><input type='text' name='e_date' value='".$defualt_e_date."' class='input_style' style='width: 100px;' /></td>";
						echo "<td width=40></td>";
						echo "<td><input type='submit' value='חפש!' class='submit_style'></td>";
					echo "</tr>";
				echo "</table>";
				echo "</form>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td colspan=6 height=20></td></tr>";
		echo "<tr>";
			echo "<th>שם הלקוח</th>";
			echo "<th>הודעות SMS</th>";
			echo "<th>הודעות צור קשר</th>";
			echo "<th>שליחה חופשית</th>";
			echo "<th>יתרת הודעות</th>";
			echo "<th>מספור לידים בין התאריכים</th>";
		echo "</tr>";
		echo "<tr bgcolor='#000000'><td colspan=6 height=1></td></tr>";
        $sum_leads=0;
        $counter=0;
        
        $s_date4 = ( $defualt_s_date != "" ) ? "AND date_in >= '".$ex_s[2]."-".$ex_s[1]."-".$ex_s[0]."' " : "";
        $e_date4 = ( $defualt_e_date != "" ) ? "AND date_in <= '".$ex_e[2]."-".$ex_e[1]."-".$ex_e[0]."' " : "";
            
    
            
            foreach($res as $data)
            {
                $total_form_leads = 0;
                
                $total_form_leads_paybypswd = 0;
                $total_form_leads_paybypswd_closed = 0;
                $total_form_leads_status_2 = 0;
                $total_form_leads_billed = 0;
                $total_form_leads_doubled = 0;
                $total_form_leads_refunded = 0;
    
                $total_phone_leads = 0;
                $total_phone_leads_paybypswd = 0;
                $total_phone_leads_paybypswd_closed = 0;
                $total_phone_leads_status_2 = 0;
                $total_phone_leads_billed = 0;
                $total_phone_leads_doubled = 0;
                $total_phone_leads_refunded = 0;
                
                $form_leads_arr = array();
                $phone_leads_arr = array();
                $phone_leads_id_arr = array();
                $sql_check_u1 = "SELECT  * FROM user_leads WHERE user_id = '" . $data['user_id'] . "' ".$s_date4.$e_date4;		
                
                $req = $db->prepare($sql_check_u1);
                $req->execute();
                $res = $req->fetchAll();

                foreach ($res as $data_check_u1) {
                    if ($data_check_u1['resource'] == 'form') {
                        $form_leads_arr[] = $data_check_u1;
                    }
                    else{
                        $phone_leads_id_arr[] = $data_check_u1['phone_id'];
                        $phone_leads_arr[] = $data_check_u1;
                    }
                }
                foreach($form_leads_arr as $form_lead){
                    
    
                    $total_form_leads++;
                    if($form_lead['status'] == '2' && $form_lead['billed'] == '1'){
                        $total_form_leads_status_2++;
                    }				
                    if($form_lead['open_state'] == '1'){
                        $total_form_leads_paybypswd++;
                    }
                    else{
                        $total_form_leads_paybypswd_closed++;
                    }
                    if($form_lead['billed'] == '1'){
                        $total_form_leads_billed++;
                    }
                    else{
                        if($form_lead['duplicate_id'] != '' && $form_lead['duplicate_id'] != '0'){
                            
                            $total_form_leads_doubled++;
                        }
                        elseif($form_lead['open_state'] == "1" && $form_lead['request_id'] == "0"){
                            $total_form_leads_billed++;
                        }
                    }
                    if($form_lead['status'] == '6'){
                        $total_form_leads_refunded++;
                    }				
                }
                $total_form_leads_to_pay = $total_form_leads_billed - $total_form_leads_refunded;
                foreach($phone_leads_arr as $phone_lead){
                    $total_phone_leads++;
                    if($phone_lead['status'] == '2'  && $phone_lead['billed'] == '1'){
                        $total_phone_leads_status_2++;
                    }				
                    if($phone_lead['open_state'] == '1'){
                        $total_phone_leads_paybypswd++;
                    }
                    else{
                        $total_phone_leads_paybypswd_closed++;
                    }
                    if($phone_lead['billed'] == '1'){
                        $total_phone_leads_billed++;
                    }
                    else{
                        if($phone_lead['duplicate_id'] != '' && $phone_lead['duplicate_id'] != '0'){
                            $total_phone_leads_doubled++;
                        }
                    }
                    if($phone_lead['status'] == '6'){
                        $total_phone_leads_refunded++;
                    }				
                }			
                $total_phone_leads_to_pay = $total_phone_leads_billed - $total_phone_leads_refunded;
        
                
                $sum_total_leads = $sum_total_leads + $total_form_leads + $total_phone_leads;
                $sum_total_leads_to_pay = $sum_total_leads_to_pay + $total_form_leads_to_pay + $total_phone_leads_to_pay;
                
    
                
                $havaSms = ( $data['send_lead_sms_alerts'] == "1" ) ? "כן" : "לא";
                $haveContact = ( $data['send_lead_email_alerts'] == "1" ) ? "כן" : "לא";
                $free_send = ( $data['free_send'] == "1" ) ? "כן" : "לא";
                
    			$bgcolor = ( $counter%2 == 0 ) ? "F9F9F9" : "F3F3F3";
			echo "<tr bgcolor='#".$bgcolor."'><td colspan=6 height=3></td></tr>";
			echo "<tr bgcolor='#".$bgcolor."' onmouseover='style.backgroundColor=\"#BBBBFF\"' onmouseout='style.backgroundColor=\"#".$bgcolor."\"'>";
				echo "<td><a href='".inner_url("user_lead_settings/list/?user_id=").$data['user_id']."' class='maintext' target='_blank'>".$data['clientName']."</a></td>";
				echo "<td align=center>".$havaSms."</td>";
				echo "<td align=center>".$haveContact."</td>";
				echo "<td align=center>".$free_send."</td>";
				echo "<td align=center>".$data['lead_credit']."</td>";
				echo "<td>";
					echo "<table border='1' cellpadding=5 cellspacing=0 width=100% aligh=right>";
						
						echo "<tr>";
							echo "<td>";
								echo "מקור הלידים";
							echo "</td>";
							echo "<td>";
								echo "נשלחו";
							echo "</td>";						
							echo "<td>";
								echo "פתוחים";
							echo "</td>";								
							echo "<td>";
								echo "סגורים";
							echo "</td>";	
	
							echo "<td>";
								echo "חוייבו";
							echo "</td>";
							echo "<td>";
								echo "כפולים";
							echo "</td>";
							echo "<td>";
								echo $status_list[2]['str'];
							echo "</td>";
						
							echo "<td>";
								echo "זוכו";
							echo "</td>";
							echo "<td>";
								echo 'סה"כ לתשלום';
							echo "</td>";							
						echo "</tr>";						
						

						echo "<tr>";
						
							echo "<td>";
								echo "טופס";
							echo "</td>";
												
						
							echo "<td>";
								
								echo $total_form_leads;
							echo "</td>";
						
						
							echo "<td>";
								echo $total_form_leads_paybypswd;
							echo "</td>";
											

						
							echo "<td>";
								echo $total_form_leads_paybypswd_closed;
							echo "</td>";
						

						
							echo "<td>";
								echo $total_form_leads_billed;
							echo "</td>";
						

						
							echo "<td>";	
								echo $total_form_leads_doubled;
							echo "</td>";
							echo "<td>";	
								echo $total_form_leads_status_2;
							echo "</td>";						

						
							echo "<td>";
								echo $total_form_leads_refunded;
							echo "</td>";
						
							echo "<td>";
								echo $total_form_leads_to_pay;
							echo "</td>";
						
						echo "</tr>";
						echo "<tr>";
						
							echo "<td>";
								echo "טלפון";
							echo "</td>";
												
						
							echo "<td>";
								
								echo $total_phone_leads;
							echo "</td>";
						
						
							echo "<td>";
								echo $total_phone_leads_paybypswd;
							echo "</td>";
											

						
							echo "<td>";
								echo $total_phone_leads_paybypswd_closed;
							echo "</td>";
						

						
							echo "<td>";
								echo $total_phone_leads_billed;
							echo "</td>";
						

						
							echo "<td>";	
								echo $total_phone_leads_doubled;
							echo "</td>";
						
							echo "<td>";	
								echo $total_phone_leads_status_2;
							echo "</td>";	
						
							echo "<td>";
								echo $total_phone_leads_refunded;
							echo "</td>";
						
							echo "<td>";
								echo $total_phone_leads_to_pay;
							echo "</td>";
						
						echo "</tr>";

						echo "<tr><td colspan='20'>";
							echo "סך הכל לתשלום: ";
							echo "<a href='".inner_url('leads_user_get/user_csv/')."?withouthtml=1&user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo $total_form_leads_to_pay+$total_phone_leads_to_pay;
							echo "</a>";
							
							
							echo " | <a href='".inner_url('leads_user_get/user_csv/')."?user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo "צפה בדוח כאן";
							echo "</a>";
							echo " | <a href='".inner_url('leads_user_get/user_csv/')."?advanced_report=1&user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo "צפה בדוח מתקדם";
							echo "</a>";
							echo " | <a href='".inner_url('leads_user_get/user_csv/')."?withouthtml=1&advanced_report=1&user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo "הורד דוח מתקדם";
							echo "</a>";							
						echo "</td></tr>";
					echo "</table>";
				echo "</td>";
			echo "</tr>";
			echo "<tr bgcolor='#".$bgcolor."'><td colspan=6 height=3></td></tr>";
			echo "<tr bgcolor='#000000'><td colspan=6 height=1></td></tr>";
			$counter++;
            }
    
            echo "<tr>";
			echo "<td colspan=5>";
				echo "<font style='color: green;'>סך הכל מחזור לידים: <b>".$sum_total_leads."</b></font>";
			echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
			echo "<td colspan=5>";
				echo "<font style='color: green;'>סך הכל לידים לתשלום: <b>".$sum_total_leads_to_pay."</b></font>";
			echo "</td>";
		echo "</tr>";
		
	echo "</table>";
    }

    public function user_csv(){
        $db = Db::getInstance();
        if(isset($_REQUEST['phones_only'])){
            return $this->user_csv_phones_only();
        }
        $defualt_s_date = ( isset($_GET['s_date']) && $_GET['s_date'] != "" ) ? $_GET['s_date'] : date('1-m-Y');
        $defualt_e_date = ( isset($_GET['e_date']) && $_GET['e_date'] != "" ) ? $_GET['e_date'] : date('d-m-Y');
        
        $ex_s = explode("-",$defualt_s_date);
        $s_date = ( $defualt_s_date != "" ) ? $ex_s[2]."-".$ex_s[1]."-".$ex_s[0] : "";
        $ex_e = explode("-",$defualt_e_date);
        $e_date = ( $defualt_e_date != "" ) ? $ex_e[2]."-".$ex_e[1]."-".$ex_e[0] : "";
        
        $uid = $_GET['user_id'];
        $user_id = $uid;
        $tagin_sql = "SELECT * FROM user_lead_tag WHERE user_id = $uid";

        $req = $db->prepare($tagin_sql);
        $req->execute();
        $tagin_res = $req->fetchAll();
        $tagin_arr = array('0'=>array('id'=>'0','user_id'=>$uid,'tag_name'=>'ללא תגית'));
        foreach($tagin_res as $tag){
            $tagin_arr[$tag['id']] = $tag;
        }

        $status_options = array(
            '0'=>'מתעניין חדש',
            '5'=>'מחכה לטלפון',
            '1'=>'נוצר קשר',
            '2'=>'סגירה עם לקוח',
            '3'=>'לקוח רשום',
            '4'=>'לא רלוונטי',
            '6'=>'הליד זוכה',
        );	
        $row_leads_arr = array();
    
        $total_form_leads = 0;
        
        $total_form_leads_paybypswd = 0;
        $total_form_leads_paybypswd_closed = 0;
        
        $total_form_leads_billed = 0;
        $total_form_leads_doubled = 0;
        $total_form_leads_refunded = 0;
        $total_form_leads_status_closed = 0;
        
        $total_phone_leads = 0;
        $total_phone_leads_paybypswd = 0;
        $total_phone_leads_paybypswd_closed = 0;
        $total_phone_leads_billed = 0;
        $total_phone_leads_doubled = 0;
        $total_phone_leads_refunded = 0;
        $total_phone_leads_status_closed = 0;
        
        
        $total_to_pay = 0;
        $form_leads_arr = array();
        $phone_leads_arr = array();
        $form_leads_paybypswd_arr = array();
        $phone_leads_paybypswd_arr = array();
        $form_leads_doubled_arr = array();
        $phone_leads_doubled_arr = array();
        $doubled_phones_found_arr = array();
        $phones_found_arr = array();
        $advanced_report = false;
        if(isset($_GET['advanced_report'])){
            $advanced_report = true;
        }
        $sql_check_u1 = "SELECT  * FROM user_leads WHERE user_id = '" . $user_id . "'  AND date_in >= '".$s_date."' AND date_in <= '".$e_date."'";
        
        $req = $db->prepare($sql_check_u1);
        $req->execute();
        $res_check_u1 = $req->fetchAll();
	
        foreach ($res_check_u1 as $data_check_u1) {
            $date_in = $data_check_u1['date_in'];
            $month_check_arr = explode("-",$date_in);
            $month_check = $month_check_arr[1];
            $data_check_u1['month_check'] = $month_check;
            if ($data_check_u1['resource'] == 'form') {
                
                $form_leads_arr[] = $data_check_u1;
            }
            else{
                $phone_leads_arr[] = $data_check_u1;
            }
        }
        foreach($form_leads_arr as $form_lead){
            $total_form_leads++;
            $is_doubled = false;
            $doubled_found = false;
            $form_lead['resource_str'] = "טופס באתר";
            $form_lead['opened_str'] = "לא";
            $form_lead['refunded_str'] = "לא";
            if($form_lead['open_state'] == '1'){
                $total_form_leads_paybypswd++;
                $form_lead['opened_str'] = "כן";
                if($form_lead['billed'] == '1'){
                    $total_form_leads_billed++;
                    if(isset($phones_found_arr[$form_lead['month_check']][$form_lead['phone']])){
                        
                        $doubled_phones_found_arr[$form_lead['phone']] = $form_lead;
                    }
                    else{
                        $phones_found_arr[$form_lead['month_check']][$form_lead['phone']] = $form_lead;
                    }
                }
                else{
                    if($form_lead['duplicate_id'] != '' && $form_lead['duplicate_id'] != '0'){
                        
                        $is_doubled = true;
                        $total_form_leads_doubled++;
                    }
                    elseif($form_lead['open_state'] == "1" && $form_lead['request_id'] == "0"){
                        $form_lead['resource_str'] = "טופס צור קשר באתר הלקוח";
                        //$total_form_leads_billed++;
                    }
                }					
            }
            else{
                $total_form_leads_paybypswd_closed++;
                $form_lead['phone'] = "*****";
            }
    
            if($form_lead['status'] == '6'){
                $total_form_leads_refunded++;
                $form_lead['refunded_str'] = "כן";
            }
            if($form_lead['status'] == '2'  && $form_lead['billed'] == '1'){
                $total_form_leads_status_closed++;
            }		
            
            
            if($is_doubled){
                $form_leads_doubled_arr[] = $form_lead;
            }
            else{			
                $form_leads_paybypswd_arr[] = $form_lead;
            }
        }
        $total_form_leads_to_pay = $total_form_leads_billed - $total_form_leads_refunded;
        foreach($phone_leads_arr as $phone_lead){
            $total_phone_leads++;
            $is_doubled = false;
            $phone_lead['refunded_str'] = "לא";
            $phone_lead['opened_str'] = "לא";
            if($phone_lead['open_state'] == '1'){
                $total_phone_leads_paybypswd++;
                $phone_lead['opened_str'] = "כן";
                if($phone_lead['billed'] == '1'){
                    $total_phone_leads_billed++;
                    if(isset($phones_found_arr[$phone_lead['month_check']][$phone_lead['phone']])){
                        $doubled_phones_found_arr[$phone_lead['phone']] = $phone_lead;
                    }
                    else{
                        $phones_found_arr[$phone_lead['month_check']][$phone_lead['phone']] = $phone_lead;
                    }				
                }
                else{
                    if($phone_lead['duplicate_id'] != '' && $phone_lead['duplicate_id'] != '0'){
                        
                        $is_doubled = true;
                        $total_phone_leads_doubled++;
                    }
                    elseif($phone_lead['open_state'] == "1" && $phone_lead['request_id'] == "0"){
                        $total_phone_leads_billed++;
                    }
                }					
            }
            else{
                $total_phone_leads_paybypswd_closed++;
                $phone_lead['phone'] = "*****";
            }
    
            if($phone_lead['status'] == '6'){
                $total_phone_leads_refunded++;
                $phone_lead['refunded_str'] = "כן";
            }
            if($phone_lead['status'] == '2' && $phone_lead['billed'] == '1'){
                $total_phone_leads_status_closed++;
            }		
            
            if($is_doubled){
                $phone_leads_doubled_arr[] = $phone_lead;
            }
            else{
                $phone_leads_paybypswd_arr[] = $phone_lead;
            }
        }
        if($advanced_report){
            $customer_types_str = array("new"=>"חדש","new_back"=>"חדש חוזר","back"=>"קיים חוזר","shimur"=>"שימור");
            $customer_type_phones = array();
            $customer_types_count = array("new"=>0,"new_back"=>0,"back"=>0,"shimur"=>0);
            foreach($form_leads_paybypswd_arr as $key=>$lead){
                $lead['previous_sends'] = array();
                $phone_check = trim($lead['phone']);
                if($phone_check == ""){
                    continue;
                }

                
                $sql_prev = "SELECT  * FROM user_leads WHERE user_id = $user_id  AND phone = '$phone_check' AND id != ".$lead['id']."";

                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
                
                foreach ($res_prev as $prev_lead){
                    $lead['previous_sends'][] = $prev_lead;
                }	
                $sql_firstcall = "SELECT * FROM user_leads WHERE user_id = $user_id AND phone = '$phone_check' ORDER BY date_in LIMIT 1";
                
                $req = $db->prepare($sql_firstcall);
                $req->execute();
                $res_firstcall = $req->fetchAll();

                $lead['firstcall'] = "";
                foreach ($res_firstcall as $firstcall_lead){
                    $lead['firstcall'] .= $firstcall_lead['date_in'];
                }

                $lead['previous_imports'] = array();
                $sql_prev = "SELECT  * FROM private_contacts_imports WHERE user_id = $user_id  AND phone = '$phone_check'";
                
                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
                
                			
                foreach ($res_prev as $prev_lead){
                    $prev_lead['shimur'] = "shimur";
                    $prev_lead_date = $prev_lead['update_time'];
                    $lead['previous_imports'][] = $prev_lead;
                    
                }
                if(!isset($customer_type_phones[$lead['phone']])){
                    $customer_type_phones[$lead['phone']] = "new";
                    if(!empty($lead['previous_sends'])){
                        $customer_type_phones[$lead['phone']] = "new_back";
                    }
                    if(!empty($lead['previous_imports'])){
                        $customer_type_phones[$lead['phone']] = $lead['previous_imports'][0]['shimur'];
                    }
                    $customer_types_count[$customer_type_phones[$lead['phone']]]++;
                }
                $form_leads_paybypswd_arr[$key] = $lead;
            }
            foreach($phone_leads_paybypswd_arr as $key=>$lead){
                $phone_check = trim($lead['phone']);
                if($phone_check == ""){
                    continue;
                }
                $lead['previous_sends'] = array();
                $sql_prev = "SELECT  * FROM user_leads WHERE user_id = $user_id  AND phone = '$phone_check' AND id != ".$lead['id']."";

                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
                
                foreach ($res_prev as $prev_lead){
                    $lead['previous_sends'][] = $prev_lead;
                }	
                $sql_firstcall = "SELECT * FROM user_leads WHERE  user_id = $user_id AND phone = '$phone_check' ORDER BY date_in LIMIT 1";
                
                $req = $db->prepare($sql_firstcall);
                $req->execute();
                $res_firstcall = $req->fetchAll();

                $lead['firstcall'] = "";
                foreach ($res_firstcall as $firstcall_lead){
                    $lead['firstcall'] .= $firstcall_lead['date_in'];
                }			
                $lead['previous_imports'] = array();
                $sql_prev = "SELECT  * FROM private_contacts_imports WHERE user_id = $user_id  AND phone = '$phone_check'";
                
                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
                   
                foreach ($res_prev as $prev_lead){
                    
                    $prev_lead['shimur'] = "shimur";
                    $prev_lead_date = $prev_lead['update_time'];
                    $sql_shimur = "SELECT * FROM user_leads WHERE  user_id = $user_id AND phone = '$phone_check' AND date_in BETWEEN '$prev_lead_date' AND DATE_ADD('$prev_lead_date', INTERVAL 4 MONTH) LIMIT 1";
                    
                    $req = $db->prepare($sql_shimur);
                    $req->execute();
                    $res_shimur = $req->fetchAll();
		
                    foreach ($res_shimur as $shimur_lead){
                        $prev_lead['shimur'] = "back";
                    }
                    
                    $lead['previous_imports'][] = $prev_lead;
                }
                if(!isset($customer_type_phones[$lead['phone']])){
                    $customer_type_phones[$lead['phone']] = "new";
                    if(!empty($lead['previous_sends'])){
                        $customer_type_phones[$lead['phone']] = "new_back";
                    }
                    if(!empty($lead['previous_imports'])){
                        $customer_type_phones[$lead['phone']] = $lead['previous_imports'][0]['shimur'];
                    }
                    $customer_types_count[$customer_type_phones[$lead['phone']]]++;
                }
                $phone_leads_paybypswd_arr[$key] = $lead;
            }	
        }
        $total_phone_leads_to_pay = $total_phone_leads_billed - $total_phone_leads_refunded;
    
        
        $sum_total_leads = $total_form_leads + $total_phone_leads;
        $sum_total_leads_paybypswd_closed = $total_form_leads_paybypswd_closed + $total_phone_leads_paybypswd_closed;
        $sum_total_leads_paybypswd = $total_form_leads_paybypswd + $total_phone_leads_paybypswd;
        $sum_total_leads_billed = $total_form_leads_billed + $total_phone_leads_billed;
        $sum_total_leads_refunded = $total_form_leads_refunded + $total_phone_leads_refunded;
        $sum_total_leads_status_closed = $total_form_leads_status_closed + $total_phone_leads_status_closed;
        
        $sum_total_leads_doubled = $total_form_leads_doubled + $total_phone_leads_doubled;
        $sum_total_leads_to_pay = $total_form_leads_to_pay + $total_phone_leads_to_pay;
    
        
        $row_leads_arr[] = array('רשימת טופסי צור קשר:');
        if(!isset($_GET['withouthtml'])){	
            $lead_h_list = array('תאריך', 'שם מלא', 'דוא"ל', 'טלפון','פתוח','סטטוס','תיוג' , 'מקור הליד','קיבל זיכוי', 'תוכן','בקשה לזיכוי');
        }
        else{
            $lead_h_list = array('תאריך', 'שם מלא', 'דוא"ל', 'טלפון','פתוח','סטטוס','תיוג' , 'מקור הליד','קיבל זיכוי', 'תוכן' );
        }
        if($advanced_report){
            $lead_h_list[] = "סוג לקוח";
            $lead_h_list[] = "התקשרות ראשונה מהאתר";
        }
        $row_leads_arr[] = $lead_h_list;
    
        foreach($form_leads_paybypswd_arr as $lead){
            $lead_row = array( stripslashes($lead['date_in']) , stripslashes($lead['full_name']) , stripslashes($lead['email']) , stripslashes($lead['phone']), $lead['opened_str'] ,$status_options[$lead['status']] ,$tagin_arr[$lead['tag']]['tag_name']  , $lead['resource_str'],$lead['refunded_str'] , stripslashes($lead['note']));
            if(!isset($_GET['withouthtml'])){
                $lead_row[] = "<a target='_BLANK' href='".inner_url('refund_requests/add_request/')."lead_id=".$lead['id']."'>שלח בקשה לזיכוי</a>";
            }
            if($advanced_report){
                $lead_row[] = $customer_types_str[$customer_type_phones[$lead['phone']]];
                $lead_row[] = $lead['firstcall'];
            }		
            $row_leads_arr[] = $lead_row;
            if(isset($lead['previous_sends'])){
                
                if(!empty($lead['previous_sends'])){
                    $row_leads_arr[] = array("פניות קודמות","פניות קודמות","פניות קודמות");
                    foreach($lead['previous_sends'] as $prev_lead){
                        $billed_str = "לא חוייב";
                        if($prev_lead['billed'] == '1'){
                            $billed_str = "חוייב";
                        }
                        if($prev_lead['resource'] == 'form'){
                            $prev_lead_row = array( stripslashes($prev_lead['date_in']) , stripslashes($prev_lead['full_name']) , stripslashes($prev_lead['email']) ,$billed_str, $prev_lead['opened_str']  ,'טופס באתר',$prev_lead['refunded_str'] , stripslashes($prev_lead['content']));
    
                            
                        }
                        else{
                            $prev_resource = 'טלפון';
                            $sql3 = "SELECT sms_send,call_from,answer,call_date,billsec  FROM user_phone_calls WHERE id = ".$prev_lead['phone_id']."";
                            
                            $req = $db->prepare($sql3);
                            $req->execute();
                            $call_data = $req->fetch();
                            $answ = ( $call_data['billsec'] == '0' ) ? "ללא מענה" : "שיחה של ".$call_data['billsec']." שניות";
                            $prev_lead_row = array( stripslashes($call_data['call_date']) , '' , '' ,$billed_str,''  , 'מערכת טלפונייה',$prev_lead['refunded_str']  , $answ);
    
                        }
                        $row_leads_arr[] = $prev_lead_row;
                    }
                    $row_leads_arr[] = array("---","----","----");
                }
            }
    
            if(isset($lead['previous_imports'])){
                
                if(!empty($lead['previous_imports'])){
                    $row_leads_arr[] = array("ייבוא טלפון","ייבוא טלפון","ייבוא טלפון");
                    foreach($lead['previous_imports'] as $prev_lead){
                        
                        $prev_lead_row = array( stripslashes($prev_lead['update_time']) , "-----" , "-----" ,"-----", "------"  ,'ייבוא מהלקוח',"----" , "------");
    
                        $row_leads_arr[] = $prev_lead_row;
                    }
                    $row_leads_arr[] = array("---","----","----");
                }
            }
    
            
        }
        $row_leads_arr[] = array("----");
        $row_leads_arr[] = array('כפילויות בטפסים');
        foreach($form_leads_doubled_arr as $lead){
            $row_leads_arr[] = array( stripslashes($lead['date_in']) , stripslashes($lead['full_name']) , stripslashes($lead['email']) , stripslashes($lead['phone']), $lead['opened_str'],$status_options[$lead['status']], $tagin_arr[$lead['tag']]['tag_name']  , 'טופס באתר',$lead['refunded_str'] , stripslashes($lead['content']));
        }
    
        
        $row_leads_arr[] = array("----");
        $row_leads_arr[] = array('רשימת טלפונים שהתקבלו:');
        foreach($phone_leads_paybypswd_arr as $lead){				
            //$row_leads_arr[] = array( stripslashes($lead['date_in']) , stripslashes($lead['full_name']) , stripslashes($lead['email']) , stripslashes($lead['phone']), $lead['opened_str'] , stripslashes($lead['content']) , 'טופס באתר',$lead['refunded_str'] );
            $sql3 = "SELECT sms_send,call_from,answer,call_date,billsec  FROM user_phone_calls WHERE id = ".$lead['phone_id']."";
            
            $req = $db->prepare($sql3);
            $req->execute();
            $call_data = $req->fetch();

            $answ = ( $call_data['billsec'] == '0' ) ? "ללא מענה" : "שיחה של ".$call_data['billsec']." שניות";
            $lead_row = array( stripslashes($call_data['call_date']) , '' , '' , stripslashes($call_data['call_from']),'',$status_options[$lead['status']] ,$tagin_arr[$lead['tag']]['tag_name'] , 'מערכת טלפונייה',$lead['refunded_str']  , $answ);
            if(!isset($_GET['withouthtml'])){
                $lead_row[] = "<a target='_BLANK' href='".inner_url('refund_requests/add_request/')."lead_id=".$lead['id']."'>שלח בקשה לזיכוי</a>";     
            }		
            if($advanced_report){
                $lead_row[] = $customer_types_str[$customer_type_phones[$lead['phone']]];
                $lead_row[] = $lead['firstcall'];
            }
            $row_leads_arr[] = $lead_row; 
            if(isset($lead['previous_sends'])){
                
                if(!empty($lead['previous_sends'])){
                    $row_leads_arr[] = array("פניות קודמות","פניות קודמות","פניות קודמות");
                    foreach($lead['previous_sends'] as $prev_lead){
                        $billed_str = "לא חוייב";
                        if($prev_lead['billed'] == '1'){
                            $billed_str = "חוייב";
                        }
                        if($prev_lead['resource'] == 'form'){
                            $prev_lead_row = array( stripslashes($prev_lead['date_in']) , stripslashes($prev_lead['full_name']) , stripslashes($prev_lead['email']) ,$billed_str, $prev_lead['opened_str']  ,'טופס באתר',$prev_lead['refunded_str'] , stripslashes($prev_lead['content']));
    
                            
                        }
                        else{
                            $prev_resource = 'טלפון';
                            $sql3 = "SELECT sms_send,call_from,answer,call_date,billsec  FROM user_phone_calls WHERE id = ".$prev_lead['phone_id']."";
                            
                            $req = $db->prepare($sql3);
                            $req->execute();
                            $call_data = $req->fetch();
                            
                            $answ = ( $call_data['billsec'] == '0' ) ? "ללא מענה" : "שיחה של ".$call_data['billsec']." שניות";
                            $prev_lead_row = array( stripslashes($call_data['call_date']) , '' , '' ,$billed_str,'',$status_options[$lead['status']] ,$tagin_arr[$lead['tag']]['tag_name'] , 'מערכת טלפונייה',$prev_lead['refunded_str']  , $answ);
    
                        }
                        $row_leads_arr[] = $prev_lead_row;
                    }
                    $row_leads_arr[] = array("---","----","----");
                }
            }
            if(isset($lead['previous_imports'])){
                
                if(!empty($lead['previous_imports'])){
                    $row_leads_arr[] = array("ייבוא טלפון","ייבוא טלפון","ייבוא טלפון");
                    foreach($lead['previous_imports'] as $prev_lead){
                        
                        $prev_lead_row = array( stripslashes($prev_lead['update_time']) , "-----" , "-----" ,"-----", "------" , "------"  ,'ייבוא מהלקוח',"----" , "------");
    
                        $row_leads_arr[] = $prev_lead_row;
                    }
                    $row_leads_arr[] = array("---","----","----");
                }
            }		
        }			
        $row_leads_arr[] = array("----");
        $row_leads_arr[] = array('כפילויות טלפונים');
        foreach($phone_leads_doubled_arr as $lead){				
            //$row_leads_arr[] = array( stripslashes($lead['date_in']) , stripslashes($lead['full_name']) , stripslashes($lead['email']) , stripslashes($lead['phone']), $lead['opened_str'] , stripslashes($lead['content']) , 'טופס באתר',$lead['refunded_str'] );
            $sql3 = "SELECT sms_send,call_from,answer,call_date,billsec  FROM user_phone_calls WHERE id = ".$lead['phone_id']."";
            
            $req = $db->prepare($sql3);
            $req->execute();
            $call_data = $req->fetch();
            
            $answ = ( $call_data['billsec'] == '0' ) ? "ללא מענה" : "שיחה של ".$call_data['billsec']." שניות";
            $row_leads_arr[] = array( stripslashes($call_data['call_date']) , '' , '' , stripslashes($call_data['call_from']) ,'',$status_options[$lead['status']],$tagin_arr[$lead['tag']]['tag_name']  , 'מערכת טלפונייה',$lead['refunded_str'], $answ );				
        }	
        if(!isset($_GET['withouthtml'])){	
            $row_leads_arr[] = array("----");  
            $row_leads_arr[] = array("כפילויות שלא זוהו"); 
    
            foreach($doubled_phones_found_arr as $lead){				
                $row_leads_arr[] = array( stripslashes($lead['date_in']) , stripslashes($lead['full_name']) , stripslashes($lead['email']) , stripslashes($lead['phone']), $lead['opened_str'],$status_options[$lead['status']],$tagin_arr[$lead['tag']]['tag_name']  , $lead['resource'],$lead['refunded_str'] , stripslashes($lead['content']));
            }	
        }
        $row_leads_arr[] = array("----");  
        $row_leads_arr[] = array("סיכום פניות"); 
        $row_leads_arr[] = array("מקור הפנייה","נשלחו","מצב פתוח","(לא מחוייב)מצב סגור","חוייבו","כפילויות","זוכו","סגירה עם לקוח","סך הכל לחיוב"); 
        $row_leads_arr[] = array("טופס",$total_form_leads,$total_form_leads_paybypswd,$total_form_leads_paybypswd_closed,$total_form_leads_billed,$total_form_leads_doubled,$total_form_leads_refunded,$total_form_leads_status_closed,$total_form_leads_to_pay); 
        $row_leads_arr[] = array("טלפון",$total_phone_leads,$total_phone_leads_paybypswd,$total_phone_leads_paybypswd_closed,$total_phone_leads_billed,$total_phone_leads_doubled,$total_phone_leads_refunded,$total_phone_leads_status_closed,$total_phone_leads_to_pay); 
        $row_leads_arr[] = array("");
        $row_leads_arr[] = array("");  
        $row_leads_arr[] = array("סך הכל",$sum_total_leads,$sum_total_leads_paybypswd,$sum_total_leads_paybypswd_closed,$sum_total_leads_billed,$sum_total_leads_doubled,$sum_total_leads_refunded,$sum_total_leads_status_closed,$sum_total_leads_to_pay); 
        if($advanced_report){
            $row_leads_arr[] = array("מספור"," לקוחות","לפי סוג לקוח","חדש","קיים-חוזר","חדש-חוזר","שימור");
            $row_leads_arr[] = array("","","",$customer_types_count['new'],$customer_types_count['back'],$customer_types_count['new_back'],$customer_types_count['shimur']);
        }	
    
        if(!isset($_GET['withouthtml'])){
            echo "<div><a href='".current_url(array("phones_only"=>"1"))."'>לחץ כאן לצפות במספרי הטלפון בלבד</a></div>";		
                
            echo "<table border=1 style='direction:rtl; text-align:right; border-collapse: collapse;'>";
            $max_cols = 0;
            foreach($row_leads_arr as $row){
                $cols_count = count($row);
                if($cols_count > $max_cols){
                    $max_cols = $cols_count;
                }
            }
            foreach($row_leads_arr as $row){
                echo "<tr>";
                    $col_count = 0;
                    foreach($row as $col){
                        echo "<td>".$col."</td>";
                        $col_count ++;
                    }
                    $col_left = $max_cols - $col_count;
                    if($col_left > 0){
                        echo "<td colspan='".$col_left."'></td>";
                    }
                echo "</tr>";
            }
            echo "</table>";
            
        }
        else{
            $this->set_layout('blank');
            return Helper::array_to_csv_download($row_leads_arr);
            
        }  
    }

    public function user_csv_phones_only(){
        $db = Db::getInstance();
        $defualt_s_date = ( isset($_GET['s_date']) && $_GET['s_date'] != "" ) ? $_GET['s_date'] : date('1-m-Y');
        $defualt_e_date = ( isset($_GET['e_date']) && $_GET['e_date'] != "" ) ? $_GET['e_date'] : date('d-m-Y');
        
        $ex_s = explode("-",$defualt_s_date);
        $s_date = ( $defualt_s_date != "" ) ? $ex_s[2]."-".$ex_s[1]."-".$ex_s[0] : "";
        $ex_e = explode("-",$defualt_e_date);
        $e_date = ( $defualt_e_date != "" ) ? $ex_e[2]."-".$ex_e[1]."-".$ex_e[0] : "";
        
        
        $user_id = $_GET['user_id'];
        $leadCounter=0;
        $row_leads_arr = array();
    
      // SQL query that get by the user unk - the user contact forms between the date of the date right now -1 , and the the day before a month.
    
    
        $total_form_leads = 0;
        
        $total_form_leads_paybypswd = 0;
        $total_form_leads_paybypswd_closed = 0;
        
        $total_form_leads_billed = 0;
        $total_form_leads_doubled = 0;
        $total_form_leads_refunded = 0;
    
        $total_phone_leads = 0;
        $total_phone_leads_paybypswd = 0;
        $total_phone_leads_paybypswd_closed = 0;
        $total_phone_leads_billed = 0;
        $total_phone_leads_doubled = 0;
        $total_phone_leads_refunded = 0;
        
        $total_to_pay = 0;
        $form_leads_arr = array();
        $phone_leads_arr = array();
        $form_leads_paybypswd_arr = array();
        $phone_leads_paybypswd_arr = array();
        $form_leads_doubled_arr = array();
        $phone_leads_doubled_arr = array();
        $doubled_phones_found_arr = array();
        $phones_found_arr = array();
        $advanced_report = false;
        if(isset($_GET['advanced_report'])){
                $advanced_report = true;
        }
        $sql_check_u1 = "SELECT  * FROM user_leads WHERE user_id = '" . $user_id . "'  AND date_in >= '".$s_date."' AND date_in <= '".$e_date."'";
        
        $req = $db->prepare($sql_check_u1);
        $req->execute();
        $res_check_u1 = $req->fetchAll();
		
        foreach ($res_check_u1 as $data_check_u1) {
            $date_in = $data_check_u1['date_in'];
            $month_check_arr = explode("-",$date_in);
            $month_check = $month_check_arr[1];
            $data_check_u1['month_check'] = $month_check;
            if ($data_check_u1['resource'] == 'form') {
                
                $form_leads_arr[] = $data_check_u1;
            }
            else{
                $phone_leads_arr[] = $data_check_u1;
            }
        }
        foreach($form_leads_arr as $form_lead){
            $total_form_leads++;
            $is_doubled = false;
            $doubled_found = false;
            
            $form_lead['opened_str'] = "לא";
            $form_lead['refunded_str'] = "לא";
            if($form_lead['open_state'] == '1'){
                $total_form_leads_paybypswd++;
                $form_lead['opened_str'] = "כן";
                if($form_lead['billed'] == '1'){
                    $total_form_leads_billed++;
                    if(isset($phones_found_arr[$form_lead['month_check']][$form_lead['phone']])){
                        
                        $doubled_phones_found_arr[$form_lead['phone']] = $form_lead;
                    }
                    else{
                        $phones_found_arr[$form_lead['month_check']][$form_lead['phone']] = $form_lead;
                    }
                }
                else{
                    if($form_lead['duplicate_id'] != '' && $form_lead['duplicate_id'] != '0'){
                        
                        $is_doubled = true;
                        $total_form_leads_doubled++;
                    }
                    elseif($form_lead['open_state'] == "1" && $form_lead['request_id'] == "0"){
                        //$total_form_leads_billed++;
                    }
                }					
            }
            else{
                $total_form_leads_paybypswd_closed++;
                $form_lead['phone'] = "*****";
            }
    
            if($form_lead['status'] == '6'){
                $total_form_leads_refunded++;
                $form_lead['refunded_str'] = "כן";
            }
            
            if($is_doubled){
                $form_leads_doubled_arr[] = $form_lead;
            }
            else{			
                $form_leads_paybypswd_arr[] = $form_lead;
            }
        }
        $total_form_leads_to_pay = $total_form_leads_billed - $total_form_leads_refunded;
        foreach($phone_leads_arr as $phone_lead){
            $total_phone_leads++;
            $is_doubled = false;
            $phone_lead['refunded_str'] = "לא";
            $phone_lead['opened_str'] = "לא";
            if($phone_lead['open_state'] == '1'){
                $total_phone_leads_paybypswd++;
                $phone_lead['opened_str'] = "כן";
                if($phone_lead['billed'] == '1'){
                    $total_phone_leads_billed++;
                    if(isset($phones_found_arr[$phone_lead['month_check']][$phone_lead['phone']])){
                        $doubled_phones_found_arr[$phone_lead['phone']] = $phone_lead;
                    }
                    else{
                        $phones_found_arr[$phone_lead['month_check']][$phone_lead['phone']] = $phone_lead;
                    }				
                }
                else{
                    if($phone_lead['duplicate_id'] != '' && $phone_lead['duplicate_id'] != '0'){
                        
                        $is_doubled = true;
                        $total_phone_leads_doubled++;
                    }
                    elseif($phone_lead['open_state'] == "1" && $phone_lead['request_id'] == "0"){
                        $total_phone_leads_billed++;
                    }
                }					
            }
            else{
                $total_phone_leads_paybypswd_closed++;
                $phone_lead['phone'] = "*****";
            }
    
            if($phone_lead['status'] == '6'){
                $total_phone_leads_refunded++;
                $phone_lead['refunded_str'] = "כן";
            }
            
            if($is_doubled){
                $phone_leads_doubled_arr[] = $phone_lead;
            }
            else{
                $phone_leads_paybypswd_arr[] = $phone_lead;
            }
        }
        if($advanced_report){
            $customer_types_str = array("new"=>"חדש","new_back"=>"חדש חוזר","back"=>"קיים חוזר","shimur"=>"שימור");
            $customer_type_phones = array();
            $customer_types_count = array("new"=>0,"new_back"=>0,"back"=>0,"shimur"=>0);
            foreach($form_leads_paybypswd_arr as $key=>$lead){
                $lead['previous_sends'] = array();
                $phone_check = stripslashes(trim($lead['phone']));
                if($phone_check == ""){
                    continue;
                }
                $sql_prev = "SELECT  * FROM user_leads WHERE user_id = $user_id  AND phone = '$phone_check' AND id != ".$lead['id']."";
                
                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
		
                foreach ($res_prev as $prev_lead){
                    $lead['previous_sends'][] = $prev_lead;
                }	
                $sql_firstcall = "SELECT * FROM user_leads WHERE  user_id = $user_id AND phone = '$phone_check' ORDER BY date_in LIMIT 1";
                
                $req = $db->prepare($sql_firstcall);
                $req->execute();
                $res_firstcall = $req->fetchAll();

                $lead['firstcall'] = "";
                foreach ($res_firstcall as $firstcall_lead){
                    $lead['firstcall'] .= $firstcall_lead['date_in'];
                }		
                $lead['previous_imports'] = array();
                $sql_prev = "SELECT  * FROM private_contacts_imports WHERE user_id = $user_id  AND phone = '$phone_check'";
                
                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
			
                foreach ($res_prev as $prev_lead){
                    $prev_lead['shimur'] = "shimur";
                    $prev_lead_date = $prev_lead['update_time'];
                    $lead['previous_imports'][] = $prev_lead;
                    
                }
                if(!isset($customer_type_phones[$lead['phone']])){
                    $customer_type_phones[$lead['phone']] = "new";
                    if(!empty($lead['previous_sends'])){
                        $customer_type_phones[$lead['phone']] = "new_back";
                    }
                    if(!empty($lead['previous_imports'])){
                        $customer_type_phones[$lead['phone']] = $lead['previous_imports'][0]['shimur'];
                    }
                    $customer_types_count[$customer_type_phones[$lead['phone']]]++;
                }
                $form_leads_paybypswd_arr[$key] = $lead;
            }
            
            foreach($phone_leads_paybypswd_arr as $key=>$lead){
                $phone_check = stripslashes(trim($lead['phone']));
                if($phone_check == ""){
                    continue;
                }
                $lead['previous_sends'] = array();
                $sql_prev = "SELECT  * FROM user_leads WHERE user_id = $user_id  AND phone = '$phone_check' AND id != ".$lead['id']."";
                
                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();
		
                foreach ($res_prev as $prev_lead){
                    $lead['previous_sends'][] = $prev_lead;
                }	

                $sql_firstcall = "SELECT * FROM user_leads WHERE  user_id = $user_id AND phone = '$phone_check' ORDER BY date_in LIMIT 1";
                
                $req = $db->prepare($sql_firstcall);
                $req->execute();
                $res_firstcall = $req->fetchAll();
                
                $lead['firstcall'] = "";
                foreach ($res_firstcall as $firstcall_lead){
                    $lead['firstcall'] .= $firstcall_lead['date_in'];
                }	

                $lead['previous_imports'] = array();
                $sql_prev = "SELECT  * FROM private_contacts_imports WHERE user_id = $user_id  AND phone = '$phone_check'";
                
                $req = $db->prepare($sql_prev);
                $req->execute();
                $res_prev = $req->fetchAll();

                foreach ($res_prev as $prev_lead){
                    
                    $prev_lead['shimur'] = "shimur";
                    $prev_lead_date = $prev_lead['update_time'];
                    $sql_shimur = "SELECT * FROM user_leads WHERE  user_id = $user_id AND phone = '$phone_check' AND date_in BETWEEN '$prev_lead_date' AND DATE_ADD('$prev_lead_date', INTERVAL 4 MONTH) LIMIT 1";
                    
                    $req = $db->prepare($sql_shimur);
                    $req->execute();
                    $res_shimur = $req->fetchAll();
		
                    foreach ($res_shimur as $shimur_lead){
                        $prev_lead['shimur'] = "back";
                    }
                    
                    $lead['previous_imports'][] = $prev_lead;
                }
                if(!isset($customer_type_phones[$lead['phone']])){
                    $customer_type_phones[$lead['phone']] = "new";
                    if(!empty($lead['previous_sends'])){
                        $customer_type_phones[$lead['phone']] = "new_back";
                    }
                    if(!empty($lead['previous_imports'])){
                        $customer_type_phones[$lead['phone']] = $lead['previous_imports'][0]['shimur'];
                    }
                    $customer_types_count[$customer_type_phones[$lead['phone']]]++;
                }
                $phone_leads_paybypswd_arr[$key] = $lead;
            }	
        }
        $total_phone_leads_to_pay = $total_phone_leads_billed - $total_phone_leads_refunded;
    
        
        $sum_total_leads = $total_form_leads + $total_phone_leads;
        $sum_total_leads_paybypswd_closed = $total_form_leads_paybypswd_closed + $total_phone_leads_paybypswd_closed;
        $sum_total_leads_paybypswd = $total_form_leads_paybypswd + $total_phone_leads_paybypswd;
        $sum_total_leads_billed = $total_form_leads_billed + $total_phone_leads_billed;
        $sum_total_leads_refunded = $total_form_leads_refunded + $total_phone_leads_refunded;
        $sum_total_leads_doubled = $total_form_leads_doubled + $total_phone_leads_doubled;
        $sum_total_leads_to_pay = $total_form_leads_to_pay + $total_phone_leads_to_pay;
    
        
        $row_leads_arr[] = array('רשימת טופסי צור קשר:');
    
        $leads_bytype_arr = array();
        foreach($form_leads_paybypswd_arr as $lead){
            $lead_row = array(stripslashes($lead['phone']));
            if(!isset($leads_bytype_arr[$customer_type_phones[$lead['phone']]])){
                $leads_bytype_arr[$customer_type_phones[$lead['phone']]] = array();
            }
            $leads_bytype_arr[$customer_type_phones[$lead['phone']]][] = $lead_row;
            //$row_leads_arr[] = $lead_row;
        }
        foreach($leads_bytype_arr as $key=>$lead_arr){
            $row_leads_arr[] = array("---");
            $row_leads_arr[] = array("---");
            $row_leads_arr[] = array("---");
            $row_leads_arr[] = array($customer_types_str[$key]);
            foreach($lead_arr as $lead_row){
                $row_leads_arr[] = $lead_row;
            }
        }
    
        $leads_bytype_arr = array();
        $row_leads_arr[] = array("----");
        $row_leads_arr[] = array("---");
        $row_leads_arr[] = array("---");
        $row_leads_arr[] = array("---");	
        $row_leads_arr[] = array('רשימת טלפונים שהתקבלו:');
        foreach($phone_leads_paybypswd_arr as $lead){				
            //$row_leads_arr[] = array( stripslashes($lead['date_in']) , stripslashes($lead['full_name']) , stripslashes($lead['email']) , stripslashes($lead['phone']), $lead['opened_str'] , stripslashes($lead['content']) , 'טופס באתר',$lead['refunded_str'] );
            $sql3 = "SELECT sms_send,call_from,answer,call_date,billsec  FROM user_phone_calls WHERE id = ".$lead['phone_id']."";
            
            $req = $db->prepare($sql3);
            $req->execute();
            $call_data = $req->fetch();

            $answ = ( $call_data['billsec'] == '0' ) ? "ללא מענה" : "שיחה של ".$call_data['billsec']." שניות";
            $lead_row = array(stripslashes($call_data['call_from']));		
            
            //$lead_row[] = $customer_type_phones[$lead['phone']];
            if(!isset($leads_bytype_arr[$customer_type_phones[$lead['phone']]])){
                $leads_bytype_arr[$customer_type_phones[$lead['phone']]] = array();
            }
            $leads_bytype_arr[$customer_type_phones[$lead['phone']]][] = $lead_row;	
            //$row_leads_arr[] = $lead_row; 		
        }
        foreach($leads_bytype_arr as $key=>$lead_arr){
            $row_leads_arr[] = array("---");
            $row_leads_arr[] = array("---");
            $row_leads_arr[] = array("---");
            $row_leads_arr[] = array($customer_types_str[$key]);
            foreach($lead_arr as $lead_row){
                $row_leads_arr[] = $lead_row;
            }
        }	
        $row_leads_arr[] = array("----");

        if(!isset($_GET['withouthtml'])){
            $current_url = current_url();
            $not_phones_only_url = str_replace("&phones_only=1","",$current_url);
            echo "<div><a href='".$not_phones_only_url."'>חזור לדוח המקורי</a></div>";
            echo "<table border=1 style='direction:rtl; text-align:right; border-collapse: collapse;'>";
            $max_cols = 0;
            foreach($row_leads_arr as $row){
                $cols_count = count($row);
                if($cols_count > $max_cols){
                    $max_cols = $cols_count;
                }
            }
            foreach($row_leads_arr as $row){
                echo "<tr>";
                    $col_count = 0;
                    foreach($row as $col){
                        echo "<td>".$col."</td>";
                        $col_count ++;
                    }
                    $col_left = $max_cols - $col_count;
                    if($col_left > 0){
                        echo "<td colspan='".$col_left."'></td>";
                    }
                echo "</tr>";
            }
            echo "</table>";
        }
        else{
            $this->set_layout('blank');
            return Helper::array_to_csv_download($row_leads_arr);
        }  
    }

  }
?>