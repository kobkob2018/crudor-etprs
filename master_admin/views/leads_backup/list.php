<h2>רשימת גיבוי טלפונים ממערכת ישנה</h2>
<hr/>
<div class = "filter-wrap">

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
                    חיפוש טלפון, שם או אימייל: <br/>
                    <input type="text" class = 'table-input' name = 'filter[free]' value = "<?= $info['filter_input']['free'] ?>" />
                </div>
                <div class="col"><input class="filter-submit" type="submit" value="חפש" /></div>
            </div>                
        </form>
    </div>
</div>


<hr/>
<h3>
    תוצאות מהטפסים
</h3>

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
    </div>
    <?php foreach($info['biz_requests'] as $biz_request): ?>
        <div class="request-list-tr table-tr row">
        <div class="col">
            <?= hebdt($biz_request['date_in'],"d-m-Y") ?><br/>
            <?= hebdt($biz_request['date_in'],"H:i") ?><br/>
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
            <?= $biz_request['city'] ?>
        </div>
        <div class="col">
            <?= $biz_request['cat_label'] ?>
        </div>
    </div>       
    <?php endforeach; ?>
</div>



<h3>
    תוצאות משיחות טלפון
</h3>

<div class="request-list flex-table">
    <div class="request-list-th table-th row">
        <div class="col">
            תאריך
        </div>

        <div class="col">
            התקשרו מ
        </div>
        <div class="col">
            אל
        </div>
        <div class="col">
            did
        </div>
        <div class="col">
            תשובה
        </div>
        <div class="col">
            לקוח שקיבל שיחה
        </div>
    </div>
    <?php foreach($info['calls'] as $biz_request): ?>
        <div class="request-list-tr table-tr row">
        <div class="col">
            <?= hebdt($biz_request['call_date'],"d-m-Y") ?><br/>
            <?= hebdt($biz_request['date_in'],"H:i") ?><br/>
        </div>
       
        <div class="col">
            <?= $biz_request['call_from'] ?>
        </div>
        <div class="col">
            <?= $biz_request['call_to'] ?>
        </div>
        <div class="col">
            <?= $biz_request['did'] ?>
        </div>
        <div class="col">
            <?= $biz_request['answer'] ?>
        </div>
        <div class="col">
            <?= $biz_request['customer_name'] ?>
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
</script>