<style type="text/css">
            #current_calls_interface{
                display: block;
                position: fixed;
                left: 5px;
                bottom: 6px;
                padding: 5px;
                background: pink;
                border: 2px solid gray;
            }
            
            #current_call_list_table tr td.hide{
                display:none;
            }
            
            #current_call_list_table tr td{
                min-width: 80px;
                height: 22px;
                text-align: right;
                padding: 9px;
                background: #c3f4c0;
            }
            #calls_monitor_content{background: black;}
            
        </style>

        <div id="current_calls_interface">
            <div id="calls_monitor_content" class="hidden">
                <div id="current_calls_ajax_helper" class="visible">
                    <form action="javascript://" name="current_calls_ajax_helper_form">
                        <input type="hidden" id="current_calls_ajax_helper_is_on" value="0" />
                    </form>
                    <div id="current_calls_ajax_helper_list_placeholder" style = "background:red;">
                        
                    </div>
                </div>
                <div id="popup_call">
                
                </div>
                <div id="current_call_list">
                    <table id="current_call_list_table">
                        <tr  class="phone_list_row_header" data-state="phone" data-uniqid="-1" >
                            <td class="current_call_th_unk hide">user_id</td>
                            <td class="current_call_th_from">הגיע ממספר</td>
                            <td class="current_call_th_to">מספר יעד</td>
                            <td class="current_call_th_did hide">מספר חוייג</td>
                            <td class="current_call_th_date hide">זמן שיחה</td>
                            <td class="current_call_th_hour">זמן</td>
                            <td class="current_call_th_link_sys_id hide">מזהה</td>
                        </tr>
                    </table>
                </div>
            </div>
            <a href="javascript://" id="calls_monitor_key" onclick="start_current_calls_info(this);" data-state="0" data-hovered="0" data-init="0">הפעל מוניטור שיחות</a>
            
    
        </div>
            <script type="text/javascript">

                function start_current_calls_info(){
                        const current_calls_switch = document.querySelector("#current_calls_ajax_helper_is_on");
                        const calls_monitor_key = document.querySelector("#calls_monitor_key");
                        let current_calls_ajax_helper_is_on = current_calls_switch.value;
                        if(current_calls_ajax_helper_is_on == "0"){	
                            current_calls_switch.value = "1";
                            if(document.querySelector("#calls_monitor_key").dataset.init == "0"){	
                                document.querySelector("#calls_monitor_key").dataset.init = "1";
                                show_calls_monitor();
                            }
                            document.querySelector("#calls_monitor_key").innerHTML = "כבה מוניטור שיחות";
                            send_current_calls_ajax_request();	
                        
                        }
                        if(current_calls_ajax_helper_is_on == "1"){
          
                            document.querySelectorAll(".phone_list_row").forEach(el=>{
                                el.remove();
                            });
                            current_calls_switch.value = '0';
                            calls_monitor_key.innerHTML = "הפעל מוניטור שיחות";
                            calls_monitor_key.dataset.init = "0";
                            calls_monitor_key.dataset.hovered = "0";
                            calls_monitor_key.dataset.state = "0";
                            document.querySelector("#calls_monitor_content").classList.add("hidden");
                        }	
                   
                        get_current_calls_info();
                }
                
                function get_current_calls_info(){
                    console.log("working");
                    //console.log(jQuery("#current_calls_ajax_helper_is_on").val());
                    const current_calls_switch = document.querySelector("#current_calls_ajax_helper_is_on");
                    if(current_calls_switch.value == "1"){
                        send_current_calls_ajax_request();
                    }	
                   				
                }
                
                function send_current_calls_ajax_request(){
                
                    var filter_data = "<?= inner_url("call_monitor/get_current_phone_calls_ajax/") ?>";
                    
                    const xhttp = new XMLHttpRequest();

                    xhttp.onload = function() {
                        const msg = this.responseText;    
                        console.log(msg);
                        
                        const placeholder = document.querySelector("#current_calls_ajax_helper_list_placeholder");
                        placeholder.innerHTML= msg;

                        //placeholder.append("sssssss");
                        let list_table_new = placeholder.querySelector("#current_phone_calls_return");
                        let new_alerts = 0;
                        list_table_new.querySelectorAll(".phone_list_row").forEach(el=>{
                            if(el.dataset.state == 'done'){
                                placeholder.innerHTML = "";
                            }
                            else{
                                if(el.dataset.state == 'phone'){
                                
                                    let uniqid = el.dataset.uniqid;
                                    let row_id = "phone_list_row_"+uniqid;
                                    const findEl = document.querySelector("#"+row_id);
                                    if(!findEl){
                                        el.id = row_id;										
                                        document.querySelector("#current_call_list_table").append(el);
                                        new_alerts++;
                                    }
                                }
                            }
                        });
                        if(new_alerts>0){
                            show_calls_monitor();
                        }

                        setTimeout(function(){
                            get_current_calls_info();
                        },10000);
                    }
                    xhttp.open("GET", filter_data, true);
                    xhttp.send();                      
                }
                function show_calls_monitor(){
                    const calls_monitor_key = document.querySelector("#calls_monitor_key");
                    const calls_monitor_content = document.querySelector("#calls_monitor_content");
                    if(calls_monitor_key.dataset.state == "0"){
                        calls_monitor_content.classList.remove("hidden");
                        calls_monitor_key.dataset.state ="2";
                    }
                    
                }
                function start_current_calls_ui(){
                    const calls_monitor_key = document.querySelector("#calls_monitor_key"); 
                    const calls_monitor_content = document.querySelector("#calls_monitor_content"); 
                        if(calls_monitor_key.dataset.hovered == "0"){
                            if(calls_monitor_key.dataset.state == "1"){
                                calls_monitor_key.dataset.state = "0";
                                calls_monitor_content.classList.add("hidden");
                            }
                            if(calls_monitor_key.dataset.state == "2"){
                                calls_monitor_key.dataset.state = "1";
                            }						
                        }
                        calls_monitor_key.dataset.hovered = "0";
                }                    
                
            </script>