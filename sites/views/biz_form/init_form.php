<div class="biz-form-wrap leftbar-item">
    <div class="biz-form-generator">
        <div class="form-header big-title biz-form-bg">
            <h3 class='form-title'>
                <?= $info['biz_form']['title'] ?>
            </h3>
        </div>
        <?php if(isset($info['custom_cat_title'])): ?>
            <h3><?= $info['custom_cat_title'] ?></h3>
        <?php endif; ?>
        <form class="biz-form" action = "javascript://" method = "POST">
            <input type="hidden" name="submit_request" value="1" />
            <input class="cat-id-holder" type="hidden" name="biz[cat_id]" value="" />
            <input type="hidden" name="biz[site_id]" value="<?= $this->data['site']['id'] ?>" />
            <?php if(isset($this->data['page'])): ?>
                <input type="hidden" name="biz[page_id]" value="<?= $this->data['page']['id'] ?>" />
            <?php else: ?>
                <input type="hidden" name="biz[page_id]" value="-1" />
            <?php endif; ?>
            <?php if(isset($info['recapcha_data'])): ?>
                <input type="hidden" class="recapcha-key" value="<?= $info['recapcha_data']['public_key'] ?>" />
                <input type="hidden" class="recapcha-token" name="g_recaptcha_token" />
            <?php endif; ?>
            <input type="hidden" name="biz[form_id]" value="<?= $this->data['biz_form']['id'] ?>" />
            <input type="hidden" name="biz[referrer]" value="<?= current_url() ?>" />
            <input type="hidden" name="biz[site_ref]" value="<?= $_SERVER['HTTP_HOST'] ?>" />
            <?php if(isset($_REQUEST['test_form'])): ?>
                <div class="test-group form-group">
                    <input type="button" class="tester-button form-input" data-status="pending" value="<?= __tr("Regular check send") ?>" onclick="help_debug_forms(0)" />
                    <br/><br/>
                    <input type="button" class="tester-button form-input" data-status="pending" value="<?= __tr("Check submit without db registration") ?>" onclick="help_debug_forms(1)" />

                </div>
            <?php endif; ?>
            <?php if(isset($_GET['banner_id'])): ?>
                <input type="hidden" name="biz[banner_id]" value="<?= $_GET['banner_id'] ?>" />
            <?php endif; ?>
            <?php if(isset($_GET['cube_id'])): ?>
                <input type="hidden" name="biz[cube_id]" value="<?= $_GET['cube_id'] ?>" />
            <?php endif; ?>
            <?php if(isset($_GET['aff_id'])): ?>
                <input type="hidden" name="biz[aff_id]" value="<?= $_GET['aff_id'] ?>" />
            <?php endif; ?>
            <?php if(isset($info['campaign_type'])): ?>
                <input type="hidden" name="biz[campaign_type]" value="<?= $info['campaign_type'] ?>" />
            <?php endif; ?>
            <?php if(isset($info['campaign_name'])): ?>
                <input type="hidden" name="biz[campaign_name]" value="<?= $info['campaign_name'] ?>" />
            <?php endif; ?>
            <input type="hidden" name="biz[is_moblie]" value="<?= is_mobile()? '1': '0' ?>" />
            <div class="biz-form-placeholder biz-form-bg"  data-form_id='<?= $info['biz_form']['id'] ?>' data-cat_id='<?= $info['biz_form']['cat_id'] ?>' data-fetch_url='<?= inner_url("biz_form/fetch/") ?>'>
                <span class = "append-spot"></span>
                <?php if(!isset($info['input_remove']['name'])): ?>
                    <div class="form-group">
                        <input 
                        type="text" 
                        name="biz[full_name]" 
                        id="biz_name" 
                        class="form-input validate" 
                        placeholder="<?= __tr("Full name") ?>" 
                        required 
                        data-msg_required="<?= __tr("Please add Full name") ?>" 
                        data-msg_invalid="<?= __tr("Please add a valid Full name") ?>"
                        pattern="^(([A-Za-z_\-'\u0022\u0590-\u05FF ])\2?(?!\2))+$" 
                        minlength="2"
                        />
                    </div>
                <?php else: ?>
                    <input type="hidden" name="biz[full_name]" value="no-name" />
                <?php endif; ?>
                <?php if(!isset($info['input_remove']['phone'])): ?>
                    <div class="form-group">
                        <input type="text" name="biz[phone]" id="biz_phonne" 
                        required 
                        pattern="^(?=\d)(?=.{6,})(?!.*(\d)\1{4})((0[23489]{1}[5-9]{1})|(0[5]{1}[01234578]{1}[2-9]{1})|0[7]{1}[2-9]{1}[2-9]{1})?(\d{2}?\d{4})$" 
                        minlength="9" 
                        maxlength="10"  
                        class="form-input validate phoneNumber" 
                        placeholder="<?= __tr("Phone") ?>" 
                        required data-msg_required="<?= __tr("Please add phone number") ?>" data-msg_invalid="<?= __tr("Please add a valid phne number") ?>"
                        <?php if(isset($info['custom_phone'])): ?> 
                            value = "<?= $info['custom_phone'] ?>" 
                        <?php endif; ?>
                        />
                    </div>  
                    
                <?php else: ?>
                    <input type="hidden" name="biz[phone]" value="" />
                <?php endif; ?>
                <?php if((!isset($info['input_remove']['email'])) && $this->data['biz_form']['add_email']): ?>
                    <div class="form-group email-field-switch-on">
                        <?php /* email is not required anymore  */  ?>
                        <input type="text" name="biz[email]" id="biz_phone" class="form-input validate" placeholder="<?= __tr("Email") ?>" data-msg_required="<?= __tr("Please add email") ?>" data-msg_invalid="<?= __tr("Please add a valid email") ?>" />
                    </div>
                <?php else: ?>
                    <input type="hidden" name="biz[email]" value="no-mail" />
                <?php endif; ?>
                <?php if(!isset($info['input_remove']['city'])): ?> 
                    <div class="form-group">
                        <select name="biz[city_id]" id="biz_city_id" class="form-input validate" required data-msg_required="<?= __tr("Please select city") ?>">
                            <option value = "" class="select-note"><?= __tr("Select a city") ?></option>
                            <option value = "" class="select-note-2" disabled><?= __tr("You can get sevrice at the folowing cities") ?>:</option>
                            <?php foreach($this->data['city_select']['options'] as $option): ?>
                                <option value = "<?= $option['id'] ?>" class="city-option deep-<?= $option['deep'] ?> city_<?= $option['id'] ?>" data-parent="<?= $option['parent'] ?>"><?= $option['label'] ?></option>
                            <?php endforeach; ?>
                            <option value = "" class="select-note red"><?= __tr("If you don't find the city, we don't have a service provider there") ?></option>
                        </select>
                    </div>      
                <?php else: ?>
                    <input type="hidden" name="biz[city_id]" value="0" />
                <?php endif; ?>  
                
                <?php if(!isset($info['input_remove']['note'])): ?>
                    <div class="form-group">
                        <textarea 
                        name="biz[note]" 
                        id="biz_note" 
                        class="form-input validate" 
                        placeholder="<?= __tr("Notes") ?>" 
                        ></textarea>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="biz[note]" value="" />
                <?php endif; ?>
                <div class="loading-message hidden">
                    <div class="loader-icon">
                    
                    </div>              
                </div>
            </div>
        </form>
        <div class="submit-wrap pending-state form-group biz-form-bg">
            <input type="submit" class="submit-button form-input color-button" data-status="pending" value="<?= $info['biz_form']['btn_text'] ?>" />
                    
            <?php if(isset($info['recapcha_data'])): ?>
                <div class="recapcha-g-note">          
                    <small>This site is protected by reCAPTCHA and the Google 
                        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                    </small>
                </div>
            <?php endif; ?>
        </div>

        <?php if((!isset($info['input_remove']['email'])) && $this->data['biz_form']['add_email']): ?>
            <div class="hidden email-field-switch">
                <input class="email-field-switch-off" type="hidden" name="biz[email]" value="no-mail" />
            </div> 
        <?php endif; ?>
    </div>
</div>
<?php if(isset($info['recapcha_data'])): ?>
    <?php $this->register_script('js','google_recapcha',"https://www.google.com/recaptcha/api.js?render=".$info['recapcha_data']['public_key'],'foot'); ?> 
<?php endif; ?>
<?php $this->register_script('js','biz_form_js',styles_url('style/js/biz_form.js?cache='.get_config('cash_version')),'foot'); ?> 

