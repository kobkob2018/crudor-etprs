<h2>רשימת בקשות להצעת מחיר</h2>
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
                    הגיע מחיפוש ב: <br/>
                    <select class = 'table-input' name="filter[referrer]" >
                        <option value="">הכל</option>
                        <?php foreach($info['referrer_options'] as $option): ?>
                            <option value="<?= $option['value'] ?>" <?= $option['selected_str'] ?> >
                                <?= $option['label'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    ip: <br/>
                    <input type="text" class = 'table-input' name = 'filter[ip]' value = "<?= $info['filter_input']['ip'] ?>" />
                </div>
                <div class="col">
                    חיפוש חפשי: <br/>
                    <input type="text" class = 'table-input' name = 'filter[free]' value = "<?= $info['filter_input']['free'] ?>" />
                </div>
                <div class="col"><input class="filter-submit" type="submit" value="חפש" /></div>
            </div>
    
            <div class="check-show <?= $info['campaign_types_checked'] ?>">
                <div class="check-show-main check-show-el">
                    <input type="checkbox" class="input-checkbox" value="1" name="filter[filter_campaign_types]" <?= $info['campaign_types_checked'] ?> /> סנן לפי סוג קמפיין
                </div>
                <div class="check-show-child check-show-el">
                    <input type="checkbox" class="input-checkbox" value="1" name="filter[campaign_types][0]" <?= $info['campaign_types_checkboxes']['0']['checked_str'] ?> /> ללא קמפיין
                </div>
                <div class="check-show-child check-show-el">
                    <input type="checkbox" class="input-checkbox" value="1" name="filter[campaign_types][1]" <?= $info['campaign_types_checkboxes']['1']['checked_str'] ?> /> קמפיין פייסבוק
                </div>
                <div class="check-show-child check-show-el">
                    <input type="checkbox" class="input-checkbox" value="1" name="filter[campaign_types][2]" <?= $info['campaign_types_checkboxes']['2']['checked_str'] ?> /> קמפיין גוגל
                </div>
            </div>
                
        </form>
    </div>
</div>

<hr/>

<div class="marks-menu-wrap">
    <h5>סימונים</h5>
    <div class="marks-menu">
        <div class="mark-wrap">     
            <div class="mark mark-is-mobile">m</div> מובייל
        </div>
        <div class="mark-wrap">     
            <div class="mark mark-google-campaign campaign_type-02">קמפיין גוגל</div>
        </div>
        <div class="mark-wrap">     
            <div class="mark mark-fb-campaign campaign_type-01">קמפיין פייסבוק</div>
        </div>
        <div class="mark-wrap">     
            <div class="mark mark-fb-lead">ליד פייסבוק</div>
        </div>
        <div class="mark-wrap">     
            <div class="mark mark-affiliate">a</div>הגיע משותף
        </div>
        <div class="mark-wrap">     
            <div class="mark mark-not-sent">X</div>לא נשלח לאף לקוח
        </div>
    </div>
</div>

<hr/>

<div class="request-list flex-table">
    <div class="request-list-th table-th row">
        <div class="col">
            תאריך
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
            הערות
        </div>
        <div class="col">
            IP
        </div>
        <div class="col">
            עיר
        </div>
        <div class="col">
            שליחות
        </div>
        <div class="col">
            קטגוריה
        </div>
        <div class="col">
            באנר
        </div>
        <div class="col">
            סטטוס עבודה
        </div>
        <div class="col">
            שלח
        </div>
    </div>
    <?php foreach($info['biz_requests'] as $biz_request): ?>
        <div class="request-list-tr table-tr row  campaign_type-0<?= $biz_request['campaign_type'] ?>">
        <div class="col">
            <?= hebdt($biz_request['date_in'],"d-m-Y") ?><br/>
            <?= hebdt($biz_request['date_in'],"H:i") ?><br/>

            <?php if($biz_request['is_mobile'] == '1'): ?>
                <div class="mark mark-is-mobile">m</div>
            <?php endif; ?>
            <?php if($biz_request['aff_id'] != ''): ?>
                <div class="mark mark-affiliate">a</div>
            <?php endif; ?>
            <?php if($biz_request['recivers'] == '0'): ?>
                <div class="mark mark-not-sent">X</div>
            <?php endif; ?>
        </div>
        <div class="col">
            <?= $biz_request['full_name'] ?>
        </div>
        <div class="col">
            <?= $biz_request['phone'] ?>
        </div>
        <div class="col">
            <?= $biz_request['email'] ?>
        </div>
        <div class="col">
            <?= $biz_request['note'] ?>
            <br/>
            <?= $biz_request['extra_info'] ?>
        </div>
        <div class="col">
            <?= $biz_request['ip'] ?>
        </div>
        <div class="col">
            <?= $biz_request['city_name'] ?>
        </div>
        <div class="col">
            <?= $biz_request['recivers'] ?>
        </div>
        <div class="col">
            <?php if($biz_request['cat_id'] == '0' || $biz_request['cat_id'] == ''): ?>
                לא נבחרה קטגוריה
            <?php elseif(empty($biz_request['cat_tree'])): ?>
                הקטגוריה נמחקה
            <?php endif; ?>
            <?php foreach($biz_request['cat_tree'] as $cat): ?>
                <?= $cat['label'] ?><br/>
            <?php endforeach; ?>
        </div>
        <div class="col">
            <?= $biz_request['banner_name'] ?>
        </div>
        <div class="col">
            <select class="auto-change-status" data-row_id = "<?= $biz_request['id'] ?>">
                <?php foreach($info['status_options'] as $option_key=>$option): ?>
                    <?php if($option_key != 'all'): ?>
                        <?php $selected_str = $option['value'] == $biz_request['status']? "selected" : ""; ?>
                        <option value="<?= $option['value'] ?>" <?= $selected_str ?> ><?= $option['label'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <a href = "<?= inner_url("biz_requests/view/?row_id=") ?><?= $biz_request['id'] ?>">
                שלח
            </a>
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

    
    document.querySelectorAll(".check-show").forEach(checkShow=>{
        
        const checkSwitch = checkShow.querySelector(".check-show-main input");
        checkSwitch.addEventListener("change",function(){
            if(checkSwitch.checked){
                checkShow.classList.add("checked");
            }
            else{
                checkShow.classList.remove("checked");
            }
        });
    });

    document.querySelectorAll(".check-show").forEach(checkShow=>{
        
        const checkSwitch = checkShow.querySelector(".check-show-main input");
        checkSwitch.addEventListener("change",function(){
            if(checkSwitch.checked){
                checkShow.classList.add("checked");
            }
            else{
                checkShow.classList.remove("checked");
            }
        });
    });

    document.querySelectorAll(".auto-change-status").forEach(selectEl=>{
        
        selectEl.addEventListener("change",function(event){
            const select = event.target;
            const rowId = select.dataset.row_id;
            const status = select.value;
            const url = "<?= inner_url('biz_requests/status_update/?row_id=') ?>" + rowId + "&status="+status;
            window.location.href = url;
        })
    });
</script>