<h3>מחיקת קטגוריה <?= $this->data['item_info']['label'] ?></h3>
<div class="focus-box">
    הקטגוריה תמחק עם כל הקטגוריות המשוייכות אליה. במידה וקיימים לידים ובקשות להצעת מחיר המשוייכים לקטגוריה זו, עלייך לבחור לאיזו קטגוריה קיימת לשייך אותם:
</div>
<div class="red">
    * בניהול קטגוריות תמצאו עמודה עם סימון # - זהו מספר הקטגוריה
</div>
<div class="focus-box">
    <div id="block_form_wrap" class="form-gen page-form">
        <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
            <div class='form-group'>
                <label for='alt_cat_select'>מספר קטגוריה להעברת הלידים המשוייכים לקטגווריה שנמחקת, ולצאצאיה: </label>
                <input style="width:100px;" type='text' name='alt_cat_select' class='form-input' data-msg-required='*'/>
                
            </div>
            <div class="form-group submit-form-group">
                    <input type="submit"  class="submit-btn"  value="שליחה" />
            </div>         

        </form>
    </div>
</div>