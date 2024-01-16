<h3>קוביות ספקי שירות</h3>

<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('supplier_cubes/add/') ?>">הוספת קוביית נותן שירות</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">
            עדכון ספק שירות
            <hr/> 
            <div class="col-table-30">
                <div class="col-30">
                    צפיות
                </div>
                <div class="col-30">
                    קליקים
                </div>
                <div class="col-30">
                    המרות
                </div>
            </div>
        </div>
        <div class="col">סטטוס</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['supplier_cubes'] as $supplier_cube): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('supplier_cubes/edit/') ?>?row_id=<?= $supplier_cube['id'] ?>" title="ערוך קובייה"><?= $supplier_cube['label'] ?></a>
                <hr/>
                <div class="col-table-30">
                    <div class="col-30">
                        <?= $supplier_cube['views'] ?>
                    </div>
                    <div class="col-30">
                        <?= $supplier_cube['clicks'] ?>
                        <?php if($supplier_cube['views']): ?>
                        <br/>
                            <?= round(intval($supplier_cube['clicks'])/intval($supplier_cube['views'])*100) ?>%
                        <?php endif; ?>
                    </div>
                    <div class="col-30">
                        <?= $supplier_cube['convertions'] ?>
                        <?php if($supplier_cube['clicks']): ?>
                            <br/>
                            <?= round(intval($supplier_cube['convertions'])/intval($supplier_cube['clicks'])*100) ?>%
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <?= $this->get_label_value('status',$supplier_cube) ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('supplier_cubes/delete/') ?>?row_id=<?= $supplier_cube['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

