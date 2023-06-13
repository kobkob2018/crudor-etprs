<?php
  class leads_user_getController extends CrudController{
    public $add_models = array("biz_categories","net_directories");

    function report()
    {
        $db = Db::getInstance();
        $clientName = (isset($_GET['clientName']) &&  $_GET['clientName'] != "" ) ? "u.full_name LIKE '%".$_GET['clientName']."%' AND " : "";
        
        $defualt_s_date = ( isset($_GET['s_date']) && $_GET['s_date'] != "" ) ? $_GET['s_date'] : date('01-m-Y');
        $defualt_e_date = ( isset($_GET['e_date']) && $_GET['e_date'] != "" ) ? $_GET['e_date'] : date('d-m-Y');
        
        $ex_s = explode("-",$defualt_s_date);
        $s_date = ( $defualt_s_date != "" ) ? "AND sent.date_in >= '".$ex_s[2]."-".$ex_s[1]."-".$ex_s[0]."' " : "";
        $ex_e = explode("-",$defualt_e_date);
        $e_date = ( $defualt_e_date != "" ) ? "AND sent.date_in <= '".$ex_e[2]."-".$ex_e[1]."-".$ex_e[0]."' " : "";
        
        $sql = "SELECT ulv.* ,uls.*, u.full_name AS clientName
            FROM user_lead_visability AS ulv ,user_lead_settings AS uls , user_leads AS sent , users AS u WHERE 
                ulv.user_id=u.id AND
                ulv.show_in_leads_report = '1' AND 
                ".$clientName."
                ulv.user_id=sent.user_id
                ".$s_date.$e_date ."
                GROUP BY ulv.user_id
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
        
        echo "<table class=\"maintext\" cellpadding=0 cellspacing=0>";
		echo "<tr><td colspan=6 height=10></td></tr>";
		echo "<tr>";
			echo "<td colspan=6>";
            echo "<form action='".inner_url("leads_user_get/report/")."' name='serachForm' method='get' style='padding:0;margin:0'>";

				echo "<table class=\"maintext\" cellpadding=0 cellspacing=0>";
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
							echo "<a href='".inner_url('leads_user_get/user_csv/')."?user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo $total_form_leads_to_pay+$total_phone_leads_to_pay;
							echo "</a>";
							
							
							echo " | <a href='".inner_url('leads_user_get/user_view_here/')."?user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo "צפה בדוח כאן";
							echo "</a>";
							echo " | <a href='".inner_url('leads_user_get/user_adv_view_here/')."?user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
								echo "צפה בדוח מתקדם";
							echo "</a>";
							echo " | <a href='".inner_url('leads_user_get/user_adv_csv/')."?user_id=".$data['user_id']."&s_date=".$defualt_s_date."&e_date=".$defualt_e_date."'>";
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

  }
?>