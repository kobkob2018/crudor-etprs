<h3>
            טלפונים שלא הפכו ללידים
        </h3>
        
        <div style="padding:20px;">
            <form action="<?= current_url() ?>" method="GET"><?= $info['filter_str']['date_from']; ?>
                מתאריך <input type="text" name="date_from" value="<?= $info['filter_str']['date_from']; ?>" />&nbsp&nbsp&nbsp
                עד תאריך  <input type="text" name="date_to" value="<?= $info['filter_str']['date_to']; ?>" />&nbsp&nbsp&nbsp
                שם לקוח  <input type="text" name="user_name" value="<?= $info['filter_str']['user_name']; ?>" />&nbsp&nbsp&nbsp
                <br/><br/>
                <input type="submit" value="הצג" />
            </form>
        </div>
        <div>
            סימוני משתמשים(הערה אחרונה על-ידי): 
            <?php 
                foreach($info['owners_names'] as $owner_id=>$owner_name){
                    if(isset($info['owners_colors'][$owner_id])){
                        ?>
                            <span style="background:<?php echo $info['owners_colors'][$owner_id]; ?>"><?php echo $owner_name; ?></span>
                        <?php
                    }
                }
            ?>
        </div>
        <div>
            <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>שיחות שנענו:</b><?php echo $info['answerd_count']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span style="background:yellow;">כפילויות לאותו מספר: <?php echo $info['doubled_answerd_count']; ?></span>
            
            &nbsp;&nbsp;&nbsp;
            <input type="checkbox" onchange="showhideanweredorno(this,'lead_tr_answered');" checked /> הצג
            <br/>
            <b>שיחות שלא נענו:</b><?php echo $info['noanswer_count']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<span style="background:yellow;">כפילויות לאותו מספר: <?php echo $info['doubled_noanswer_count']; ?></span>
            &nbsp;&nbsp;&nbsp;
            <input type="checkbox" onchange="showhideanweredorno(this,'lead_tr_noanswer');" checked /> הצג
            <br/>
        </div>
        <script type="text/javascript">
            function showhideanweredorno(el,class_id){
                if(el.checked){
                    document.querySelector("."+class_id).classList.remove("hidden");
                }
                else{
                    document.querySelector("."+class_id).classList.add("hidden");
                }                
            }
        </script>
        <table border="1" cellpadding="10" style="border-collapse: collapse;">
            <tr>
                <th>תאריך</th>
                <th>טלפון</th>
                <th>מצב שיחה</th>
                <th>לינק להקלטה</th>
                <th>קמפיין</th>
                <th>שם קמפיין</th>
                <th>הערה</th>
            </tr>
            <?php foreach($info['users_arr'] as $user_id=>$user): ?>
                
                <tr>
                    <th colspan="7">
                        <a target='_blank' href='<?= inner_url('/users/edit/?&row_id=').$user_id ?>'><?= $user['full_name'] ?></a>
                    </th>
                </tr>
    
                <?php if(isset($user['leads']) && !empty($user['leads'])): ?>
                    <?php foreach($user['leads'] as $lead): ?>
                        <?php 
    
                            $mark = $lead['mark_color'];
                            if($mark == ""){
                                if($lead['last_comment_by_user']!="" && $lead['last_comment_by_user']!="0" ){
                                    if(isset($info['owners_colors'][$lead['last_comment_by_user']])){
                                        $mark = "style='background:".$info['owners_colors'][$lead['last_comment_by_user']].";'";
                                        
                                    }
                                }
                            }
                            else{
                                $mark = "style='background:".$mark.";'";
                            }
                            $answer_class = "noanswer";
                            if($lead['phone_data']['answer'] == "ANSWERED"){
                                $answer_class = "answered";
                            }
                        ?>
                        <tr <?php echo $mark; ?> id="misscall_comment_tr_<?php echo $lead['id']; ?>" class="lead_tr_<?php echo $answer_class; ?>">
                            <td>
                            `	<?php echo $lead['date_in']; ?>
                                <br/>
                                זמן שיחה בשניות: 
                                <?php echo $lead['phone_data']['billsec']; ?>
                            </td>
                            <td><?php echo $lead['phone']; ?></td>
                            <td><?php echo $lead['phone_data']['answer']; ?></td>
                            <td>
                                <?php
                                    if($lead['phone_data']['recordingfile']!=""){
                                        echo "<a target='_blank' href='http://ilbiz.co.il/site-admin/recording_handlers/download.php?filename=".$lead['phone_data']['recordingfile']."' class='maintext'>לחץ כאן להורדת הקלטה</a><br/>"; 
                                    }
                                ?>
                            </td> 
                            <td style="background:<?php echo $info['campaign_colors'][$lead['campaign_type']]; ?>"><?php echo $info['campaign_names'][$lead['campaign_type']]; ?></td>
                            <td style="background:<?php echo $info['campaign_colors'][$lead['campaign_type']]; ?>"><?php echo $lead['campaign_name']; ?></td>
                            <td>
                                <form action="" method="POST" id="misscall_comment_form_<?php echo $lead['id']; ?>" >
                                    <input type="hidden" name="edit_misscall_comment" value="<?php echo $lead['id']; ?>" />
                                    <input type="hidden" name="lead_user_id" value="<?php echo $lead['user_id']; ?>" />
                                    <textarea name="comment" style="width:250px; height:35px;"><?php echo $lead['comment']; ?></textarea>
                                    
    
                                    <br/>
                                    הפך לליד עם טלפון: </br>
                                    <input type='text' name='lead_by_phone' value='<?php echo $lead['lead_by_phone']; ?>'/>
                                    <?php 
                                        $color_options = array("#ffd55b"=>"","#f59cff"=>"","#ffff63"=>"","#92e7f1"=>"","#00e8b4"=>"","#ff4949"=>"");
                                        $defult_color_selected = "checked";
                                        if(isset($color_options[$lead['mark_color']])){
                                            $color_options[$lead['mark_color']] = "checked";
                                            $defult_color_selected = "";
                                        }
                                    ?>
                                    <br/>
                                    סימון בצבע: <br/>
                                    <div style="display:inline-block; padding:2px;">
                                        
                                    </div>
                                    <?php foreach($color_options as $color=>$selected): ?>
                                        <div style="display:inline-block;background:<?php echo $color; ?>; padding:2px;">
                                            <input type="radio" name="mark_color" value="<?php echo $color; ?>" <?php echo $selected; ?> />
                                        </div>
                                            
                                    <?php endforeach; ?>
                                    <br/>
                                    <input type="radio" name="mark_color" value="" />לפי המשתמש
                                    <input type="radio" name="mark_color" value="none"  <?php echo $defult_color_selected; ?> />ללא צבע
                                    
                                    <br/><br/>
                                    <button type="button" style="width:250px;"  onclick="quickedit_misscall_comment(<?php echo $lead['id']; ?>);">שמור</button>
                                    <br/>
                                    הערה אחרונה: 
                                    <span class='last_comment_by_name'>
                                        <?php if($lead['last_comment_by_user']!="" && $lead['last_comment_by_user']!="0" ): ?>
                                            <?php echo $info['owners_names'][$lead['last_comment_by_user']]; ?>
                                            <br/>
                                        <?php endif; ?>	
                                    </span>									
                                </form>
                            </td>
                        </tr>
                        <?php if(!empty($lead['appears_arr'])): ?>
                            <?php foreach($lead['appears_arr'] as $appear): ?>
                                <tr style="background:yellow;" id="misscall_comment_tr_<?php echo $appear['id']; ?>"  class="lead_tr_<?php echo $answer_class; ?>">
                                    <td>שיחה נוספת: <br/><?php echo $appear['date_in']; ?> 
                                        <br/>
                                        זמן שיחה בשניות: 
                                        <?php echo $lead['phone_data']['billsec']; ?>	
                                    </td>
                                    <td><?php echo $appear['phone']; ?></td>
                                    <td><?php echo $appear['phone_data']['answer']; ?></td>
                                    <td>
                                        <?php
                                            if($appear['phone_data']['recordingfile']!=""){
                                                echo "<a target='_blank' href='http://ilbiz.co.il/site-admin/recording_handlers/download.php?filename=".$appear['phone_data']['recordingfile']."' class='maintext'>לחץ כאן להורדת הקלטה</a><br/>"; 
                                            }
                                        ?>
                                    </td> 
                                    <td><?php echo $info['campaign_names'][$appear['campaign_type']]; ?></td>
                                    <td><?php echo $appear['campaign_name']; ?></td>
                                    <td></td>
                                </tr>							
                            
                            <?php endforeach; ?> 
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            <?php endforeach; ?>
        </table>
        <div id="quickedit_done_msg" class="hidden" style="position: fixed; top: 42px; background: #ffa500b5; right: 36%; padding: 40px; font-size: 30px; font-family: SANS-SERIF; border-radius: 10px; color:green;">
            <div>
                ההערה נשמרה בהצלחה
            </div>
        </div>
        <div id="quickedit_send_msg" class="hidden" style="position: fixed; top: 42px; background: #ff000038; right: 36%; padding: 40px; font-size: 30px; font-family: SANS-SERIF; border-radius: 10px; color:red;">
            <div>
                שולח
            </div>
        </div>
        <div id="quickedit_err_msg" class="hidden" style="position: fixed; top: 42px; background: #ff000038; right: 36%; padding: 40px; font-size: 30px; font-family: SANS-SERIF; border-radius: 10px; color:red;">
            <div class="msg">
                
            </div>
        </div>	
        <form action="" method="POST" id="quickedit_helper_form">
            <input type="hidden" name="quickedit" value="1" />
            <input type="hidden" name="controller" value="misscalls_leads_reports" />
            <input type="hidden" name="func" value="edit_misscall_comment" />
            <?php foreach($_GET as $get_key=>$get_val): ?>
                <input type="hidden" name="<?php echo $get_key; ?>" value="<?php echo $get_val; ?>" />
            <?php endforeach; ?>
        </form>	
        <script type="text/javascript">
            function quickedit_misscall_comment(lead_id){
                
                    document.querySelector("#quickedit_send_msg").classList.remove('hidden');
                    let thisform = document.querySelector("#misscall_comment_form_"+lead_id);
                    let helperForm = document.querySelector("#quickedit_helper_form");
					
					let thisParams = new FormData(thisform);
					let helperParams = new FormData(helperForm);
					
					
					for (var pair of helperParams.entries()) {
						thisParams.append(pair[0], pair[1]);
					}
					
                    let params = thisParams;
                    const ajax_url = "<?= inner_url('call_monitor/misscalls_comments/') ?>";
                    const xhttp = new XMLHttpRequest();
                    xhttp.responseType = 'json';
                    xhttp.onload = function(res) {
						console.log(res.response);
                        const return_data = xhttp.response;   
                         
                        console.log(return_data);
                        document.querySelector("#quickedit_send_msg").classList.add("hidden");
                        if(return_data['success'] == '1'){	  
                            let comment_data = return_data['data'];
                            thisform.querySelector('textarea[name="comment"]').innerHTML = comment_data['comment'];
                            thisform.querySelector('input[name="lead_by_phone"]').value = comment_data['lead_by_phone'];
                            thisform.querySelector('.last_comment_by_name').innerHTML = comment_data['owner_name'];				
                            document.querySelector("#misscall_comment_tr_"+lead_id).style["background"] = comment_data['mark_color'];
                            document.querySelector("#quickedit_done_msg").classList.remove('hidden');
                            setTimeout(function(){document.querySelector("#quickedit_done_msg").classList.add('hidden')},2000);
                        }
                        else{
                            
                            document.querySelector("#quickedit_err_msg").classList.remove('hidden');
                            document.querySelector("#quickedit_err_msg").querySelector(".msg").innerHTML = return_data['err_msg'];
                            setTimeout(function(){document.querySelector("#quickedit_err_msg").classList.add('hidden')},2000);
                            
                        }
                    }
                    xhttp.open("POST", ajax_url, true);
                    xhttp.send(params);  
            }
        </script>
