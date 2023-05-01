<div class='buy_leads_wrap'>
    <div class='buy_leads_leadQry'>
    
        <h3>תשלום אוטומטי לחברת איי אל ביז קידום עסקים באינטרנט בע"מ</h3>
        <h4><?= $info['lounch_fee']['details'] ?></h4>
        <h5>סה"כ: <?= $info['lounch_fee']['price'] ?>ש"ח</h5>
    </div>
    
    
    <form action='payments/send_to_yaad/' method='post' name='sendto_yaad' id='sendto_yaad_form'>
        <input type='hidden' name='row_id' value='<?= $info['lounch_fee']['id'] ?>' />
        
        <div class='pay_desc form-group'>
            
            <?php if(!$info['user_cc_tokens']): ?>
                <input type='hidden' id='use_token_select' name='use_token' value='0' />
            <?php else: ?>
                <div class='pay_token_select form-group '>
                    <label for='use_token'>בחר כרטיס אשראי</label>
                    <select name='use_token' id='use_token_select' class="form-select use-token input_style">
                        <option value='0'>השתמש בכרטיס חדש</option>
                        <?php foreach($info['user_cc_tokens'] as $token): ?>
                            <option value='<?= $token['L4digit'] ?>'><?= $token['L4digit'] ?>**** **** **** </option>		
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <?php if($info['lounch_fee']['tash'] != '1'): ?>
                <div class='pay_payments_select form-group '>
                    <label for='Payments'>תשלומים</label>
                    <select name='Payments' id='Payments_select' class="form-select Payments input_style">
                        <?php for($i=1;$i<=$info['lounch_fee']['tash']; $i++): ?>
                            <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>	
            <?php else: ?>
                <input type='hidden' id='Payments' name='Payments' value='1' />			
            <?php endif; ?>
                <div class='pay_full_name form-group '>
                    <label for='full_name'>שם מלא</label>
                    <input type='text' id='full_name_input' name='full_name' value='<?= $this->user['full_name'] ?>' class='input_style text-input required' data-msg="נא להוסיף שם מלא"><br>
                </div>	
                <div class='buy_leads_biz_name form-group '>
                    <label for='biz_name'>שם העסק שיופיע בחשבונית</label>
                    <input type='text' id='biz_name_input' name='biz_name' value='<?= $this->user['biz_name'] ?>' class='input_style text-input required' data-msg="נא להוסיף את שם העסק"><br>
                </div>						
            <input type='submit' id='pay_submit' class='submit_style' value='עבור לטופס תשלום מאובטח' />
        </div>
    </form>
    <script type="text/javascript">
        $("#sendto_yaad_form").validate({
            submitHandler: function(form) {
            $("#pay_submit").attr("disabled", true).val("אנא המתן...");
            form.submit();
            }
        });

        $("#use_token_select").change(function(){
            if($(this).val()!= '0'){
                $("#pay_submit").val("בצע רכישה");
            }
            else{
                $("#pay_submit").val("עבור לטופס תשלום מאובטח");
            }
        });
        /*
        $('#sendto_yaad_form').submit(function () {
            if($(this).valid()) {
                if($("#use_token_select").val() != '0'){
                    window.parent.begin_ajax_call_view();
                }
            }
        });
        */
    </script>
</div>