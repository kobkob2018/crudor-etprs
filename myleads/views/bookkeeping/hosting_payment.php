<style type="text/css">
    .bookkeeping-wrap{
        display: flex;
        flex-wrap: wrap;
    }
    .book-box{
        max-width: 500px;
    }
</style>
<script type="text/javascript">
    function toggle_token_fields(select){
        const token_fields = document.querySelector("."+select.dataset.token_fields);
        const submit_btn = document.querySelector("."+select.dataset.token_submit);
        if(select.value == '0'){
            token_fields.classList.add("hidden");
            submit_btn.value = submit_btn.dataset.no_token_text;
        }
        else{
            token_fields.classList.remove("hidden");
            submit_btn.value = submit_btn.dataset.use_token_text;
        }
    }
</script>
<div class="bookkeeping-wrap">
    <?php if($info['have_hosting']): ?>
        
        <div class="hosting-details focus-box book-box">
            <b>
                תאריך תוקף אחסון: 
            </b>
            <?= hebdt($info['hostEndDate'],"d-m-Y") ?>
            <br/>
            <?php if($info['allow_host_payment']): ?>
                

            
                <h4>בעוד <?= $info['hosting_days'] ?> ימים יפוג תוקף האתר.</h4>
                <p>
                    כדי שהמערכת האוטומטית לא תוריד את אתרך מהאינטרנט בתאריך הנ"ל יש לבצע תשלום
                </p>
                <p>
                    <b>
                        פירוט תשלום :
                    </b>
                    <b>עלות אחסון חודשית</b> <?= $info['hostPriceMon'] ?> ₪ + מע"מ
                    <b>סה"כ תשלום</b> <?= $info['hostPriceYear'] ?> ₪ כולל מע"מ
                </p>
                    

                <h4>
                    ניתן לחדש את אחסון האתר באחת מהדרכים הבאות :
                </h4>
                <ol>
                    <li>
                        באמצעות כרטיס אשראי, עד 12 תשלומים ללא רבית והצמדה, לחץ כאן לטופס סליקה * 
                        <div class="booking-pay-form">
                            <h4>לתשלום בכרטיס אשראי</h4>
                            <form action='bookkeeping/send_to_yaad_hosting/' method='post' name='sendto_Bookkeeping' id='sendto_yaad_hosting_form'>	
                                <div class='buy_hosting_desc form-group'>

                                    <?php if(!$info['user_cc_tokens']): ?>
                                        <input type='hidden' id='use_token_select' name='use_token' value='0' />
                                    <?php else: ?>
                                        <div class='buy_leads_token_select form-group '>
                                            <label for='use_token'>בחר כרטיס אשראי</label>
                                            <select onchange="toggle_token_fields(this)" data-token_fields="token-fields-hosting" data-token_submit="hosting-form-submit" name='use_token' id='use_token_select' class="form-select use-token input_style">
                                                <option value='0'>השתמש בכרטיס חדש</option>
                                                <?php foreach($info['user_cc_tokens'] as $token): ?>
                                                    <option value='<?= $token['L4digit'] ?>'><?= $token['L4digit'] ?>**** **** **** </option>		
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    
                                    <?php endif; ?>
                                        <div class="token-fields-hosting hidden">

                                        
                                            <div class="payments-select-wrap form-group" >
                                                בחר מספר תשלומים: <br/>

                                                <select id='payments_input' name='Tash' class='input_style' data-msg="אנא בחר מספר תשלומים">
                                                    <?php for($i=1;$i<13;$i++): ?>
                                                        <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <br>
                                            </div>
                                            <div class='buy_leads_full_name form-group '>
                                                <label for='full_name'>שם מלא</label>
                                                <input type='text' id='full_name_input' name='full_name' value='<?= $this->user['full_name'] ?>' class='input_style text-input required' data-msg="נא להוסיף שם מלא"><br>
                                            </div>	
                                            <div class='buy_leads_biz_name form-group '>
                                                <label for='biz_name'>שם העסק שיופיע בחשבונית</label>
                                                <input type='text' id='biz_name_input' name='biz_name' value='<?= $this->user['biz_name'] ?>' class='input_style text-input required' data-msg="נא להוסיף את שם העסק"><br>
                                            </div>		
                                        </div>				
                                        <input type='submit' class='submit_style hosting-form-submit' value='עבור לטופס תשלום מאובטח' data-no_token_text = 'עבור לטופס תשלום מאובטח' data-use_token_text = 'בצע תשלום'  />
                                </div>
                            </form>
                        </div>   
                    </li>
                    <li>
                        הפקדה לחשבון בנק ע"ש איי. אל. ביז קידום עסקים באינטרנט בע"מ, בנק הפועלים, סניף 160 , מספר חשבון 71732
                    </li>
                </ol>
                <p>
                    * לאחר התשלום ישלח לאימייל שיצויין בטופס, חשבונית מס קבלה מקורית עם חתימה דגיטלית
                </p>
                <p>
                    אם שולם באמצעות הפקדת כסף לחשבון הבנק, נא להודיע לשירות לקוחות
                </p>
                <p>
                    *** עלות החזרת אתר שהורד על ידי המערכת האוטומטית מהאינטרנט – 250 ₪ + מע"מ
                </p>
                
            
            <?php else: ?>
                
                    <b>
                        מחיר אחסון חודשי: 
                    </b>
                    <?= $info['hostPriceMon'] ?> ש"ח (כולל מע"מ)
                    <br/>
                    <b>
                        מחיר אחסון שנתי: 
                    </b>
                    <?= $info['hostPriceYear'] ?> ש"ח (כולל מע"מ)
                        
            
            <?php endif; ?>
        </div>
    <?php endif; ?>


       

       
           
    <?php if($info['allow_domain_payment']): ?>
        <div class="hosting-details focus-box book-box sub-focus">

                
                <h4>בעוד <?= $info['hosting_days'] ?> ימים יפוג תוקף האתר.</h4>
                <p>
                    כדי שהמערכת האוטומטית לא תוריד את אתרך מהאינטרנט בתאריך הנ"ל יש לבצע תשלום
                </p>
                <p>
                    <b>
                        פירוט תשלום :
                    </b>
                    <b>עלות אחסון חודשית</b> <?= $info['hostPriceMon'] ?> ₪ + מע"מ
                    <b>סה"כ תשלום</b> <?= $info['hostPriceYear'] ?> ₪ כולל מע"מ
                </p>
                    

                <h4>
                    ניתן לחדש את אחסון האתר באחת מהדרכים הבאות :
                </h4>
                <ol>
                    <li>
                        באמצעות כרטיס אשראי, עד 12 תשלומים ללא רבית והצמדה, לחץ כאן לטופס סליקה *    
                    </li>
                    <li>
                        הפקדה לחשבון בנק ע"ש איי. אל. ביז קידום עסקים באינטרנט בע"מ, בנק הפועלים, סניף 160 , מספר חשבון 71732
                    </li>
                </ol>
                <p>
                    * לאחר התשלום ישלח לאימייל שיצויין בטופס, חשבונית מס קבלה מקורית עם חתימה דגיטלית
                </p>
                <p>
                    אם שולם באמצעות הפקדת כסף לחשבון הבנק, נא להודיע לשירות לקוחות
                </p>
                <p>
                    *** עלות החזרת אתר שהורד על ידי המערכת האוטומטית מהאינטרנט – 250 ₪ + מע"מ
                </p>
                
                
        </div>
    <?php endif; ?>
    

</div>