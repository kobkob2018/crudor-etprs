document.addEventListener("DOMContentLoaded",()=>{
    initFormValidators();
});
initFormValidators = ()=>{
    document.querySelectorAll('.form-validate').forEach(
        formElement=>{
            bizForm = new formValidator(formElement);
        }
    );
} 

class formValidator{
    constructor(formElement) {
        this.formElement = formElement;
        console.log(this.formElement);
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
        this.submitClickedBinded = this.submitClicked.bind(this);
        this.formElement.addEventListener("submit",this.submitClickedBinded);
        this.updateInputs();

    }

    submitClicked(event){
        this.validate();
        alert("clicked");
        event.preventDefault();
        return false;

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
            if(!this.validateField(field)){
                
                isValid = false;
            }
        });
        return isValid;
    }
    showTooltip(field, message){
        const tipHolder = field.closest(".tip-holder");
        let tooltip = tipHolder.querySelector(".tooltip");
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tipHolder.insertBefore(tooltip, field);
        }
        tooltip.innerHTML = message;
        tooltip.classList.remove("hidden");
    }
    hideTooltip(field){
        const tipHolder = field.closest(".tip-holder");
        let tooltip = tipHolder.querySelector(".tooltip");
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