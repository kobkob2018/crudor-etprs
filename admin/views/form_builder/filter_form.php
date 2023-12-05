<div class = "form-wrap filter-form">
    
    <div class="focus-box">
        <h4>מסנן חיפוש</h4>
        <a href="<?= current_url(array("reset_session_filter"=>'1')) ?>">איפוס מסנן</a>
    </div>
    <form name="list_filter_form" class="list-filter-form form-validate" id="list_filter_form" method="post" action="">
        <input type="hidden" name="sendAction" value="setup_filter" /> 
        <?php foreach($info['filter_form']['fields'] as $field_key=>$build_field): ?>
            <?php if($build_field['type'] == 'hidden'): ?>
                <input type='hidden' name='filter[<?= $field_key ?>]" id="filter_<?= $field_key ?>' class='' value="<?= $this->get_form_input($field_key,$info['filter_form']['identifier']); ?>"  />
            <?php else: ?>
                <div class='form-group <?= isset($build_field['css_class'])? $build_field['css_class']: "" ?>'>
                        
                    <div class='form-group-st'>                
                        <label for='filter[<?= $field_key ?>]'><?= $build_field['label'] ?></label>
                    </div>
                    <div class='form-group-en'> 
                        <?php if($build_field['type'] == 'text' || $build_field['type'] == 'date'): ?>
                            <input type='text' name='filter[<?= $field_key ?>]' id='filter_<?= $field_key ?>' class='form-input <?= $build_field['validate_frontend'] ?>' data-msg-required='*' value="<?= $this->get_form_input($field_key,$info['filter_form']['identifier']); ?>"  />
                        <?php endif; ?>

              
                            
                        <?php if($build_field['type'] == 'select'): ?>
                            <select id='filter_<?= $field_key ?>' name='filter[<?= $field_key ?>]' class='form-select <?= $build_field['validate_frontend'] ?>' data-msg='יש לבחור <?= $build_field['label'] ?>'>
                                <?php if(isset($build_field['select_blank'])  && $build_field['select_blank']): ?>
                                    <option value="<?= $build_field['select_blank']['value'] ?>"><?= $build_field['select_blank']['label'] ?></option>
                                <?php endif; ?>
                                <?php foreach($this->get_select_options($field_key,$info['filter_form']['identifier']) as $option): ?>
                                    <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            
                        <?php endif; ?>
                            
                        
                        <?php if($build_field['type'] == 'pagination'): ?>
                            <select id='filter_<?= $field_key ?>' onchange="submit_filter_form()" name='filter[<?= $field_key ?>]' class='form-select <?= $build_field['validate_frontend'] ?>' data-msg='יש לבחור <?= $build_field['label'] ?>'>
                                <?php for($i=1 ;$i<=$info['filter_form']['pagination']['page_count'];$i++): ?>
                                    <option value="<?= ($i==$info['filter_form']['pagination']['page'])?"0":$i  ?>" <?= ($i==$info['filter_form']['pagination']['page'])?"selected":"" ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        <?php endif; ?>
                       

                        <?php if($build_field['type'] == 'build_method' && isset($build_field['build_method'])): ?>
                            <?php $build_method = $build_field['build_method']; ?>
                            <?php $this->$build_method($field_key, $build_field); ?>
                        <?php endif; ?>
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
    </form>
<script type="text/javascript">
    function submit_filter_form(){
        document.querySelector(".list-filter-form").submit();
    }
</script>
</div>