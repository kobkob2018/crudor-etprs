<div class = "form-wrap">
    <?php $this->include_view('messages/formMessages.php'); ?>
    <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="<?= inner_url('leads/set_filter/') ?>">

        <h3>סינון לידים</h3>

        

        <?php foreach($this->data['leads_filter_form_builder']['fields_collection'] as $field_key=>$build_field): ?>
            <div class='form-group <?= isset($build_field['css_class'])? $build_field['css_class']: "" ?>'>
                    
                <div class='form-group-col'>                
                    <label for='row[<?= $field_key ?>]'><?= $build_field['label'] ?></label>
                </div>
                <div class='form-group-col tip-holder'> 
                    <?php if($build_field['type'] == 'text' || $build_field['type'] == 'date'): ?>
                        
                    
                        <input type='text' <?= $build_field['front_attributes'] ?> name='row[<?= $field_key ?>]" id="row_<?= $field_key ?>' class='form-input <?= $build_field['validate_frontend'] ?>' data-msg-required='*' value="<?= $this->get_form_input($field_key,'leads_filter'); ?>"  />
                    
                        
                    <?php endif; ?>              
                        
                    <?php if($build_field['type'] == 'select'): ?>
                    
                        
                        <select <?= $build_field['front_attributes'] ?> id='row_<?= $field_key ?>' name='row[<?= $field_key ?>]' class='form-select <?= $build_field['validate_frontend'] ?>' data-msg='יש לבחור <?= $build_field['label'] ?>'>
                            <?php if(isset($build_field['select_blank'])  && $build_field['select_blank']): ?>
                                <option value="<?= $build_field['select_blank']['value'] ?>"><?= $build_field['select_blank']['label'] ?></option>
                            <?php endif; ?>
                            <?php foreach($this->get_select_options($field_key,'leads_filter') as $option): ?>
                                <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                    <?php endif; ?>
                        
                    <?php if($build_field['type'] == 'textbox'): ?>
                        
                            
                        <textarea <?= $build_field['front_attributes'] ?> name="row[<?= $field_key ?>]" id="row_<?= $field_key ?>_textarea" class="form-input form-textarea" data-msg-required="*"><?= $this->get_form_input($field_key,'leads_filter'); ?></textarea>
                    
                    <?php endif; ?>

                    <?php if($build_field['type'] == 'build_method' && isset($build_field['build_method'])): ?>
                        <?php $build_method = $build_field['build_method']; ?>
                        <?php $this->$build_method($field_key, $build_field); ?>
                    <?php endif; ?>
                </div>
            </div>  
        <?php endforeach; ?>
        <div class="form-group submit-form-group">
            <div class="form-group-col">
                <label id="submit_label"></label>
            </div>
            <div class="form-group-col">
                <input type="submit"  class="submit-btn"  value="שליחה" />
            </div>
        </div>
    </form>

</div>

<?php $this->register_script('js','validate_forms',styles_url('style/js/validate_forms.js'),'foot'); ?> 