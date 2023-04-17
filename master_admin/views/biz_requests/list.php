<div class="hidden">
    <form action = "" class="filter-keeper">
        <?php foreach($_REQUEST['filter'] as $filter_key=>$filter_val): ?>
            <?php if(is_array($filter_val)): ?>
                <?php foreach($filter_val as $key_2=>$val_2): ?>
                    <input type="hidden" name="filter[<?= $filter_key ?>][<?= $key_2 ?>]" value="<?= $val_2 ?>" />
                <?php endforeach; ?>
            <?php else: ?>
                <input type="hidden" name="filter[<?= $filter_key ?>]" value="<?= $filter_val ?>" />
            <?php endif; ?>
        <?php endforeach; ?>
    </form>
</div>

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
                <input type="hidden" class="status-holder-field" name="filter[status]" value="<?= $info['filter_str']['status'] ?>" />
                <input type="hidden" class="status-holder-field" name="filter[limit_count]" value="<?= $info['filter_str']['limit_count'] ?>" />

                <div class="col">
                    מתאריך: <br/>
                    <input type="text" class = 'table-input' name = 'filter[date_s]' value = "<?= $info['filter_str']['date_s'] ?>" />
                </div>
                <div class="col">
                    עד תאריך: <br/>
                    <input type="text" class = 'table-input' name = 'filter[date_e]' value = "<?= $info['filter_str']['date_e'] ?>" />
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
                    <input type="text" class = 'table-input' name = 'filter[ip]' value = "<?= $info['filter_str']['ip'] ?>" />
                </div>
                <div class="col">
                    חיפוש חפשי: <br/>
                    <input type="text" class = 'table-input' name = 'filter[free]' value = "<?= $info['filter_str']['free'] ?>" />
                </div>
                <div class="col"><input type="submit" value="חפש" /></div>
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
<script type="text/javascript">
    document.querySelectorAll(".filter-a-clicker").forEach(clicker=>{
        const c_wrap = clicker.closest(".filter-wrap");
        const c_form = c_wrap.querySelector("form.filter-form");
        const class_find = clicker.dataset.field+"-holder-field";
        const c_val = clicker.dataset.value;
        const c_input = c_wrap.querySelector("."+class_find);
        clicker.addEventListener("click",function(){
            c_input.value = c_val;
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
</script>
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
            <?php foreach($biz_request['cat_tree'] as $cat): ?>
                <?= $cat['label'] ?><br/>
            <?php endforeach; ?>
        </div>
        <div class="col">
            <?= $biz_request['banner_name'] ?>
        </div>
        <div class="col">
            <?= $biz_request['banner_name'] ?>
        </div>
        <div class="col">
            <a href = "javascript://" onClick = "link_with_filter" >
                שלח
            </a>
        </div>
    </div>       
    <?php endforeach; ?>
</div>

<?php 
print_r_help($info);
?>