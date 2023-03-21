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
        this.submitUrl = "";
        this.selectEventListenerBinded = this.selectEventListener.bind(this);
        this.submitEventListenerBinded = this.submitEventListener.bind(this);
        this.testFunctionBinded = this.testFunction.bind(this);
        
        this.submitButton.addEventListener("click", this.submitEventListenerBinded, true);
        this.max_stock = 0;
        this.initFetch();
        this.initTestButton();
    }

    initFetch(){
        const cat_id = this.placeholder.dataset.cat_id;
        this.fetchForCat(cat_id);


    }
    fetchForCat(cat_id){
        this.enterLoadingState();
        fetch(this.fetchUrl+"?cat_id="+cat_id+"&form_id="+this.form_id).then((res) => res.json()).then(info => {
            
            if(info.success){
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
                this.submitForm();
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
    initTestButton(){
        
        this.formElement.querySelectorAll(".tester-button").forEach(testButton=>{
            testButton.addEventListener("click", this.testFunctionBinded, true);
        });
    }
    testFunction(){
        
        fetch(this.fetchUrl+"?test_form=1&cat_id=0&form_id="+this.form_id).then((res) => res.json()).then(info => {
            console.log("adding pixels for testing");

            const successEl = document.createElement('div');
            successEl.innerHTML = info.html;
            this.wrapElement.insertBefore(successEl,this.formElement);
        }).catch(function(err) {
            
            console.log(err);
            console.log("The test failed... ");
            //alert("Something went wrong. please reload the page");
        });
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


function help_debug_forms(){
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
            formElement.append(new_elements);
            //return;
            
        }
    );
}

function updateCatIdHelper(){
    form_debug_helper.catHolder.value = form_debug_helper.selected_cat;
}