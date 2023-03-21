<?php
  class Refund_requestsController extends CrudController{
    public $add_models = array("refund_reasons","users", "refund_requests");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    function list()
    {
        
        $return_page_id = '1';
        if(isset($_GET['page_id'])){
            $return_page_id = $_GET['page_id'];
        }
        $return_filter_qstr = "";
        $filter_params = array('s_date','e_date','user_name');
        foreach($filter_params as $filter_param){
            if(isset($_GET[$filter_param]) && $_GET[$filter_param] != ''){
               
                $filter_val = $_GET[$filter_param];
                $return_filter_qstr .= "&$filter_param=$filter_val";
            }

        }
        $return_url = inner_url("refund_requests/list/")."?page_id=".$return_page_id.$return_filter_qstr;
        //pass for phone system server: juzvklcrj
        if(isset($_GET['refund']) && isset($_GET['reuquest_user_id'])){
            if($_GET['refund'] == "0" || $_GET['refund'] == "" || (!isset($_GET['refund_type']))){
                echo "בקשה לא תקינה";
                exit();
            }
            
            $denied = false;
            $cancel_refund = false;
            if(isset($_REQUEST['denied']) && $_REQUEST['denied'] == '1'){
                $lead_status = Refund_requests::get_lead_status($_REQUEST['lead_id']);
                
                if($lead_status == '6'){
                    SystemMessages::add_err_message("שגיאה: הליד הזה כבר זוכה");
                    return $this->redirect_to($return_url);  
                }
                $denied = true;
                
                Refund_requests::deny_refund($_REQUEST['refund'],$_REQUEST['admin_comment']);		
            }
            elseif(isset($_REQUEST['cancel_refund']) && $_REQUEST['cancel_refund'] == '1'){
                Refund_requests::deny_refund($_REQUEST['refund'] ,$_REQUEST['admin_comment']);		
                $effected_rows =  0;
                $effected_rows =  Refund_requests::set_lead_status($_REQUEST['lead_id'],'0');
                if($effected_rows > 0){
                    Refund_requests::remove_credit_to_user($_REQUEST['reuquest_user_id']);
                }
            }
            else{
                $effected_rows =  0;  
                $effected_rows =  Refund_requests::set_lead_status($_REQUEST['lead_id'],'6');
            }

            $user_data = Users::get_by_id($_REQUEST['reuquest_user_id']);

            
            $lead_data = Refund_requests::get_lead_data($_REQUEST['lead_id']);
            	

            if( $lead_data){
                if($denied){
                    SystemMessages::add_success_message("התגובה נשלחה ללקוח:"."<br/>".$lead_data['full_name']."<br>".$lead_data['phone']);
                    
                }
                elseif($cancel_refund){
                    SystemMessages::add_success_message("הזיכוי בוטל והתגובה נשלחה ללקוח:"."<br/>".$lead_data['full_name']."<br>".$lead_data['phone']);
                    
                }			
                else{
                    SystemMessages::add_success_message("ליד זוכה:"."<br/>".$lead_data['full_name']."<br>".$lead_data['phone']);		
                }

                $email_info = array(
                    'user'=>$user_data,
                    'lead'=>$lead_data,
                    'denied'=>$denied,
                    'cancel_refund'=>$cancel_refund,
                    'admin_comment'=>$_REQUEST['admin_comment']
                );
                if(isset($_REQUEST['admin_comment'])){
                    $email_info['admin_comment'] = $_REQUEST['admin_comment'];
                }
                ob_start();
				    $this->include_view('emails_send/refund_reply_email.php',$email_info);

                $email_content = ob_get_clean();
                $email_to = trim($user_data['email']);
                $email_title = "זיכוי ליד";

			    $this->send_email($email_to, $email_title, $email_content);
            }
            else{
                exit("no lead data!!!");
            }
            return $this->redirect_to($return_url);
        }	
        
        $refund_reasons = Refund_requests::get_refund_reasons_id_indexed();

        $status_list = array();
        $status_list[0] = "מתעניין חדש";
        $status_list[1] = "נוצר קשר";
        $status_list[5] = "מחכה לטלפון";
        $status_list[2] = "סגירה עם לקוח";
        $status_list[3] = "לקוחות רשומים";
        $status_list[4] = "לא רלוונטי";
        $status_list[6] = "הליד זוכה";


        $resource_list = array();
        $resource_list['form'] = "טופס";
        $resource_list['phone'] = "טלפון";
        
        
        
        
        
        $user_name_filter = false;
        $user_name_filter_str = "";
        if(isset($_GET['user_name']) && !empty($_GET['user_name'])){
            $user_name_filter = $_GET['user_name'];
            $user_name_filter_str = $_GET['user_name'];
        }

        $s_date_filter = false;
        $s_date_filter_str = "";
        if(isset($_GET['s_date']) && !empty($_GET['s_date'])){
            $s_date_filter_str = $_GET['s_date'];
            $s_dateT = explode( "-", $_GET['s_date'] );
            $s_date_filter = $s_dateT[2]."-".$s_dateT[1]."-".$s_dateT[0];
        }
        $e_date_filter = false;
        $e_date_filter_str = "";
        if(isset($_GET['e_date']) && !empty($_GET['e_date'])){
            $e_date_filter_str = $_GET['e_date'];
            $e_dateT = explode( "-", $_GET['e_date'] );
            $e_date_filter = $e_dateT[2]."-".$e_dateT[1]."-".$e_dateT[0];
        }

        

        $limit_rows = 3;
        $page_id =  ( !empty($_GET['page_id']) ) ? $_GET['page_id'] : '1';
        
        $request_list = Refund_requests::get_filtered_requests_count($user_name_filter,$s_date_filter,$e_date_filter,$page_id,$limit_rows);

        
        $num_rows = $request_list['rows_count'];

        $refund_requests = $request_list['list'];

        $refund_ids = array();
        $refund_list = array();
        foreach($refund_requests as $refund_request){
            if(!isset($refund_list[$refund_request['lead_id']])){
                $refund_ids[] = $refund_request['lead_id'];
                $refund_list[$refund_request['lead_id']] = $refund_request;
            }
            else{
                if(!isset($refund_list[$refund_request['lead_id']]['history'])){
                    $refund_list[$refund_request['lead_id']]['history'] = array();
                }
                $refund_list[$refund_request['lead_id']]['history'][$refund_request['refund_id']] = $refund_request;
            }
        }
        echo "<h3>בקשות לזיכויים</h3>";
        echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"maintext\">";

            echo "<tr><td colspan=20 height=15></td><td>";
            echo "<tr><td colspan=20 height=15></td><td>";
            $page_id = ( empty($_GET['page_id']) ) ? "0" : $_GET['page_id'];

            echo "<tr>";
                echo "<td colspan=20>";
                    echo "<form action='".inner_url("refund_requests/list/")."' name='searchForm' method='get' style='padding:0; margin:0;'>";
                    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"maintext\">";
                        echo "<tr>";
                            echo "<td>מתאריך</td>";
                            echo "<td width=10></td>";
                            echo "<td><input type='text' name='s_date' value='".$s_date_filter_str."' class='input_style' style='width: 100px;'> dd-mm-yyyy</td>";
                            echo "<td width=30></td>";
                            echo "<td>עד לתאריך</td>";
                            echo "<td width=10></td>";
                            echo "<td><input type='text' name='e_date' value='".$e_date_filter_str."' class='input_style' style='width: 100px;'> (תאריך רצוי + יום) dd-mm-yyyy</td>";
                            
                            echo "<td width=30></td>";
                            echo "<td>שם משתמש: </td>";
                            echo "<td><input type='text' name='user_name' value='".$user_name_filter_str."' class='input_style' style='width: 100px;'></td>";
                            
                            echo "<td width=10></td>";
                            echo "<td><input type='submit' value='חפש' class='submit_style'></td>";
                        echo "</tr>";
                    echo "</table>";
                    echo "</form>";
                echo "</td>";
            echo "</tr>";
            echo "<tr><td colspan=20 height=15></td><td>";

            echo "<tr><td colspan=20 height=15></td><td>";
            echo "<tr><td colspan=20 height=15></td><td>";		
            echo "<tr>";
                echo "<td><b>תאריך הבקשה</b></td>";
                echo "<td width=10></td>";
                echo "<td><b>שם משתמש</b></td>";
                echo "<td width=10></td>";
                echo "<td><b>סטטוס</b></td>";
                echo "<td width=10></td>";			
                echo "<td><b>סיבת הבקשה</b></td>";
                echo "<td width=10></td>";
                echo "<td><b>תיוג</b></td>";
                echo "<td width=10></td>";			
                echo "<td><b>הערות</b></td>";
                echo "<td width=10></td>";
                echo "<td><b>קטגוריה</b></td>";
                echo "<td width=10></td>";
                echo "<td><b>פרטי הליד</b></td>";
                echo "<td width=10></td>";
                echo "<td><b>צפייה בליד</b></td>";
                echo "<td width=10></td>";			
                echo "<td><b>זיכוי</b></td>";
            echo "</tr>";
                echo "<tr><td colspan=20 height=2></td></tr>";
                echo "<tr><td colspan=20><hr width=100% size=1 color=#000000></td></tr>";		
                    
            foreach($refund_ids as $refund_id)
            {
                $data = $refund_list[$refund_id];
                $tr_style="";
                if($data['status'] == '6'){
                    $tr_style = "background:#ffcece;";
                }
                elseif($data['denied'] == '1'){
                    $tr_style = "background:#d3ffd3";
                }
                elseif(isset($data['history'])){
                    if(!empty($data['history'])){
                        $tr_style = "background:#eeeeff";
                    }
                }
                $doubled_str = "";
                if($data['billed'] != '1'){
                    $tr_style = "background:#7d7d7d";
                    $doubled_str = "<b style = 'color:red'>ליד כפול. יש לברר כיצד בוצעה בקשת זיכוי</b><br/>";
                }
                echo "<tr style='$tr_style' >";
                    echo "<td>".$data['request_time']."</td>";
                    echo "<td width=15></td>";
                    echo "<td><a target='_new' href='".inner_url("user_lead_settings/list/?user_id=".$data['user_id'])."' class='maintext'>".$data['user_name']."</a></td>";
                    echo "<td width=15></td>";
                    echo "<td>".$status_list[$data['status']]."</td>";			
                    echo "<td width=15></td>";

                    echo "<td><span style='color:red; font-weight:bold;'>".$refund_reasons[$data['reason']]."</span></td>";
                    
                    echo "<td width=15></td>";
                    $tag_str = "--";
                    if($data['tag'] != "0"){
                        $tag_str = Refund_requests::get_tag_name($data['tag']);
                    }
                    echo "<td><span style='color:red; font-weight:bold;'>".$tag_str."</span></td>";
                    
                    echo "<td width=15></td>";				
                    echo "<td>".$data['comment']."</td>";
                    echo "<td width=15></td>";
                    echo "<td>";
                        if($data['resource'] == "phone"){
                            $phone_data =  Refund_requests::get_phone_call_data($data['phone_id']);
                            echo "<span style='color:orange;font-weight:bold;'>סטטוס תשובה:</span> ".$phone_data['answer']."<br/>"
                                ."<span style='color:orange;font-weight:bold;'>הודעת טקסט:</span> ".$phone_data['sms_send']."<br/>"
                                ."<span style='color:orange;font-weight:bold;'>חיוב בשניות:</span> ".$phone_data['billsec']."<br/>"
                                ."<span style='color:orange;font-weight:bold;'>מזהה:</span> ".$phone_data['uniqueid']."<br/>";						
                        }
                        else{
                            echo $data['cat_str']."<br/>";
                        }
                    echo "</td>";
                    echo "<td width=15></td>";
                    echo "<td>$doubled_str";
                        echo "<span style='color:orange;font-weight:bold;'>נשלח ב:</span> ".$data['send_time']."<br/>"
                            ."<span style='color:orange;font-weight:bold;'>שם:</span> ".$data['sender_name']."<br/>"
                            ."<span style='color:orange;font-weight:bold;'>טלפון:</span> ".$data['sender_phone']."<br/>"
                            ."<span style='color:orange;font-weight:bold;'>אימייל:</span> ".$data['sender_email']."<br/>"
                            ."<span style='color:orange;font-weight:bold;'>הערות:</span> ".$data['brq_note']."<br/>";
                    echo "</td>";
                    echo "<td width=10></td>";					
                    echo "<td>";
                    if($data['resource'] == "phone"){
                        echo "<b style='color:red;'>ליד טלפוני</b>";
                    }
                    else{
                        echo "<a target='_new' href='".inner_url("leads_handler/send_lead_users_list/")."?biz_request_id=".$data['lead_id']."&status=".$data['status']."' class='maintext'>צפה בליד</a>";
                    }
                    echo "</td>";
                    echo "<td width=20></td>";					
                    echo "<td>";
                        if(isset($data['history'])){
                            ksort($data['history']);
                            echo "<b style='color:red;'>הסטורית בקשות:</b><br/>";
                            foreach($data['history'] as $history_data){
                                echo "<br/>".$history_data['refund_id']."<br/><span style='color:brown;'><b>לקוח:</b>".$history_data['comment']."</span>";
                                echo "<br/><span style='color:orange;'><b>מנהל:</b>";
                                if($history_data['admin_comment'] == ""){
                                    echo "-----אין תגובה----";
                                }
                                else{
                                    echo $history_data['admin_comment'];
                                }
                                
                                if($history_data['denied'] == '1'){
                                    echo "(הוחלט לא לזכות)";
                                }
                                echo "</span>";

                            }
                                echo "<br/><br/>החלטה נוכחית: <br/>".$data['refund_id']."<br/>";
                        }
                        if($data['denied'] == '1'){
                            echo "<b><span style='color:red;'>הוחלט לא לזכות(".$data['admin_comment'].")</span></b>";
                        }
                        else{
                            if($data['status'] == '6'){
                                echo "<h4 style='color:red;'>הליד זוכה</h4>";
                            }
                            if($data['resource'] == "phone"){
                                if($data['status'] != '6'){   //reuquest_user_id,refund, refund_type, cancel_refund, admin_comment, denied, 
                                    echo "<a href='".$return_url."&reuquest_user_id=".$data['user_id']."&refund_type=phone&refund=".$data['refund_id']."&lead_id=".$data['lead_id']."' class='maintext'>לחץ כאן לזיכוי</a><br/><br/>";
                                }
                                if($phone_data['recordingfile'] != ""){
                                    if($phone_data['link_sys_id'] == "0"){
                                        $link_records_alt_url = get_config("link_records_alt_url");
                                        echo "<a target='_blank' href='".$link_records_alt_url."?menu=monitoring&action=display_record&id=".$phone_data['uniqueid']."&rawmode=yes' class='maintext'>לחץ כאן להורדת הקלטה</a><br/>";
                                    }
                                    else{
                                        echo "<a target='_blank' href='".inner_url("Link_recordings/download/")."?filename=".$phone_data['recordingfile']."' class='maintext'>לחץ כאן להורדת הקלטה</a><br/>";
                                    }
                                }
                            }
                            else{
                                if($data['status'] != '6'){
                                    echo "<a href='".$return_url."&reuquest_user_id=".$data['user_id']."&refund_type=form&refund=".$data['refund_id']."&lead_id=".$data['lead_id']."' class='maintext'>לחץ כאן לזיכוי</a>";
                                }
                            }

                            if($data['status'] != '6'){
                                echo "<form method='POST' action='".$return_url."&reuquest_user_id=".$data['user_id']."&refund=".$data['refund_id']."&refund_type=".$data['resource']."&lead_id=".$data['lead_id']."&denied=1' class='maintext'>
                                    <b>החלט לא לזכות(הוסף הערה)</b>
                                    <textarea name='admin_comment'></textarea>
                                    <button type='sumbit'>שלח</button>
                                </form>";	
                            }
                            else{
                                echo "<form method='POST' action='".$return_url."&reuquest_user_id=".$data['user_id']."&refund=".$data['refund_id']."&refund_type=".$data['resource']."&lead_id=".$data['lead_id']."&cancel_refund=1' class='maintext'>
                                    <b>בטל זיכוי(הוסף הערה)</b>
                                    <textarea name='admin_comment'></textarea>
                                    <button type='sumbit'>שלח</button>
                                </form>";							
                            }
                        }

                    echo "</td>";
                echo "</tr>";
                echo "<tr><td colspan=20 height=2></td></tr>";
                echo "<tr><td colspan=20><hr width=100% size=1 color=#000000></td></tr>";			
            }
            
            echo "<tr>";
                    echo "<td colspan=20 align=center style=\"border-top: 1px solid gray;\">";
                        echo "<table align=center border=0 cellspacing=\"0\" width=100% cellpadding=\"3\" class=\"maintext\">";
                            echo "<tr>";
                                echo "<td align=center>סך הכל ".$num_rows." בקשות</td>";
                            echo "</tr>";
                            
                            if( $num_rows > $limit_rows )//$limit_rows $num_rows
                            {
                                echo "<tr>";
                                    echo "<td align=center>";
                                    
                                        $z = 0;
                                        
                                        for($i=1 ; ($i*$limit_rows) < $num_rows ; $i++)
                                        {
                                            
                                            $pz = $z+1;
                                            

                                                    if( $i == $page_id )
                                                        $classi = "<strong style=\"color:#000000\">".$pz."</strong>&nbsp;&nbsp;";
                                                    else
                                                        $classi = "<a href='".inner_url("refund_requests/list/")."?page_id=".$i.$return_filter_qstr."' class='maintext'>".$pz."</a>&nbsp;&nbsp;";
                                                        
                                                    echo $classi;
                                                    
                                                    $z = $z + 1;
                                                    
                                                    if( $z%35 == 0 )
                                                        echo "<br>";
                                            
                                            
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        echo "</table>";
                    echo "</td>";
                echo "</tr>";		
        echo "</table>";
    }


  }
?>