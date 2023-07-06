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
                <div class="new-cat table-tr row active-0<?= $cat['active'] ?> is-visible-0<?= $cat['visible'] ?> deep-0<?=  $cat['deep'] ?>">
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
                    <div class="col">
                        <?php foreach($cat['pairs'] as $cat_pair): ?> 
                        <small class="new-cat-pair pair-label">
                            <a class="pair-x" href="javascript://" onclick="pair_remove(this)" data-olld_cat_id="<?= $cat_pair['old_cat_id'] ?>" >X</a>
                           <?= $cat_pair['label'] ?>
                        </small>
                        <?php endforeach; ?>
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
        <div class="items-table flex-table old-cat-list">
            <div class="table-th row">
                <div class="col col-tiny"></div>
                <div class="col col-tiny">#</div>
                <div class="col">שם</div>

                <div class="col col-tiny">סטטוס</div>
                <div class="col">תיאום</div>
            </div>
            <?php foreach($this->data['migrate_cat_list'] as $cat): ?>
                <div class="old-cat old-cat-<?= $cat['id'] ?> table-tr row cat_status-0<?= $cat['status'] ?>  is-hidden-0<?= $cat['status'] ?> deep-0<?=  $cat['deep'] ?>">
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
    }
    .new-cat-pair .pair-x{
        text-align: left;
        font-size: 18px;
        display: block;
    }
    .loading-tag-wrap{
        top:0px;
        left: 0px;
        text-align: center;
        position: fixed;
        background: #c2c2f7aa;
        font-size: 150px;
        padding-top: 50px;
        height: 100%;
        width: 100%;
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
        show_loading();
        const url = "<?= inner_url("migration_cat/pair_remove/?old_cat_id=") ?>"+a_el.dataset.cat_id;
        fetch(url).then((res) => res.json()).then(info => {
            a_el.closest(".new-cat").querySelector(".pair-label").remove();
            if(info.old_cat_remove != "-1"){
                document.querySelector(".old-cat-"+info.old_cat_remove).querySelector(".pair-label").innerHTML = "";
            }
            console.log(info);
            hide_loading();
        });
    }

    function pair_cat_go(pair_a_el){

        show_loading();
        const a_el = document.querySelector(".current-cat-el-prepare").querySelector(".pair-cat-prepare");
        a_el.dataset.cat_id;

        const url = "<?= inner_url("migration_cat/pair_go/?cat_id=") ?>"+a_el.dataset.cat_id + "&pair_cat="+pair_a_el.dataset.cat_id;
        fetch(url).then((res) => res.json()).then(info => {
            const labels_col = a_el.closest(".new-cat").querySelector(".pair-label").closest(".col");
            const new_label_html = 
                "<small class='new-cat-pair pair-label'> " + 
                    "<a class='pair-x' href='javascript://' onclick='pair_remove(this)' data-old_cat_id='"+pair_cat+"' > " + 
                        "X" + 
                    "</a>" + 
                    old_cat_label + 
                "</small>'";

            labels_col.innerHTML = labels_col.innerHTML + info.old_cat_label;
            pair_a_el.closest(".old-cat").querySelector(".pair-label").innerHTML = info.cat_label;

            console.log(info);
            hide_loading();
        });
    }

    function show_loading(){
        document.querySelector(".loading-tag-wrap").classList.remove("hidden");
    }
    
    function hide_loading(){
        document.querySelector(".loading-tag-wrap").classList.add("hidden");
    }
</script>