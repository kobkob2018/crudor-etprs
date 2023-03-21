<div class="category-selector-wrap" data-fetch_url = "<?= inner_url("biz_categories/ajax_get_select_options/") ?>" data-active_parent="<?= $info['active_parent'] ?>">
    <?php if($info['cat_tree']): ?>
    <?php foreach($info['cat_tree'] as $parent_cat): ?>
        <?php if($parent_cat['id'] != $info['selected_box_parent']): ?>
            <span class="parent-a-template source-parent-a child-of-<?= $parent_cat['parent'] ?>" data-cat_id = "<?= $parent_cat['id'] ?>" data-parent_id = "<?= $parent_cat['parent'] ?>" > 
                <a class="val-placeholder" href = "javascript://"><?= $parent_cat['label'] ?></a> >> 
            </span> 
        <?php endif; ?>

    <?php endforeach; ?>
    <?php endif; ?>
    <span class = "append-spot"></span>
    <select  id='cat_select_<?= $info['selected_cat_id'] ?>' name='cat_select_helper_<?= $info['selected_cat_id'] ?>' data-parent = '<?= $info['selected_cat_id'] ?>' class='tree-select form-select'>
        <option value="">-- בחירה --</option>
        <?php foreach($info['select_options'] as $option): ?>
            <option value="<?= $option['id'] ?>" <?= $option['selected'] ?>><?= $option['label'] ?></option>
        <?php endforeach; ?>9    
    </select>
    &nbsp;&nbsp;<span class = "loading-msg hidden">טוען...</span>
    <input type="hidden" class = "value-holder" name = "<?= $info['field_name'] ?>" value = "<?= $info['selected_cat_id'] ?>" />
    <div class="apitoui" style="display:none;">
        <span class="parent-a-template"> <a class="val-placeholder" href = "javascript://"></a> >> </span>
        <select class='options-template-wrap'>
            <option value="">--- בחירה ---</option>
        </select>
    </div>
</div>

<?php $this->register_script('js','tree_selector',styles_url('style/js/tree_selector.js')); ?> 