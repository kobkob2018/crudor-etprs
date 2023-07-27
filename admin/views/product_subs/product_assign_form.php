<?php $this->include_view("products/main_header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/product_subs/list/") ?>">חזרה לרשימת תתי התיקיות</a>
    </div>
    <hr/>
    <?php $this->include_view("product_subs/header.php"); ?>
    <h3>שיוך מוצרים לתת התיקייה  <?= $this->data['item_info']['label'] ?></h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
            <input type="hidden" name="submit_assign" value="1" />

            <div class='form-group assign-checks'>
                <div class='form-group-st'>                
                    <label for='row[product_assign]'>בחר מוצרים לשיוך</label>
                </div>
                <div class='form-group-en'>
                    <input type="hidden" name="assign[]" value="-1" />
                    <?php foreach($info['options'] as $option): ?>
                        <div class="check-assign-group">
                            <input type="checkbox" name="assign[]" value="<?= $option['value'] ?>" <?= $option['checked'] ?> /> <?= $option['title'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group submit-form-group">
                <div class="form-group-st">
                    
                </div>
                <div class="form-group-en">
                    <input type="submit"  class="submit-btn"  value="שליחה" />
                </div>
            </div>
        </form>
    </div>
</div>