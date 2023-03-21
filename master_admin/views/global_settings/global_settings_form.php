<h3>ניהול כללי</h3>
<div class="focus-box">
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>

<div class="focus-box">
    <h3>הוספת פרמטר</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="" <?= $this->data['form_builder']['enctype_str'] ?>>
            <input type="hidden" name="sendAction" value="add_paramSend" />
            <div class='form-group'>
                    
                <div class='form-group-st'>                
                    <label>מזהה הפרמטר</br>(אותיות אנגלית וקו תחתון בלבד)</label>
                </div>
                <div class='form-group-en'>                     
                    <input type='text' name='row[param_name]' class='form-input' value=""  />                   
                </div>  
            </div>
            <div class='form-group'>
                    
                <div class='form-group-st'>                
                    <label>שם הפרמטר(כותרת)</label>
                </div>
                <div class='form-group-en'>                     
                    <input type='text' name='row[label]' class='form-input' value=""  />                   
                </div>  
            </div>
            <div class='form-group'>
                
                <div class='form-group-st'>                
                    <label>סוג הפרמטר</label>
                </div>
                <div class='form-group-en'>                     
                    <select name='row[val_type]' class='form-input'>
                        <option value='varchar'>טקסט קצר</option>
                        <option value='longvarchar'>טקסט</option>
                        <option value='text'>תיבת טקסט</option>
                        <option value='int'>מספר</option>
                        <option value='tinyint'>בחירה כן\לא</option>
                    </select>
                </div>  
            </div>
            <div class="form-group submit-form-group">
                <div class="form-group-st">
                    <label id="submit_label"></label>
                </div>
                <div class="form-group-en">
                    <input type="submit"  class="submit-btn"  value="שליחה" />
                </div>
            </div>     
        </form>
    </div>
</div>
