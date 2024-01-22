document.addEventListener("DOMContentLoaded",()=>{
    initBizForm();
});
let form_debug_helper;
initBizForm = ()=>{
    document.querySelectorAll('.biz-form-generator').forEach(
        wrapElement=>{
            bizForm = new BizForm(wrapElement);
            form_debug_helper = bizForm;
        }
    );
} 

class BizForm{

    constructor(wrapElement) {
        this.wrapElement = wrapElement;
        this.placeholder = this.wrapElement.querySelector(".biz-form-placeholder");
        this.formElement = this.wrapElement.querySelector("form.biz-form");
        this.catHolder = this.formElement.querySelector(".cat-id-holder");
        this.formValidator = new formValidator(this.formElement);
        //return;
        this.fetchUrl = this.placeholder.dataset.fetch_url;
        this.form_id = this.placeholder.dataset.form_id;
        this.selected_cat = this.placeholder.dataset.cat_id;
        this.appendSpot = this.placeholder.querySelector(".append-spot");
        this.loadingMsg = this.placeholder.querySelector(".loading-message");
        this.submitButton = wrapElement.querySelector(".submit-button");
        this.submitWrap = wrapElement.querySelector(".submit-wrap");
        this.citySelect = this.formElement.querySelector("#biz_city_id");
        this.emailSwitch = this.wrapElement.querySelector(".email-field-switch");
        this.submitUrl = "";
        this.selectEventListenerBinded = this.selectEventListener.bind(this);
        this.submitEventListenerBinded = this.submitEventListener.bind(this);
        
        this.submitButton.addEventListener("click", this.submitEventListenerBinded, true);
        this.max_stock = 0;
        this.initFetch();
        
    }

    initFetch(){
        const cat_id = this.placeholder.dataset.cat_id;
        this.fetchForCat(cat_id);


    }
    fetchForCat(cat_id){
        this.enterLoadingState();
        fetch(this.fetchUrl+"?cat_id="+cat_id+"&form_id="+this.form_id).then((res) => res.json()).then(info => {
            
            if(info.success){
                if(info.allowed_cities){
                    this.reset_city_select(info.allowed_cities);
                }
                this.switchEmailField(info.add_email_to_form);

                this.appendChildren(info.html,cat_id);
                this.bindCatSelectEvents(cat_id);
                this.outLoadingState(info);
            }
        }).catch(function(err) {
            
            console.log(err);
            alert("Something went wrong. please reload the page");
            this.hideLoading();
            //alert("Something went wrong. please reload the page");
        });
    }
    enterLoadingState(){
        this.showLoading();
        this.enterPendingState();

    }
    outLoadingState(info){
        this.hideLoading();
        if(info.state == "ready"){
            this.submitUrl = info.submit_url;
            this.enterReadyState();
        }
    }
    enterPendingState(){
        this.submitButton.dataset.status = 'pending';
        this.submitWrap.classList.remove("ready-state");
        this.submitWrap.classList.add("pending-state");
    }
    enterReadyState(){
        this.submitButton.dataset.status = 'ready';
        this.submitWrap.classList.remove("pending-state");
        this.submitWrap.classList.add("ready-state");
    }
    showLoading(){
        this.loadingMsg.classList.remove("hidden");
    }
    hideLoading(){
        this.loadingMsg.classList.add("hidden");
    }
    catChildClassName(cat_id){
        return 'child-of-'+cat_id;
    }
    switchEmailField(addEmailField){
        
        if(typeof(addEmailField) == "undefined"){
            return;
        }
        if(!this.emailSwitch){
            return;
        }
        if(!addEmailField){
            const emailSwitchOff = this.emailSwitch.querySelector(".email-field-switch-off");
            if(!emailSwitchOff){
                return;
            }
            const emailSwitchOn = this.placeholder.querySelector(".email-field-switch-on");
            this.placeholder.insertBefore(emailSwitchOff,emailSwitchOn);
            this.emailSwitch.append(emailSwitchOn);
        }
        else{
            const emailSwitchOn = this.emailSwitch.querySelector(".email-field-switch-on");
            if(!emailSwitchOn){
                return;
            }
            const emailSwitchOff = this.placeholder.querySelector(".email-field-switch-off");
            this.placeholder.insertBefore(emailSwitchOn,emailSwitchOff);
            this.emailSwitch.append(emailSwitchOff);
        }
    }
    
    reset_city_select(allowed_cities){
        if(!this.citySelect){
            return;
        }
        this.citySelect.querySelectorAll(".city-option").forEach(option=>{
            const optionVal = parseInt(option.value);
            
            if(!allowed_cities.includes(optionVal)){
                option.classList.add("disabled");
                option.classList.add("hidden");
                option.selected = false;
                option.disabled = true;
            }
            else{
                const parent_option = this.citySelect.querySelector(".hidden.city_"+option.dataset.parent);
                if(parent_option){
                    parent_option.classList.remove("hidden");
                }
                option.classList.remove("disabled");
                option.classList.remove("hidden");
                option.disabled = false;
            }
        });
        
    }

