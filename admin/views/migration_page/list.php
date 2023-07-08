<h3>ייבוא עמודים ממערכת ישנה</h3>
<?php $this->include_view("migration_site/header.php"); ?>
<div class="focus-box">

    <b>דומיין:</b> <?= $this->data['migration_site']['old_domain'] ?> <br/>
    <b>מספר:</b> <?= $this->data['migration_site']['old_id'] ?> <br/>
    <b>unk:</b> <?= $this->data['migration_site']['old_unk'] ?> <br/>
    <b>כותרת:</b> <?= $this->data['migration_site']['old_title'] ?> <br/>

</div>

<div class="focus-box">
    <form action = "<?= inner_url("migration_page/list/") ?>" method = "GET">
        <div class='lead_form_item form-group'>
            <label for="page">עמוד</label>
            <select name="page" class="form-input">
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
        
        <div class="col"></div>
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
                    <a href="javascript://" onclick = "delete_page_fetch(this)"  data-page_id="<?= $migrate_page['migrated_page']['page_id'] ?>" title="מחק">מחק את הדף המיובא</a>
				<?php else: ?>
				
					<a class="button-focus" href = "javascript://" onclick="import_page_fetch(this)" data-page_id = "<?= $migrate_page['id'] ?>" />ייבוא</a>
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
</style>

<script type="text/javascript">
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
			
			button_holder.innerHTML = '<a href="javascript://" onclick = "delete_page_fetch(this)"  data-page_id="'+info.page_id+'" title="מחק">מחק את הדף המיובא</a>';
			
			state_holder.innerHTML = '<a target="_BLANK" href="<?= inner_url("pages/edit/?row_id=") ?>'+info.page_id+'" title="ערוך">עריכת דף</a>';
			 
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
