//taken from shap... https://dental.medicart.co.il/
for (let form of [...document.forms]) {
    const formValidator = initFormValidator(form);
    formsValidationControl.push(formValidator);
}


function initFormValidator(form) {
    const landingPage = decodeURI(pageUrl);
    const sub_id = deviceType();
    form.dataset.landing_page = landingPage;
    form.dataset.sub_id = sub_id;
    return new FormValidator(form,
        {
            defaultParam: [
                ['Landing_Page', landingPage], // url of a current page
                ['Sub_ID', sub_id], //Type of Device. Mobile or desktop
                // ['Pub_ID', form.dataset.pub_id] //Type of Device. Mobile or desktop
                //pub_id functionality improved, moved to form validator, and can change during proccess
            ]
        });
}

class FormValidator {

    constructor(form, config){
        this.form = form;
        this.defaultParams = config.defaultParam || {};
    }

    init() {
        this.addFormListener();
    }

    addFormListener() {
        // Prevent input with name Phone from typing letters
        this.form.querySelector("[name='full_phone']").addEventListener("keypress", function preventKeyPress(evt){
            if (evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });

        // Check all fields on submit
        this.form.addEventListener('submit', (event) => {
            // Only run on forms flagged for validation
            if (!event.target.classList.contains('validate')) return;
            // Get all of the form elements
            let fields = event.target.elements;

            /*Validate each field
            Store the first field with an error to a variable so we can bring it into focus later*/
            let error, hasErrors;
            for (let input of fields) {
                error = FormValidator.hasError(input);
                if (error) {
                    this.showError(input, error);
                    if (!hasErrors) {
                        hasErrors = input;
                    }
                }
            }
            /*If there are errors, don't submit form and focus on first element with error*/
            if (hasErrors) {
                event.preventDefault();
                hasErrors.focus();
            } else {
                /*Otherwise, let the form submit normally
                You could also bolt in an Ajax form submit process here*/
                event.preventDefault();
                this.submitForm();
            }
        }, false);

        // Listen to all blur events
        this.form.addEventListener('blur', (event) => {
            // Only run if the field is in a form to be validated
            if (!event.target.form.classList.contains('validate')) return;
            // Validate the field
            let error = FormValidator.hasError(event.target);

            // If there's an error, show it
            if (error) {
                this.showError(event.target, error);
                return;
            }

            // Otherwise remove error
            this.showThanks(event.target, 'תודה :)');
            // Otherwise, remove any existing error message
            FormValidator.removeError(event.target);
        }, true);

        // Here close form event
        this.form.querySelector('.collapsed-button').addEventListener('click', (event) => {
            let controlNode = this.form.parentNode.parentNode,
                flipWhenOnDesktop = event.target.dataset.manipulator;

            Boolean(flipWhenOnDesktop) ? controlNode.classList.remove(flipWhenOnDesktop) : controlNode.classList.add('collapsed');

            // Close modal form
            if (controlNode.parentNode.classList.contains('w3-modal')) {
                controlNode.parentNode.style.display = 'none';
            }
        });
    }

    static validateInput(input){
        // Only run if the field is in a form to be validated
        if (!input.form.classList.contains('validate')) return;
        // Validate the field
        let error = FormValidator.hasError(input);
        // If there's an error, show it
        if (error) {
           FormValidator.showErrorStatic(input, error)                
            return false;
        }

        // Otherwise remove error
        FormValidator.showThanksStatic(input, 'תודה :)');
        // Otherwise, remove any existing error message
        FormValidator.removeError(input);       
        return true;
    }


    // Validate the field
    static hasError(field){
        // Don't validate submits, buttons, file and reset inputs, and disabled fields
        if (field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') return;

        // Get validity
        let validity = field.validity;

        // If valid, return null
        if (validity.valid) return;

        // If field is required and empty
        if (validity.valueMissing) return 'נא למלא שדה זה';

        // Decode data-message
        let message = decodeURI(field.dataset.message);

        // If too short
        if (validity.tooShort) return `${message} זה קצר מידי `;

        // If too long
        if (validity.tooLong) return `${message} הוא ארוך מדי `;

        // If pattern doesn't match
        if (validity.patternMismatch) {

            // If pattern info is included, return custom error
            if (field.hasAttribute('title')) return field.getAttribute('title');

            // Otherwise, generic error
            return 'Please match the requested format.';

        }

        // If all else fails, return a generic catchall error
        return 'The value you entered for this field is invalid.';

    };
    

    // Remove the error message
    static removeError(field) {

        // Remove error class to field
        field.classList.remove('error');

        // Remove ARIA role from the field
        field.removeAttribute('aria-describedby');

        // Get field name
        let id = field.name.includes('[]') ? field.name.slice(0, -2) : field.name;
        if (!id) return;

        // Check if an error message is in the DOM
        let message = field.form.querySelector('.error-message#error-for-' + id + '');
        if (!message) return;

        // If so, hide it
        message.innerHTML = '';
        message.style.display = 'none';
    };

    static splitPhoneNumber(phoneNumber) {
        if(!phoneNumber){
        return
        }
        let splitPhone = {};
        switch (phoneNumber.length) {
        case 9:
            /*Prefix has 2 digits always*/
            splitPhone = {
            'Phone_Prefix': phoneNumber.substr(0, 2),
            'Phone': phoneNumber.substr(2)
            };
            break;
        default :
            /*Prefix has 3 digits always*/
            splitPhone = {
            'Phone_Prefix': phoneNumber.substr(0, 3),
            'Phone': phoneNumber.substr(3)
            };
            break
        }
        return splitPhone;
    }


    static splitName(fullName){
        if(!fullName){
            return
        }

        let newName = fullName.trim().split(' ');
        return {
            'First_Name': newName[0],
            'Last_Name': newName[1] ? newName[1] : ''
        }
    } 

    // Show an error message
    showError(field, error){
        FormValidator.showErrorStatic(field, error);
    };

    // Show a thanks message
    showThanks(field, thanx) {
        FormValidator.showThanksStatic(field, thanx);
    }

    
    static showThanksStatic(field, thanx) {
        // Add success class to field
        field.classList.add('success');

        // Get field name
        let id = field.name.includes('[]') ? field.name.slice(0, -2) : field.name;
        if (!id) return;

        // Check if success message field already exists
        // If not, create one
        let message = field.form.querySelector('.success-message#success-for-' + id);
        if (!message) {
            message = document.createElement('div');
            message.className = 'success-message';
            message.id = 'success-for-' + id;
            field.parentNode.insertBefore(message, field);
        }

        // Add ARIA role to the field
        field.setAttribute('aria-describedby', 'success-for-' + id);

        // Update error message
        message.innerHTML = thanx;

        // Fade in success message
        setTimeout(function () {
            message.style.opacity = '1';
        }, 10);
        // Fade out success message
        setTimeout(function () {
            message.style.opacity = '0';
        }, 2500);      
    };


    // Show an error message
    static showErrorStatic(field, error){

        // Add error class to field
        field.classList.add('error');

        // Get field name
        let id = field.name.includes('[]') ? field.name.slice(0, -2) : field.name;
        if (!id) return;

        // Check if error message field already exists
        // If not, create one
        let message = field.form.querySelector('.error-message#error-for-' + id);
        if (!message) {
            message = document.createElement('div');
            message.className = 'error-message';
            message.id = 'error-for-' + id;
            field.parentNode.insertBefore(message, field);
        }

        // Add ARIA role to the field
        field.setAttribute('aria-describedby', 'error-for-' + id);

        // Update error message
        message.innerHTML = error;

        // Fade in error message
        setTimeout(function () {
            message.style.opacity = '1';
            message.style.display = 'block'
        }, 10);
        // Fade out error message
        setTimeout(function () {
            message.style.opacity = '0';
            message.style.display = 'none'
        }, 2500);

    };    

    //find which form got submitted from all forms on the page
    //good for a-b conversion testing
    static get_form_pub_id(form){

        //each form has uniq identifier pub_id, and if not then it had creator(old versions)
        let form_identifier = form.dataset.creator;
        if(form.dataset.pub_id){
            form_identifier = form.dataset.pub_id;
        }
        
        //there can also be multyple buttons that trigger opening of same form
        //the button click event will also alter the pub_id in the dataset 
        if(form.dataset.alterpub_id && form.dataset.alterpub_id != ""){
            form_identifier = form.dataset.alterpub_id;
        }
        //same form can come in tabs version or full version
        if(form.dataset.tabs_version && form.dataset.tabs_version!= 'full'){
            form_identifier += ", "+form.dataset.tabs_version;
        }

        return form_identifier;
    }


    // After All checks passed submit form
    submitForm(){
        /*The FormData interface provides a way to easily construct a set of key/value pairs representing
        form fields and their values, which can then be easily sent */
        // let formData = new FormData(this.form),
        let formData = [],
            // form requires detailed data, prefix and phone body
            phoneNew = FormValidator.splitPhoneNumber(this.form.elements.full_phone.value),
            // form requires detailed user name, first_name and last_name
            userName = FormValidator.splitName(this.form.elements.full_name.value),
            // all properties needed
            properties = Object.assign(phoneNew, userName, this.defaultParams);

        this.form.querySelectorAll('input,select').forEach((input) => {
            formData.push([input.name, input.value]);
        });
        formData = formData.concat(this.defaultParams);

        /*Append custom field required by distribution API*/
        // formData.append('ID', uuidv4()); // create unique form id
        formData.push(['ID', uuidv4()]); // create unique form id
        
        //which form on the page got submitted - Pub_id, using method - get_form_pub_id
        formData.push(['Pub_ID', FormValidator.get_form_pub_id(this.form)]); 
        
        /*Set the true phone prefix, phone, lastName, firstName to be submitted.*/
        formData.push(['Phone_Prefix[]', phoneNew.Phone_Prefix]);
        formData.push(['Phone', phoneNew.Phone]);
        formData.push(['First_Name', userName.First_Name]);
        formData.push(['Last_Name', userName.Last_Name]);

        /*Emit custom event*/
        let data = urlencodeFormData(formData);

        if(this.form.elements.full_name.value == "check data"){
            console.log(formData);
            return;
        }
    
        /*Send Lead to Medicart Distribution System*/
        request({
            method: 'POST',
            url: this.form.action,
            body: data,
            headers: {
                "content-type": "application/x-www-form-urlencoded"
            }
        }).then((value) => {
            /* create and dispatch form submitted event */
            FormValidator.showThnxMessage();
        }, (reason) => {
            FormValidator.showThnxMessage();
            console.log('sent:',reason);
        });


        //if gtm scripts exist and create datalayer object
        if(typeof(dataLayer) !== "undefined"){
            dataLayer.push({
                FormType: this.form.dataset.creator,
                LeadID: leadFormID(),
                UserDemographics: this.form.querySelector('.region').value,
                event: "LeadApproved",
                productName: this.form.querySelector('.procedure-type').value
            });
        }

        // Close form after 1.4 sec. Need remake to dispatchEvent
        let formNode = this.form.parentNode.parentNode,
            flipOnDesktop = this.form.querySelector('.collapsed-button').firstElementChild.dataset.manipulator;
        setTimeout(function () {
            Boolean(flipOnDesktop) ? formNode.classList.remove(flipOnDesktop) : formNode.classList.add('collapsed');
            if (formNode.parentNode.classList.contains('w3-modal')) {
                formNode.parentNode.style.display = 'none';
            }
        }, 1100);
    }

    /*Listen to any form submission*/
    static showThnxMessage() {
        /*Create thxModal on page*/
        let thxUrl = `${document.location.origin}/thx-page`;
        /* Promise get thanks page adwords */
        request({url: thxUrl})
            .then(responce => {
                pop_responce_modal('thxModal',responce);
            })
            .catch(error => {
                console.warn(error);
            });
    }
}