    removeChildrenOf(childEl){
        this.max_stock++;
        if(this.max_stock > 10){
            alert("max_stock");
            return;
        }
        if(childEl.getAttribute("data-cat_id") === "undefined"){
            return;
        }
        if(childEl.dataset.cat_id == ""){
            return;
        }
        const childEl_cat_id = childEl.dataset.cat_id;
        const className = this.catChildClassName(childEl_cat_id);
        
        this.placeholder.querySelectorAll("."+className).forEach(child => {
            // if(child.classList.contains("binded-cat-select")){
                this.removeChildrenOf(child);
            // }
            child.remove();
        });
    }
    appendChildren(html,cat_id){
        const new_elements = document.createElement('div');
        new_elements.innerHTML = html;
        let have_new_elements = false;
        new_elements.querySelectorAll(".child-element").forEach(childEl => {
            have_new_elements = true;
            const className = this.catChildClassName(cat_id);
            childEl.classList.add(className);
            this.placeholder.insertBefore(childEl, this.appendSpot);
        }); 
        this.formValidator.updateInputs();
        return have_new_elements;
    }
    bindCatSelectEvents(){
        this.placeholder.querySelectorAll("select.to-bind").forEach(select => {
            select.classList.remove("to-bind");
            select.classList.add("binded-cat-select");
            select.addEventListener("change", this.selectEventListenerBinded, true);
        });
    }

    selectEventListener(event){
        const select = event.target;
        const cat_id = select.value;
        const childEl = select.closest('.child-element');
        this.max_stock = 0;
        this.removeChildrenOf(childEl);
        childEl.dataset.cat_id = cat_id;
        this.selected_cat = cat_id;
        if(cat_id != ''){
            this.fetchForCat(cat_id);
        }
    }
    submitEventListener(event){
        if(this.submitButton.dataset.status == "ready"){
            const selected_cat = this.selected_cat;
            if(this.selected_cat == ""){    
                return;
            }
            if(this.formValidator.validate()){
                const recapcha_key_input = this.formElement.querySelector(".recapcha-key");
                
                if(!recapcha_key_input){
                    return this.submitForm();
                }
                const recapcha_token_input = this.formElement.querySelector(".recapcha-token");
                const recapcha_key = recapcha_key_input.value;
                const thisClass = this;
                grecaptcha.ready(function() {
                    grecaptcha.execute(recapcha_key, {action: 'submit'}).then(function(token) {
                        // Add your logic to submit to your backend server here.
                        recapcha_token_input.value = token;
                        return thisClass.submitForm();
                    });
                });
            }
            
        }
        else{
            this.formValidator.validate();
            
        }
    }
    submitForm(){
        this.showLoading();
        // const formData = this.formElement;
        this.catHolder.value = this.selected_cat;
        const formData = new FormData(this.formElement);
        

        fetch(this.submitUrl,{
            method: 'POST',
            body: formData,
        }).then((res) => res.json()).then(info => {
            if(info.success){

                if(info.have_redirect){
                    console.log("redirecting to"+ info.redirect_to);
                    setTimeout(function(){
                        
                        window.location.href =  info.redirect_to;
                    },5000);
                }
                const successEl = document.createElement('div');
                successEl.innerHTML = info.html;
                this.wrapElement.insertBefore(successEl,this.formElement);
                this.formElement.remove();
                this.submitButton.remove();
                if(window.send_gtag_convertion){
                    send_gtag_convertion();
                }

            }
            else{
                const msg = info.error.msg;
                alert(msg);
                this.hideLoading();
            }
        }).catch(function(err) {
            
            console.log(err);
            alert("Something went wrong. please reload the page");
            this.hideLoading();
        });
        console.log(formData);
    }
}

