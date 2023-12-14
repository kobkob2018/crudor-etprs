<div class="focus-box register_wrap">
    <h3>הרשמה למערכת</h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('user_register/pretty_form.php',array('state'=>'reg_form')); ?>
    </div>
</div>

<?php $this->register_script('style','register_style',styles_url('style/css/register_form.css'),'foot'); ?> 

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded",()=>{
        const register_form_validator = new formValidator(document.querySelector(".register_wrap .form-validate"));
    });
    
</script>