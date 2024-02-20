<h2>רשימת בקשות להצעת מחיר שהגיעו לספאם</h2>
<hr/>
<div class = "filter-wrap">
    <div class="filter-menu">

    </div>
    <div class="filter-form-wrap">
        <form  class="flex-table filter-form" action = "" method = "POST" >
            <div class="table-tr row ">
                
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
            סיבת הדחייה
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
            שלח
        </div>
    </div>
    <?php foreach($info['biz_requests'] as $biz_request): ?>
        <div class="request-list-tr table-tr row">
        <div class="col">
            <?= hebdt($biz_request['date_in'],"d-m-Y") ?><br/>
            <?= hebdt($biz_request['date_in'],"H:i") ?><br/>
        </div>
        <div class="col">
            <?= $biz_request['reason'] ?>
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
            <?= $this->get_city_name($biz_request['city_id']) ?>
        </div>
        <div class="col">
            <?php foreach($biz_request['cat_tree'] as $cat): ?>
                <?= $cat['label'] ?><br/>
            <?php endforeach; ?>
        </div>

        <div class="col">
            <a href = "<?= inner_url("biz_requests/send_spam/?row_id=") ?><?= $biz_request['id'] ?>">
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