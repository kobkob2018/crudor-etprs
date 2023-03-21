<h3>קוביות ספקי שירות</h3>

<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('supplier_cubes/add/') ?>">הוספת קוביית נותן שירות</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון ספק שירות</div>
        <div class="col">סטטוס</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['supplier_cubes'] as $supplier_cube): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('supplier_cubes/edit/') ?>?row_id=<?= $supplier_cube['id'] ?>" title="ערוך קובייה"><?= $supplier_cube['label'] ?></a>
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

