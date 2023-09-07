<ul class="form-checklist" id="row_<?= $info['field_key'] ?>_checklist">     
    <?php foreach($info['options'] as $option): ?>
        <li class="assign-checkbox-parent-wrap">
            <input type="checkbox" class="input-checkbox" name='row[<?= $info['field_key'] ?>][<?= $option['value'] ?>]' value="1" <?= $option['checked'] ?>/> <?= $option['title'] ?>
        </li>
    <?php endforeach; ?>
</ul>  