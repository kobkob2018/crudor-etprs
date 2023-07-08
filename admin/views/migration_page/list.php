<h3>ייבוא עמודים ממערכת ישנה</h3>
<?php $this->include_view("migration_site/header.php"); ?>
<div class="focus-box">

    <b>דומיין:</b> <?= $this->data['migration_site']['old_domain'] ?> <br/>
    <b>מספר:</b> <?= $this->data['migration_site']['old_id'] ?> <br/>
    <b>unk:</b> <?= $this->data['migration_site']['old_unk'] ?> <br/>
    <b>כותרת:</b> <?= $this->data['migration_site']['old_title'] ?> <br/>

</div>

<div class="focus-box">
    <form class="filter-form" action = "<?= inner_url("migration_page/list/") ?>" method = "GET">
        <div class='lead_form_item form-group'>
            <label for="page">עמוד</label>
            <select name="page" class="form-input" onchange="submit_filter_form(this)">
                <?php foreach($this->data['page_options'] as $option): ?>
                    <option value = "<?= $option['index'] ?>" <?= $option['selected_str'] ?> >
                        <?= $option['index'] ?>
                    </option>

                    
                <?php endforeach; ?>
            </select> 
            <br/>
            מתוך <?= count($this->data['page_options']) ?>
        </div>
    </form>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">מספר דף</div>
        <div class="col col-tiny">type</div>

        <div class="col">מצב ייבוא</div>
        <div class="col">כותרת</div>
        <div class="col">
            קטגוריה ישנה <hr/> קטגוריה חדשה
        </div>
		<div class="col">קטגוריה בדף הקיים</div>
        <div class="col">גרסת ייבוא</div>
        
        <div class="col">
            <a class="button-focus auto-import-button off" href="javascript://" onclick="run_auto_import(this)" data-state="off">
                <span class="off-label">
                    התחלת ייבוא רציף
                </span>
                <span class="on-label">
                    הפסק ייבוא רציף
                </span>
                
            </a>
            <a class="auto-delete-button off" href="javascript://" onclick="run_auto_delete(this)" data-state="off">
                <span class="off-label">
                    התחלת מחיקה רציפה
                </span>
                <span class="on-label">
                    הפסק מחיקה רציפה
                </span>
                
            </a>
        </div>
    </div>
    <?php foreach($this->data['migrate_page_list'] as $migrate_page): ?>
        <div class="table-tr row is-hidden-0<?= $migrate_page['hide_page'] ?>">
            <div class="col col-tiny">
                <?= $migrate_page['id'] ?>
                <?php if($migrate_page['deleted']): ?>
                    <b class="red">מחוק!</b>
                <?php endif; ?>
				<?php if($migrate_page['hide_page']): ?>
					<b class="red">נסתר!</b>
				<?php endif; ?>
            </div>
            <div class="col col-tiny">
                <?= $migrate_page['type'] ?>
            </div>

            <div class="col migrate-state-holder">
                <?php if($migrate_page['migrated_page']['migrated']): ?>
                    <a target="_BLANK" href = "<?= inner_url('pages/edit/') ?>?row_id=<?= $migrate_page['migrated_page']['page_id'] ?>" title="צפה בדף">ערוך דף</a>

                    <br/>

                    <a class="button-focus fix-page-button" onclick = "fix_page_fetch(this)" data-page_id="<?= $migrate_page['migrated_page']['page_id'] ?>" href = "javascript://"  title="תקן תכנים">תקן תכנים</a>
                <?php else: ?>
                לא
                <?php endif; ?>
            </div>
            <div class="col">
                <?= $migrate_page['name'] ?>
            </div>
            <div class="col">
                <?= $migrate_page['old_cat_str'] ?>
                <hr/>
                <?= $migrate_page['new_cat_str'] ?>
            </div>
             <div class="col migrate-cat-str-holder">
               <?= $migrate_page['migrated_page']['cat_str'] ?>
            </div>           

            <div class="col migrate-version-holder">
                <?= $migrate_page['migrated_page']['version'] ?>
            </div>

            
            <div class="col migrate-button-holder">
                <?php if($migrate_page['migrated_page']['migrated']): ?>
                    <a class="page-delete-button" href="javascript://" onclick = "delete_page_fetch(this)"  data-page_id="<?= $migrate_page['migrated_page']['page_id'] ?>" title="מחק">מחק את הדף המיובא</a>
				<?php else: ?>
				
					<a class="button-focus page-import-button" href = "javascript://" onclick="import_page_fetch(this)" data-page_id = "<?= $migrate_page['id'] ?>" />ייבוא</a>
				<?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="loading-tag-wrap hidden">
    <div class="loading-tag">
        WAIT...
    </div>
</div>

<style type="text/css">
	.is-hidden-01{background: gray; }
	.loading-tag-wrap{
        top:0px;
        left: 0px;
        text-align: center;
        position: fixed;
        font-size: 30px;
		background: #eeaaffa1;
        padding-top: 50px;
        
        width: 100%;
    }
    .auto-import-button.on .off-label{display:none;}
    .auto-import-button.off .on-label{display:none;}
</style>

