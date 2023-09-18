<h3>התאמת קטגוריות ממערכת ישנה</h3>
<?php $this->include_view("migration_site/header.php"); ?>
<div class="loading-tag-wrap hidden">
    <div class="loading-tag">
        WAIT...
    </div>
</div>
<div class="cat-compare-wrap">
    <div class="new-cats">

        <h3>קטגוריות חדשות</h3>
        <div class="items-table flex-table new-cat-list">
            <div class="table-th row">
                <div class="col col-tiny">#</div>
                <div class="col">שם</div>

                <div class="col col-tiny">סטטוס</div>
                <div class="col">תיאום</div>
                <div class="col">כפתור שיוך</div>
            </div>
            <?php foreach($this->data['current_cat_list'] as $cat): ?>
                <div class="new-cat awaiting-children-current table-tr row active-0<?= $cat['active'] ?> is-visible-0<?= $cat['visible'] ?> deep-0<?=  $cat['deep'] ?>" data-cat_id="<?= $cat['id'] ?>">
                    <div class="col col-tiny">
                        <?= $cat['id'] ?>
                        <?php if($cat['visible'] == '0'): ?>
                            <b class="red">נסתר!</b>
                        <?php endif; ?>
                        <?php if($cat['active'] == '0'): ?>
                            <b class="red">לא פעיל!</b>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <div class="deep-0<?=  $cat['deep'] ?>">
                            <?= $cat['label'] ?>
                        </div>
                    </div>
                    <div class="col col-tiny">
                        <?= $cat['active'] ?>
                    </div>
                    <div class="col pairs-col awaiting-pairs" data-cat_id="<?= $cat['id'] ?>">

                    </div>
                    <div class="col col-tiny">

                        <a class="pair-cat-prepare" href="javascript://" onclick="pair_cat_prepare(this)" data-cat_id="<?= $cat['id'] ?>">
                            <<-
                        </a> 
                    </div>           
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="old-cats">
        <h3>קטגוריות ישנות</h3>

        <div>
            <input type="checkbox" onchange="toggle_ads(this)" /> הסתר קטגוריות עם פרסומת
        </div>
        <div class="items-table flex-table old-cat-list">
            <div class="table-th row">
                <div class="col col-tiny"></div>
                <div class="col col-tiny">#</div>
                <div class="col">שם</div>

                <div class="col col-tiny">סטטוס</div>
                <div class="col">תיאום</div>
            </div>
            <?php foreach($this->data['migrate_cat_list'] as $cat): ?>
                <div class="old-cat awaiting-children-old old-cat-<?= $cat['id'] ?> table-tr row cat_status-0<?= $cat['status'] ?>  is-hidden-0<?= $cat['hidden'] ?> deep-0<?=  $cat['deep'] ?> has-ad-0<?= $cat['googleADSense'] == '' ? '0': '1' ?>" data-cat_id="<?= $cat['id'] ?>">
                    <div class="col col-tiny">
                        <a class="pair-button" href="javascript://" onclick="pair_cat_go(this)" data-cat_id="<?= $cat['id'] ?>">
                        <<-
                        </a> 
                    </div>
                    <div class="col col-tiny">
                        <?= $cat['id'] ?>
                        <?php if($cat['hidden'] == '1'): ?>
                            <b class="red">נסתר!</b>
                        <?php endif; ?>
                        <?php if($cat['status'] == '2'): ?>
                            <b class="red">לא פעיל!</b>
                        <?php endif; ?>

                        <?php if($cat['googleADSense'] != ''): ?>
                            <br/><br/>
                            <b class="red">מכיל פרסומת!</b>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <div class="deep-0<?=  $cat['deep'] ?>">
                            <?= $cat['cat_name'] ?>
                        </div>
                    </div>
                    <div class="col col-tiny">
                        <?= $cat['status'] ?>
                    </div>
                    <div class="col">
                        <div class="pair-label">
                            <small class="pair-label">
                                <?= $cat['pair_label'] ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<style type="text/css">
    .new-cats{margin-left: 100px;}
	.cat-compare-wrap{display: flex;}
    
    .deep-02{padding-right: 50px; background: #ffffa1;}
    .deep-03{padding-right: 100px; background: #ffff71;}
    .deep-04{padding-right: 150px;background: #ffff2a;}

    .is-hidden-01, .is-visible-00{background: gray; }
    .active-00, .status-02{background: pink; }
    .old-cats .pair-button{display:none; }
    .old-cats.ready .pair-button{display:block;}
    .pair-button-mark-ready{display: none;}
    .current-cat-el-prepare .pair-button-mark-ready{display: block;}
    .current-cat-el-prepare{background: #c2c2f7;}
    .new-cat-pair{
        position: relative;
        display: block;
        background: pink;
        border: 1px solid red;
        padding: 5px;
        margin: 5px;
        border-radius: 3px;
    }
    .new-cat-pair .pair-x{
        font-size: 18px;
        text-decoration: none;
        color: red;
        font-family: Arial;
        display: block;
        
    }
	.loading-tag-wrap{
        top:0px;
        left: 0px;
        text-align: center;
        position: fixed;
        font-size: 20px;
		background: #eeaaffa1;
        padding-top: 50px;
        max-height: 200px;
        overflow: auto;
        width: 100%;
    }
    .hide-ads .has-ad-01{
        display: none;
    }

    .hide-ads .has-ad-01.deep-01{
        display: table-row;
    }

    .new-cat-list,.old-cat-list{
        max-height: 80vh;
        overflow: auto;
    }
</style>

<script type="text/javascript">

    let curent_a_cat_prepare = null;
    function pair_cat_go(a_el){
        
    }

    function pair_cat_prepare(a_el){
        document.querySelectorAll(".current-cat-el-prepare").forEach(cat_el=>{
            cat_el.classList.remove("current-cat-el-prepare");
        });
        document.querySelectorAll(".old-cats").forEach(cat_list=>{
            cat_list.classList.add("ready");
        });
        a_el.closest(".new-cat").classList.add("current-cat-el-prepare");
    }

    function pair_remove(a_el){
        show_loading("wait...");
        const url = "<?= inner_url("migration_cat/pair_remove/?old_cat_id=") ?>"+a_el.dataset.old_cat_id;
        fetch(url).then((res) => res.json()).then(info => {
            a_el.closest(".pair-label").remove();
            if(info.old_cat_remove != "-1"){
                document.querySelector(".old-cat-"+info.old_cat_remove).querySelector(".pair-label").innerHTML = "";
            }
            console.log(info);
            hide_loading();
        });
    }

    function pair_cat_go(pair_a_el){

        show_loading("wait...");
        const a_el = document.querySelector(".current-cat-el-prepare").querySelector(".pair-cat-prepare");
        
        document.querySelectorAll(".cat-pair-for-old-"+pair_a_el.dataset.cat_id).forEach(el=>{el.remove()});
        const url = "<?= inner_url("migration_cat/pair_go/?cat_id=") ?>"+a_el.dataset.cat_id + "&pair_cat="+pair_a_el.dataset.cat_id;
        fetch(url).then((res) => res.json()).then(info => {
            const labels_col = a_el.closest(".new-cat").querySelector(".pairs-col");
            const new_label_html = 
                "<small class='new-cat-pair pair-label cat-pair-for-old-"+pair_a_el.dataset.cat_id+"'> " + 
                    "<a class='pair-x' href='javascript://' onclick='pair_remove(this)' data-old_cat_id='"+pair_a_el.dataset.cat_id+"' > " + 
                        "X" + 
                    "</a>" + 
                    info.old_cat_label + 
                "</small>";

            labels_col.innerHTML = labels_col.innerHTML + new_label_html;
            pair_a_el.closest(".old-cat").querySelector(".pair-label").innerHTML = info.cat_label;

            console.log(info);
            hide_loading();
        });
    }

    function toggle_ads(checkbox){
        if(checkbox.checked){
            document.querySelector(".old-cats").classList.add('hide-ads');
        }
        else{
            document.querySelector(".old-cats").classList.remove('hide-ads');
        }
    }

	function show_loading(str){
		document.querySelector(".loading-tag-wrap").innerHTML = str;
        document.querySelector(".loading-tag-wrap").classList.remove("hidden");
    }
    
    function add_loading(str){
        console.log(str);
        const loading = document.querySelector(".loading-tag-wrap");
        loading.innerHTML += "<br/>"+str;
        loading.scrollTop = loading.scrollHeight;
    }

    function hide_loading(){
		document.querySelector(".loading-tag-wrap").innerHTML = "WAIT...";
        document.querySelector(".loading-tag-wrap").classList.add("hidden");
    }

    function init_fetch_current_category_data(){
        show_loading("fatching sub categories...");
        return fetch_current_category_data();
    }

    function fetch_current_category_data(){
        const root_cat_current_el = document.querySelector(".awaiting-children-current");
        if(!root_cat_current_el){
            return init_fetch_current_category_pairs();
        }
        root_cat_current_el.classList.remove("awaiting-children-current");
        const cat_id = root_cat_current_el.dataset.cat_id;
        add_loading("fatching sub cats for cat: "+ cat_id);

        setTimeout(function(){
            fetch_current_sub_cats_for_element(root_cat_current_el,cat_id);
        },500);
        
        
    }

    function init_fetch_current_category_pairs(){
        show_loading("fatching sub category pairs...");
        return fetch_current_category_pairs();
    }

    function fetch_current_category_pairs(){
        const pairs_cat_current_el = document.querySelector(".awaiting-pairs");
        if(!pairs_cat_current_el){
            return init_fetch_old_category_data();
        }
        pairs_cat_current_el.classList.remove("awaiting-pairs");
        const cat_id = pairs_cat_current_el.dataset.cat_id;
        add_loading("fatching pairs for cat: "+ cat_id);
        
        return fetch_current_category_pairs();
    }

    function init_fetch_old_category_data(){
        show_loading("fatching OLD sub categories...");
        return fetch_old_category_data();
    }

    function fetch_old_category_data(){
        const root_cat_old_el = document.querySelector(".awaiting-children-old");
        if(!root_cat_old_el){
            alert("done!");
            hide_loading();
            return;
        }
        root_cat_old_el.classList.remove("awaiting-children-old");
        const cat_id = root_cat_old_el.dataset.cat_id;
        add_loading("fatching sub cats for old cat: "+ cat_id);
        setTimeout(function(){
            fetch_old_sub_cats_for_element(root_cat_old_el,cat_id);
        },500);        
    }



    function fetch_current_sub_cats_for_element(el,cat_id){
        let after_el = el;
        const url = "<?= inner_url("migration_cat/fetch_sub_cats_current/?cat_id=") ?>"+cat_id;
        fetch(url).then((res) => res.json()).then(info => {

            const divhelper = document.createElement("div");
            divhelper.innerHTML = info.html;
            divhelper.querySelectorAll(".append-sub").forEach(sub_el=>{

                console.log(divhelper.innerHTML);
                if(after_el.nextSibling){
                    after_el.parentNode.insertBefore(sub_el, after_el.nextSibling);
                }
                else{
                    after_el.parentNode.append(sub_el);
                }
                after_el = sub_el;
                sub_el.classList.remove("append-sub");
            });
            divhelper.remove();
        });
        
        return fetch_current_category_data();
    }

    function fetch_old_sub_cats_for_element(el,cat_id){
        let after_el = el;
        const url = "<?= inner_url("migration_cat/fetch_sub_cats_old/?cat_id=") ?>"+cat_id;
        fetch(url).then((res) => res.json()).then(info => {

            const divhelper = document.createElement("div");
            divhelper.innerHTML = info.html;
            divhelper.querySelectorAll(".append-sub").forEach(sub_el=>{

                console.log(divhelper.innerHTML);
                if(after_el.nextSibling){
                    after_el.parentNode.insertBefore(sub_el, after_el.nextSibling);
                }
                else{
                    after_el.parentNode.append(sub_el);
                }
                after_el = sub_el;
                sub_el.classList.remove("append-sub");
            });
            divhelper.remove();
        });
        
        return fetch_old_category_data();
    }

    document.addEventListener("DOMContentLoaded",()=>{
        init_fetch_current_category_data();
    });
</script>