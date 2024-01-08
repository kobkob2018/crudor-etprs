<div class='form-group child-element' data-cat_id=''>

    <select name = 'cat_tree[]' class='form-input validate to-bind' required data-msg_required='<?= __tr("Please select a service") ?>'>
        
            <option value="">
                <?php if($info['cat_id'] == $this->data['form_info']['cat_id']): ?>
                    <?= __tr("Please select a service") ?>
                <?php else: ?>
                    <?= __tr("Select") ?>
                <?php endif; ?>
            </option>
            <?php foreach($info['children'] as $biz_cat): ?>
                <option value="<?= $biz_cat['id'] ?>"><?= $biz_cat['label'] ?></option>
            <?php endforeach; ?>
    </select>    
</div>