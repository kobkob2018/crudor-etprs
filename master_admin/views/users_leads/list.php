<h2>רשימת לידים מרוכזת ללקוחות שנבחרו</h2>
<hr/>
<div class = "filter-wrap">
    <div class="filter-menu">
        <?php foreach($info['status_options'] as $status): ?>
            <a href = "javascript://" class = "filter-a-clicker <?= $status['selected_str'] ?>" data-field= "status" data-value = "<?= $status['value'] ?>">
                <?= $status['label']; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="filter-form-wrap">
        <form  class="flex-table filter-form" action = "" method = "POST" >
            
            <div class="table-tr row ">
                <input type="hidden" class="status-holder-field" name="filter[status]" value="<?= $info['filter_input']['status'] ?>" />
                
                <div class="col">
                    עמוד: <br/>
                    <select class = 'table-input page-select' name="filter[page]" >
                        <?php foreach($info['page_options'] as $option): ?>
                            <option value="<?= $option['index'] ?>" <?= $option['selected_str'] ?>><?= $option['index'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    מתאריך: <br/>
                    <input type="text" class = 'table-input' name = 'filter[date_s]' value = "<?= $info['filter_input']['date_s'] ?>" />
                </div>
                <div class="col">
                    עד תאריך: <br/>
                    <input type="text" class = 'table-input' name = 'filter[date_e]' value = "<?= $info['filter_input']['date_e'] ?>" />
                </div>

                <div class="col">
                    חיפוש חפשי: <br/>
                    <input type="text" class = 'table-input' name = 'filter[free]' value = "<?= $info['filter_input']['free'] ?>" />
                </div>
                <div class="col"><input class="filter-submit" type="submit" value="חפש" /></div>
            </div>
            <input type="hidden" name="filter[checkboxes_set]" value = '1' />
            <div class="check-show">
                <div class="check-show-el">
                    <input type="checkbox" class="input-checkbox" value="1" name="filter[filter_selected_users]" <?= $info['filter_selected_users'] ?> /> הצג רק לקוחות נבחרים
                </div>
            </div>
                
        </form>
    </div>
</div>

<hr/>

<hr/>

    <div class="request-list flex-table">
        <div class="request-list-th table-th row">
            <div class="col">
                תאריך
            </div>
            <div class="col">
                לקוח
            </div>
            <div class="col">
                שם מלא
            </div>
            <div class="col">
                טלפון
            </div>
            <div class="col">
                אימייל
            </div>
            <div class="col">
                עריכה
            </div>
            <div class="col">
                מידע נוסף
            </div>
            <div class="col">
                <input class="input-checkbox bulk-select-all" type="checkbox" name="bulk_select_all" value="1" />
                בחר הכל
                
                <br/>
                העברת הנבחרים לסטטוס:
                <br/>
                <form class="bulk-form" action = "<?= inner_url('users_leads/bulk_update_status/') ?>" method="POST">

                    <select class="bulk-status-select form-input" name="status">
                        <?php foreach($info['status_options'] as $option_key=>$option): ?>
                            <?php if($option_key != 'all'): ?>
                                <option value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <div class="bulk-leads-holder hidden"></div>
                </form>
            </div>
        </div>
        
            <?php foreach($info['users_leads'] as $user_lead): ?>
                <div class="request-list-tr table-tr row  campaign_type-0<?= $user_lead['campaign_type'] ?>">
                    <div class="col">
                        <?= hebdt($user_lead['date_in'],"d-m-Y") ?><br/>
                        <?= hebdt($user_lead['date_in'],"H:i") ?><br/>
                    </div>
                    <div class="col">
                        <?= $user_lead['biz_name'] ?>
                        <br/>
                        <?= $user_lead['user_name'] ?>
                    </div>
                    <div class="col">
                        <?= $user_lead['full_name'] ?>
                    </div>
                    <div class="col">
                        <?= $user_lead['phone'] ?>
                    </div>
                    <div class="col">
                        <?php if($user_lead['resource'] == "phone"): ?>
                            <b class="red">ליד טלפוני</b><br/>
                        <?php endif; ?>
                        <?= $user_lead['email'] ?>
                    </div>


                    <div class="col">
                        <form action = "<?= inner_url('users_leads/update_lead/') ?>?row_id=<?= $user_lead['id'] ?>" method="POST">
                            <div class="form-group">
                                    
                                <textarea style="height:100px;min-height:auto;" name='note'><?= $user_lead['note'] ?></textarea>
                            </div>
                            
                            <input type="checkbox" name="mark" value="1" <?php echo $user_lead['mark']=='1'?'checked':'' ?> /> הדגשה
                            <br/>
                            <br/>
                            
                            <select class="status-select form-input" name="status">
                                <?php foreach($info['status_options'] as $option_key=>$option): ?>
                                    <?php if($option_key != 'all'): ?>
                                        <?php $selected_str = $option['value'] == $user_lead['status']? "selected" : ""; ?>
                                        <option value="<?= $option['value'] ?>" <?= $selected_str ?> ><?= $option['label'] ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <input type="submit" value="שמור" />
                        </form>
                    </div>

                    <div class="col">
                        <?php foreach($this->json_to_arr($user_lead['extra']) as $k=>$v): ?>
                            <?= $k ?>: <?= $v ?><br/>
                        <?php endforeach; ?>
                    </div>
                    <div class="col">    
                        <input class="input-checkbox lead-bulk-check" type="checkbox" name="bulk_lead[<?= $user_lead['id'] ?>]" value="1" />
                    </div>
                </div>       
            <?php endforeach; ?>
        
    </div>




<script type="text/javascript">
    document.querySelectorAll(".filter-a-clicker").forEach(clicker=>{
        const c_wrap = clicker.closest(".filter-wrap");
        const c_form = c_wrap.querySelector("form.filter-form");
        const class_find = clicker.dataset.field+"-holder-field";
        const c_val = clicker.dataset.value;
        const c_input = c_wrap.querySelector("."+class_find);
        const pageSelect = c_form.querySelector(".page-select");
        clicker.addEventListener("click",function(){
            c_input.value = c_val;
            pageSelect.value = '1';
            c_form.submit();

        });
        
    });

    document.querySelectorAll(".filter-submit").forEach(select=>{
        const c_wrap = select.closest(".filter-wrap");
        const c_form = c_wrap.querySelector("form.filter-form");
        const pageSelect = c_form.querySelector(".page-select");
        select.addEventListener("click",function(event){
            event.preventDefault();
            pageSelect.value = '1';
            c_form.submit();
        });
        
    });

    document.querySelectorAll(".page-select").forEach(select=>{
        const c_wrap = select.closest(".filter-wrap");
        const c_form = c_wrap.querySelector("form.filter-form");
        select.addEventListener("change",function(){
            c_form.submit();
        });
        
    });

    document.querySelectorAll(".bulk-status-select").forEach(select=>{
        const c_form = select.closest(".bulk-form");
        const bulk_leads_holder = c_form.querySelector(".bulk-leads-holder");
        select.addEventListener("change",function(){
            document.querySelectorAll(".lead-bulk-check").forEach(check=>{
                bulk_leads_holder.append(check);
            });
            c_form.submit();
        });   
    });


    document.querySelectorAll(".bulk-select-all").forEach(check=>{
        check.addEventListener("change",function(){       
            if(check.checked){
                document.querySelectorAll(".lead-bulk-check").forEach(leadCheck=>{
                    leadCheck.checked = true;
                });
            }
            else{
                document.querySelectorAll(".lead-bulk-check").forEach(leadCheck=>{
                    leadCheck.checked = false;
                });
            }
        });   
    });

</script>