<?php $this->include_view("quotes/cat_header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/quotes/list/") ?>?cat_id=<?= $this->data['cat_info']['id'] ?>">חזרה לרשימה</a>
    </div>
    <hr/>
    <?php $this->include_view("quotes/quote_header.php"); ?>
    <h3>שיוך הצעת מחיר <?= $this->data['item_info']['label'] ?> לתיקיות</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
            <input type="hidden" name="submit_assign" value="1" />

            <div class='form-group assign-checks'>
                <div class='form-group-st'>                
                    <label for='row[cat_assign]'>בחר תיקיות לשיוך</label>
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