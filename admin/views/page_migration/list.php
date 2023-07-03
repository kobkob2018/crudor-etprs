<h3>ייבוא עמודים ממערכת ישנה</h3>
<?php $this->include_view("site_migration/header.php"); ?>
<div class="focus-box">

    <b>דומיין:</b> <?= $this->data['site_migration']['old_domain'] ?> <br/>
    <b>מספר:</b> <?= $this->data['site_migration']['old_id'] ?> <br/>
    <b>unk:</b> <?= $this->data['site_migration']['old_unk'] ?> <br/>
    <b>כותרת:</b> <?= $this->data['site_migration']['old_title'] ?> <br/>

</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">מספר דף</div>
        <div class="col col-tiny">type</div>
        
        <div class="col col-tiny">redierct_301</div>
        
        <div class="col">מצב ייבוא</div>
        <div class="col">כותרת</div>
        <div class="col">קטגוריה</div>
        <div class="col">תוכן נוסף בטופס</div>
        <div class="col">גרסת ייבוא</div>
        <div class="col">הועתק טופס</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['migrate_page_list'] as $migrate_page): ?>
        <div class="table-tr row is-deleted-0<?= $migrate_page['deleted'] ?> has-301-0<?= $migrate_page['redierct_301'] == ""? "0" : "1" ?>   is-hidden-0<?= $migrate_page['hide_page'] ?>">
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

            <div class="col">
                <?= $migrate_page['redierct_301'] == "" ? "" : "יש הפנייה" ?>
				
            </div>
            <div class="col">
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
                <?= $migrate_page['cat_str'] ?>
            </div>
            <div class="col">
                <?php if($migrate_page['form_content'] != ""): ?>
                    קיים תוכן נוסף
                <?php endif; ?>
            </div>
            

            <div class="col">
                <?= $migrate_page['migrated_page']['version'] ?>
            </div>
            <div class="col">
                <?= $migrate_page['migrated_page']['has_form'] ?>
            </div>
            
            <div class="col">
                <?php if($migrate_page['migrated_page']['migrated']): ?>
                    <a href = "<?= inner_url('page_migration/delete_migration/') ?>?row_id=<?= $migrate_page['migrate_page']['id'] ?>" title="מחק">מחק</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<style type="text/css">
	.is-hidden-01{background: gray; }
    .has-301-01{background:orange;}
    .is-deleted-01{background:red;}

</style>
