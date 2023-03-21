<?php foreach($info['fields'] as $field_key=>$groups_field): ?>
    
    <div class="hour-groups-holder" data-field_key = "<?= $field_key ?>" data-group_index = '<?= count($groups_field['hour_groups']) ?>'>
       
        
        <?php foreach($groups_field['hour_groups']  as $group_key=>$hour_group): ?>
            <div class="hour-groups-template">
                <a href="javascript://" class="delete-item-x" onClick = "remove_hour_group(this)">X</a>
                <div class='group-times'>
                    <div class='time-from'>
                        <div class="time-label">
                            משעה: 
                        </div>
                        <div class="time-selector">
                            <select name='row[<?= $field_key ?>][<?= $group_key ?>][hf]' class='form-select time-group-input'>
                                <?php foreach($info['options']['hours'] as $option): ?>
                                    <?php $selected = ""; ?>
                                    <?php if($hour_group['hf'] == $option['value']): ?>
                                        <?php $selected = "selected"; ?>
                                    <?php endif; ?>
                                    <option value="<?= $option['value'] ?>" <?= $selected ?>><?= $option['value'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            : 
                            <select name='row[<?= $field_key ?>][<?=$group_key ?>][mf]' class='form-select time-group-input'>
                                <?php foreach($info['options']['minutes'] as $option): ?>
                                    <?php $selected = ""; ?>
                                    <?php if($hour_group['mf'] == $option['value']): ?>
                                        <?php $selected = "selected"; ?>
                                    <?php endif; ?>
                                    <option value="<?= $option['value'] ?>" <?= $selected ?>><?= $option['value'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class='time-to'>
                        <div class="time-label">
                            עד שעה: 
                        </div>
                        <div class="time-selector">
                            <select name='row[<?= $field_key ?>][<?=$group_key ?>][ht]' class='form-select time-group-input'>
                                <?php foreach($info['options']['hours'] as $option): ?>
                                    <?php $selected = ""; ?>
                                    <?php if($hour_group['ht'] == $option['value']): ?>
                                        <?php $selected = "selected"; ?>
                                    <?php endif; ?>
                                    <option value="<?= $option['value'] ?>" <?= $selected ?>><?= $option['value'] ?></option>
                                <?php endforeach; ?>
                            </select> : 
                            <select name='row[<?= $field_key ?>][<?=$group_key ?>][mt]' class='form-select time-group-input'>
                                <?php foreach($info['options']['minutes'] as $option): ?>
                                    <?php $selected = ""; ?>
                                    <?php if($hour_group['mt'] == $option['value']): ?>
                                        <?php $selected = "selected"; ?>
                                    <?php endif; ?>
                                    <option value="<?= $option['value'] ?>" <?= $selected ?>><?= $option['value'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="group-days">
                    <div class="days-label">
                        ימי פעילות: 
                    </div>
                    <div class='days-checks'>
                        <?php foreach($info['options']['days'] as $day): ?>
                            <?php $checked = ""; ?>
                                    <?php if(isset($hour_group['d'][$day['value']])): ?>
                                        <?php $checked = "checked"; ?>
                                    <?php endif; ?>
                            <div class="day-check-wrap">

                                <input class="time-group-input" <?= $checked ?> type="checkbox" name='row[<?= $field_key ?>][<?=$group_key ?>][d][<?= $day['value'] ?>]' /> 
                                <?= $day['label'] ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
            </div>            
        <?php endforeach; ?>

        <a href="javascript://" class="button-focus hour-add-button" onClick = "add_hour_group(this)">הוסף שעות</a>
    </div>


    <div class="<?= $field_key ?>-apitoui" style="display:none;">
        <div class="hour-groups-template">
            <a href="javascript://" class="delete-item-x" onClick = "remove_hour_group(this)">X</a>
            <div class='group-times'>
                <div class='time-from'>
                    <div class="time-label">
                        משעה: 
                    </div>
                    <div class="time-selector">
                        <select name='row[{{field_key}}][{{group_id}}][hf]' class='form-select time-group-input'>
                            <?php foreach($info['options']['hours'] as $option): ?>
                                <option value="<?= $option['value'] ?>"><?= $option['value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                         : 
                        <select name='row[{{field_key}}][{{group_id}}][mf]' class='form-select time-group-input'>
                            <?php foreach($info['options']['minutes'] as $option): ?>
                                <option value="<?= $option['value'] ?>"><?= $option['value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class='time-to'>
                    <div class="time-label">
                        עד שעה: 
                    </div>
                    <div class="time-selector">
                        <select name='row[{{field_key}}][{{group_id}}][ht]' class='form-select time-group-input'>
                            <?php foreach($info['options']['hours'] as $option): ?>
                                <option value="<?= $option['value'] ?>"><?= $option['value'] ?></option>
                            <?php endforeach; ?>
                        </select> : 
                        <select name='row[{{field_key}}][{{group_id}}][mt]' class='form-select time-group-input'>
                            <?php foreach($info['options']['minutes'] as $option): ?>
                                <option value="<?= $option['value'] ?>"><?= $option['value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="group-days">
                <div class="days-label">
                    ימי פעילות: 
                </div>
                <div class='days-checks'>
                    <?php foreach($info['options']['days'] as $day): ?>
                        <div class="day-check-wrap">
                            <input class="time-group-input" type="checkbox" name='row[{{field_key}}][{{group_id}}][d][<?= $day['value'] ?>]' <?= $day['default_checked'] ?> /> 
                            <?= $day['label'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>    
<?php endforeach; ?>

<?php $this->register_script('js','time_groups',styles_url('style/js/time_groups.js')); ?>



