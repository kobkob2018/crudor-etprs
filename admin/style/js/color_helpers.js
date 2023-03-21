document.addEventListener("DOMContentLoaded",()=>{
    initColorHelpers();
});

initColorHelpers = ()=>{
    document.querySelectorAll('.color-text').forEach(
        colorGroup=>{
            colorHelper = new ColorHelper(colorGroup);
        }
    );
}

class ColorHelper{

    constructor(colorGroup) {
        this.colorGroup = colorGroup;
        this.colorBoxHolder = this.createColorBoxHolder();
        
        this.textbox = this.colorGroup.querySelector("input.form-input");
        this.color = this.textbox.value;
        this.updateColorFromTextBinded = this.updateColorFromText.bind(this);
        this.textbox.addEventListener("blur",this.updateColorFromTextBinded,true);
        this.updateColor(this.color);
    }

    createColorBoxHolder(){
        const colorBoxHolder = document.createElement('div'); 
        colorBoxHolder.classList.add("form-group-color");
        this.colorGroup.append(colorBoxHolder);
        return colorBoxHolder;
    }

    updateColor(bg){
        this.colorBoxHolder.innerHTML = "<div style='width:30px; height:30px; background: "+ bg +"'></div>";
    }

    updateColorFromText(){
        this.color = this.textbox.value;
        this.updateColor(this.color);
    }

}