class formValidator{
    constructor(formElement) {
        this.formElement = formElement;
        
        this.formElement.querySelectorAll(".phoneNumber").forEach(phoneInput=>{
            phoneInput.addEventListener("keypress", function preventKeyPress(evt){
                if (evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
        });
        
        this.inputKeypressListenerBinded = this.inputKeypressListener.bind(this);
        this.blurListenerBinded = this.blurListener.bind(this);
        this.selectChangeListenerBinded = this.selectChangeListener.bind(this);
        this.updateInputs();

    }
    updateInputs(){
        this.formElement.querySelectorAll("input[type=text].validate").forEach(input=> {
            this.bindInputValidate(input);
        });

        this.formElement.querySelectorAll("select.validate").forEach(input=> {
            this.bindselectValidate(input);
        });        

        this.formElement.querySelectorAll(".validate").forEach(input=> {
            this.bindBlurListener(input);
        });
    }

    bindInputValidate(input){
        if(input.classList.contains('validate-binded')){
            return;
        }
        input.classList.add('validate-binded');
      
        input.addEventListener("keypress",this.inputKeypressListenerBinded, true);
    }
    bindselectValidate(select){
        if(select.classList.contains('validate-binded')){
            return;
        }
        select.classList.add('validate-binded');
      
        select.addEventListener("change",this.selectChangeListenerBinded, true);        
    }
    bindBlurListener(input){
        if(input.classList.contains('blur-binded')){
            return;
        }
        input.classList.add('blur-binded');
      
        input.addEventListener("blur",this.blurListenerBinded, true);
    }
    validate(event){
        let isValid = true;
        isValid = this.validateErrors(isValid);
        return isValid;
    }

    validateErrors(isValid){
        
        const validateFileds = this.formElement.querySelectorAll(".validate");
        validateFileds.forEach(field => {
            //alert(field.name);
            if(!this.validateField(field)){
                
                isValid = false;
            }
        });
        return isValid;
    }
    showTooltip(field, message){
        
        const formGroup = field.closest(".form-group");
        let tooltip = formGroup.querySelector(".tooltip");
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            formGroup.insertBefore(tooltip, field);
        }
        tooltip.innerHTML = message;
        tooltip.classList.remove("hidden");
    }
    hideTooltip(field){
        const formGroup = field.closest(".form-group");
        let tooltip = formGroup.querySelector(".tooltip");
        if(tooltip){
            tooltip.innerHTML = "";
            tooltip.classList.add("hidden");
        }
    }
    inputKeypressListener(event){
        if(event.target.classList.contains('key-validate')){
            this.validateField(event.target);
        }
    }

    selectChangeListener(event){
        if(event.target.classList.contains('key-validate')){
            this.validateField(event.target);
        }
    }

    blurListener(event){
        this.validateField(event.target);
    }

    validateField(field){
        if(!field.classList.contains('key-validate')){
            field.classList.add('key-validate');
        }
        const checkMsg = this.checkField(field);

        if(checkMsg){
            
            let message = field.dataset['msg_'+checkMsg];
            if(typeof (message) == "undefined"){
                message = "*";
            }
            
            this.showTooltip(field,message);
            return false;
        }
        else{
            this.hideTooltip(field);
            return true;
        }
    }
    checkField(field){
        // Don't validate submits, buttons, file and reset inputs, and disabled fields
        //alert(field.name);

        // Get validity
        let validity = field.validity;
        // If valid, return null
        if (validity.valid) return;
        
        // If field is required and empty
        if (validity.valueMissing) return 'required';

        // If too short
        if (validity.tooShort) return 'invalid';

        // If too long
        if (validity.tooLong) return 'invalid';

        // If pattern doesn't match
        if (validity.patternMismatch) {
            // Otherwise, generic error
            return 'invalid';

        }

    };
}


function help_debug_forms(debug_el){
    document.querySelectorAll('.biz-form-generator').forEach(
        wrapElement=>{
            const placeholder = wrapElement.querySelector(".biz-form-placeholder");
            
            const fetchUrl = placeholder.dataset.fetch_url;
            const formElement = wrapElement.querySelector("form.biz-form");
            const submitUrl = form_debug_helper.submitUrl;
            form_debug_helper.catHolder.value = form_debug_helper.selected_cat;
            if(submitUrl == ""){     
                alert("please select category");
                return;
            }
            if(!form_debug_helper.formValidator.validate()){
                alert("please note there are missing parameters. better to fill all form");
            }
            formElement.action = submitUrl;
            formElement.target = "_BLANK";
            const new_elements = document.createElement('div');
            new_elements.innerHTML = "<input type='submit' onClick='return updateCatIdHelper()' name='go' value='go' />";
            if(debug_el == 1){
                new_elements.innerHTML += "<input type='hidden' name='prevent_db_listing' value='1' />";
            }
            formElement.append(new_elements);
            //return;
            
        }
    );
}

function updateCatIdHelper(){
    form_debug_helper.catHolder.value = form_debug_helper.selected_cat;
}

function openbizForm(){
    const formModal = document.querySelector(".biz-form-modal");
    if(!formModal){
        return;
    }
    if(formModal.dataset.state == "empty"){
        const bizForm = document.querySelector(".biz-form-wrap");
        const formHolder = formModal.querySelector(".biz-form-holder");
        if((!bizForm) || (!formHolder)){
            return;
        }
        formHolder.append(bizForm);
        formModal.classList.remove("hidden");
        formModal.dataset.state == "set";
    }
}

function closebizForm(){
    const formModal = document.querySelector(".biz-form-modal");
    if(!formModal){
        return;
    }
    formModal.classList.add("hidden");
}
