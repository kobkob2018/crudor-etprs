<div class = "form-wrap">
    <?php $this->include_view('messages/formMessages.php'); ?>
    <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="" <?= $this->data['form_builder']['enctype_str'] ?>>
        <input type="hidden" name="sendAction" value="<?= $this->data['form_builder']['sendAction'] ?>" />
    
        <?php if(isset($this->data['form_builder']['db_row_id'])): ?>
            <input type="hidden" name="db_row_id" value="<?= $this->data['form_builder']['db_row_id'] ?>" />
        <?php endif; ?>

            
        <?php foreach($this->data['form_builder']['fields_collection'] as $field_key=>$build_field): ?>
            <div class='form-group <?= isset($build_field['css_class'])? $build_field['css_class']: "" ?>'>
                    
                <div class='form-group-st'>                
                    <label for='row[<?= $field_key ?>]'><?= $build_field['label'] ?></label>
                </div>
                <div class='form-group-en'> 
                    <?php if($build_field['type'] == 'text' || $build_field['type'] == 'date'): ?>
                        
                    
                        <input type='text' name='row[<?= $field_key ?>]" id="row_<?= $field_key ?>' class='form-input <?= $build_field['validate_frontend'] ?>' data-msg-required='*' value="<?= $this->get_form_input($field_key); ?>"  />
                    
                        
                    <?php endif; ?>

                    <?php if($build_field['type'] == 'password'): ?>
                        
                    
                        <input type='password' name='row[<?= $field_key ?>]" id="row_<?= $field_key ?>' class='form-input <?= $build_field['validate_frontend'] ?>' data-msg-required='*' value=""  />
                    
                        
                    <?php endif; ?>                
                        
                    <?php if($build_field['type'] == 'select'): ?>
                    
                        
                        <select  id='row_<?= $field_key ?>' name='row[<?= $field_key ?>]' class='form-select <?= $build_field['validate_frontend'] ?>' data-msg='יש לבחור <?= $build_field['label'] ?>'>
                            <?php if(isset($build_field['select_blank'])  && $build_field['select_blank']): ?>
                                <option value="<?= $build_field['select_blank']['value'] ?>"><?= $build_field['select_blank']['label'] ?></option>
                            <?php endif; ?>
                            <?php foreach($this->get_select_options($field_key) as $option): ?>
                                <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                    <?php endif; ?>
                        
                    <?php if($build_field['type'] == 'textbox'): ?>
                        
                            
                        <textarea name="row[<?= $field_key ?>]" id="row_<?= $field_key ?>_textarea" class="form-input form-textarea" data-msg-required="*"><?= $this->get_form_input($field_key); ?></textarea>
                        <?php if(isset($build_field['reachtext']) && $build_field['reachtext']): ?>
                            <?php $this->register_script('js','tinymce',global_url('vendor/tinymce/tinymce/tinymce.min.js')); ?>
                            <?php $this->register_script('js','tinymce_helper',styles_url('style/js/tinymce_helper.js')); ?>

                            <script type="text/javascript">
                                init_tinymce(
                                    'textarea#row_<?= $field_key ?>_textarea', 
                                    '<?= inner_url('media/upload/') ?>',
                                    '<?= inner_url('media/librarypopup/') ?>'
                                );
                            </script>
                        <?php endif; ?>
                    
                    <?php endif; ?>

                    <?php if($build_field['type'] == 'file'): ?>
                        
                        <?php if($build_field['file_type'] == 'img'): ?>
                            <input type="file" name="row[<?= $field_key ?>]" id="row_<?= $field_key ?>" accept="image/png, image/gif, image/jpeg" class="form-input" value="" />
                        <?php elseif($build_field['file_type'] == 'video'): ?>
                            <input type="file" name="row[<?= $field_key ?>]" id="row_<?= $field_key ?>" accept="video/mp4,video/x-m4v,video/*" class="form-input" value="" />
                        <?php else: ?>
                            <input type="file" name="row[<?= $field_key ?>]" id="row_<?= $field_key ?>" class="form-input" value="" />
                        <?php endif; ?>
                        <?php if($file_url = $this->get_form_file_url($field_key)): ?>
                        <div>
                            
                            <a href="<?= $file_url ?>" target="_BLANK">
                                <?php if($build_field['file_type'] == 'img'): ?>
                                    <img src='<?= $file_url ?>?cache=<?= rand() ?>'  style="max-width:200px;"/>
                                <?php elseif($build_field['file_type'] == 'video'): ?>
                                    <video width="320" height="240" controls>
                                        <source src="<?= $file_url ?>?cache=<?= rand() ?>" type="<?= $view->get_video_embed_type($this->get_form_input($field_key));  ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php else: ?>
                                    צפה בקובץ
                                <?php endif; ?>
                            </a>
                            <br/>
                            <a href="<?= current_url(array('remove_file'=>$field_key)) ?>">הסר <?= $build_field['label'] ?></a>
                        </div>
                        <?php endif; ?>
                        
                    <?php endif; ?>

                    <?php if($build_field['type'] == 'build_method' && isset($build_field['build_method'])): ?>
                        <?php $build_method = $build_field['build_method']; ?>
                        <?php $this->$build_method($field_key, $build_field); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if($build_field['type'] == 'password'): ?>

                
                <div class='form-group <?= isset($build_field['css_class'])? $build_field['css_class']: "" ?>'>
                    <div class="form-group-st">
                        <label for='row[<?= $field_key ?>_confirm]'>אימות <?= $build_field['label'] ?></label>
                    </div>
                    <div class='form-group-en'>
                        <input type='password' name='row[<?= $field_key ?>_confirm]" id="row_<?= $field_key ?>_confirm' class='form-input' data-msg-required='*' value=""  />
                    </div>	
                </div>
            <?php endif; ?>   
        <?php endforeach; ?>
        <div class="form-group submit-form-group">
            <div class="form-group-st">
                <label id="submit_label"></label>
            </div>
            <div class="form-group-en">
                <input type="submit"  class="submit-btn"  value="שליחה" />
            </div>
        </div>
        <?php if(isset($this->data['item_delete_url'])): ?>
            <div class="delete-box">
                <hr/>
                <a href="<?= $this->delete_url($this->data['item_info']) ?>"  class="delete-link" >מחיקה</a>
            </div>
        <?php endif; ?>
    </form>

</div>