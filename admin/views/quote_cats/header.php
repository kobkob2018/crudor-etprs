<div class="eject-box">
    <a class="back-link" href="<?= inner_url('quote_cats/list/') ?>">חזרה לרשימת התיקיות</a>
</div>

<h3>ניהול תיקיית הצעות מחיר <?= $this->data['cat_info']['label'] ?></h3>
<hr/>

<div class="yellowish form-group left-text">
    קוד להוספת הצעות מחיר בטוקן: <input  value = "{{% mod | quotes | print_cat | cat_id:<?= $this->data['cat_info']['id'] ?> %}}" />
    <br/>
    מצב פתוח: <input  value = "{{% mod | quotes | print_cat | cat_id:<?= $this->data['cat_info']['id'] ?> state:open %}}" />
</div>
<hr/>
<div class="item-edit-menu">
    <a href = "<?= inner_url('quote_cats/edit/') ?>?row_id=<?= $this->data['cat_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('quote_cats') ?>">עריכה</a>
     | 
     <a href = "<?= inner_url('quotes/list/') ?>?cat_id=<?= $this->data['cat_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('quotes') ?>">הצעות מחיר בתיקייה</a>
</div>
<hr/>