<script type="text/javascript">

    function run_auto_import(a_el){
        if(a_el.dataset.state == "off"){
            if(!confirm("האם אתה בטוח שברצונך להתחיל ייבוא רציף של כל הדפים בעמוד זה?")){
                return;
            }
            a_el.dataset.state = "on";
            a_el.classList.remove("off");
            a_el.classList.add("on");
            auto_import_next_page();
        }
        else{
            a_el.dataset.state = "off";
            a_el.classList.remove("on");
            a_el.classList.add("off"); 
        }
    }

    function run_auto_delete(a_el){
        if(a_el.dataset.state == "off"){
            if(!confirm("האם אתה בטוח שברצונך להתחיל מחיקה רציפה של כל הדפים בעמוד זה?")){
                return;
            }
            a_el.dataset.state = "on";
            a_el.classList.remove("off");
            a_el.classList.add("on");
            auto_delete_next_page();
        }
        else{
            a_el.dataset.state = "off";
            a_el.classList.remove("on");
            a_el.classList.add("off"); 
        }
    }

    function auto_import_next_page(){
        const auto_import_button = document.querySelector(".auto-import-button");
        if(auto_import_button.dataset.state != "on"){
            return;
        }
        setTimeout(function(){
            const next_import_button = document.querySelector(".page-import-button");
            if(!next_import_button){
                alert("אין עוד דפים לייבוא");
                return;
            }
            next_import_button.click();
        }, 500);
    }

    function auto_delete_next_page(){
        const auto_import_button = document.querySelector(".auto-delete-button");
        if(auto_import_button.dataset.state != "on"){
            return;
        }
        setTimeout(function(){
            const next_delete_button = document.querySelector(".page-delete-button");
            if(!next_delete_button){
                alert("אין עוד דפים למחיקה");
                return;
            }
            next_delete_button.click();
        }, 500);
    }

    function submit_filter_form(select){
        select.closest(".filter-form").submit();
    }

	function import_page_fetch(a_el){
		
		const page_id = a_el.dataset.page_id;
		show_loading("please wait! <br/>importing page #"+page_id);
		const url = "<?= inner_url("migration_page/import_page/") ?>?page_id="+page_id;
		const page_row = a_el.closest(".row");
		console.log(page_row);
		const cat_holder = page_row.querySelector(".migrate-cat-str-holder");
		const version_holder = page_row.querySelector(".migrate-version-holder");
		const button_holder = page_row.querySelector(".migrate-button-holder");
		const state_holder = page_row.querySelector(".migrate-state-holder");
		fetch(url).then((res) => res.json()).then(info => {
			hide_loading();
			console.log(info);
			cat_holder.innerHTML = info.cat_str;
			version_holder.innerHTML = info.version;
			
			button_holder.innerHTML = '<a class="page-delete-button" href="javascript://" onclick = "delete_page_fetch(this)"  data-page_id="'+info.page_id+'" title="מחק">מחק את הדף המיובא</a>';
			
			state_holder.innerHTML = '<a target="_BLANK" href="<?= inner_url("pages/edit/?row_id=") ?>'+info.page_id+'" title="ערוך">עריכת דף</a>';

            state_holder.innerHTML += '<br/><a class="button-focus fix-page-button" onclick = "fix_page_fetch(this)" href = "javascript://" data-page_id="'+info.page_id+'" title="תיקון תוכן">תקן תמונות ותכנים אחרים</a>';
			 
            auto_import_next_page();
		});
	}

    function fix_page_fetch(a_el){
        const page_id = a_el.dataset.page_id;
        show_loading("please wait! <br/>removing page #"+page_id);
		const url = "<?= inner_url("migration_page/get_page_blocks/") ?>?page_id="+page_id;
		const page_row = a_el.closest(".row");
        const div_holder = document.createElement("div");
        fetch(url).then((res) => {
            div_holder.innerHTML = res;
            a_el.closest(".row").append(div_holder);

        });
    }

	function delete_page_fetch(a_el){
		
		const page_id = a_el.dataset.page_id;
		show_loading("please wait! <br/>removing page #"+page_id);
		const url = "<?= inner_url("migration_page/delete_migration/") ?>?page_id="+page_id;
		const page_row = a_el.closest(".row");
		console.log(page_row);
		const cat_holder = page_row.querySelector(".migrate-cat-str-holder");
		const version_holder = page_row.querySelector(".migrate-version-holder");
		const button_holder = page_row.querySelector(".migrate-button-holder");
		const state_holder = page_row.querySelector(".migrate-state-holder");
		fetch(url).then((res) => res.json()).then(info => {
			hide_loading();
			console.log(info);
			cat_holder.innerHTML = "";
			version_holder.innerHTML = "";
			button_holder.innerHTML = '<a class="button-focus" href="javascript://" onclick="import_page_fetch(this)" data-page_id="'+info.old_page_id+'">ייבוא</a>';
			state_holder.innerHTML = "לא";
            auto_delete_next_page();
		});
	}
	
	function show_loading(str){
		document.querySelector(".loading-tag-wrap").innerHTML = str;
        document.querySelector(".loading-tag-wrap").classList.remove("hidden");
    }
    
    function hide_loading(){
		document.querySelector(".loading-tag-wrap").innerHTML = "WAIT...";
        document.querySelector(".loading-tag-wrap").classList.add("hidden");
    }
</script>
