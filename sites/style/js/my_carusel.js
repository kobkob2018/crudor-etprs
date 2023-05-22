
window.addEventListener("load", (event) => {
    setTimeout(function(){prepareModalGallery();},500);
});

let galleryCarusels = [];
function prepareModalGallery(){
    const modals = document.querySelectorAll(".gallery-modal");
    const modals_placeholder = document.getElementById("modals_placeholder");
    
    modals.forEach(modal => {
        modals_placeholder.append(modal);
        const galleryCarusel = new Mycarusel({wrap: 'gallery-carusel',autoplay:false}); 
        if(modal.id){
            galleryCarusels[modal.id] = galleryCarusel;
        }
        galleryCarusel.init();
        const closebtn = modal.querySelector('.closebtn');
        closebtn.addEventListener('click',()=>{
            
            hideModalGallery(modal.id);
        });
    });
    const modalOpeners = document.querySelectorAll('.modal-gallery-a');
    modalOpeners.forEach(opener=>{
        opener.addEventListener('click',()=>{
            
            showModalGallery(opener.dataset.gallery_id,opener.dataset.img_index);
        });
    });
    
}

function showModalGallery(gal_id, selectedIndex = false){
    const gallerymodal = document.getElementById(gal_id);
    
    if(selectedIndex && galleryCarusels[gal_id]){
        galleryCarusels[gal_id].pickIndex(selectedIndex);
    }
    gallerymodal.classList.add('modal-on');
}
 
function hideModalGallery(gal_id){
    
    const gallerymodal = document.getElementById(gal_id);
    gallerymodal.classList.remove('modal-on');
}



class Mycarusel{
    constructor(config) {
        const defaults = {
            loopStyle: 'fade',
            autoplay: true
        }
        const wrapQuery = "." + config.wrap;
        const wrapArr = document.querySelectorAll(wrapQuery);
        if(!wrapArr[0]){
            
            return false;
        }
        this.wrap = wrapArr[0];
        this.items = this.wrap.querySelectorAll(".item");
        const nextButton = this.wrap.querySelectorAll(".carusel-next");
        if(nextButton[0]){
            this.nextButton = nextButton[0];
        }
        else{
            this.nextButton = false;
        }
        const previousButton = this.wrap.querySelectorAll(".carusel-previous");
        if(previousButton[0]){
            this.previousButton = previousButton[0];
        }
        else{
            this.previousButton = false;
        }
        if(this.items[0]){
            this.currentItem = this.items[0];
            this.lastItem = this.items.length - 1;
        }
        else{
            this.currentItem = false;
        }
        const settings = {};

        for (const [key, value] of Object.entries(defaults)){
            
            if(config[key] != null){
                settings[key] = config[key];
            }
            else{
                settings[key] = defaults[key];
            }
        }
        this.settings = settings;
    }

    init() {
        if(!this.currentItem){
            return;
        }
        
        for (let i = 0; i < this.items.length; i++){
            this.items[i].dataset.carusel_index = i;
            if(i != 0){
                this.items[i].classList.add("carusel-off");
            }
            else{
                this.items[i].classList.add("carusel-on");
            }
        }
        if(this.nextButton){
            this.nextButton.addEventListener('click', () => {
                this.killAutoplayInterval();
                this.next();
            });
        }

        if(this.previousButton){
            this.previousButton.addEventListener('click', () => {
                this.killAutoplayInterval();
                this.previous();
            });
        }
        if(this.settings.autoplay){
            
            this.autoPlayState = 'on';
            this.autoplayInterval = setInterval(()=>{
                this.next();
            },3000);
        }
    }

    next(){        
        const next = this.getNext();
        if(next){
            this.swichStylish(this.currentItem, next);
            this.currentItem = next;
        }
    }

    previous(){
        const previous = this.getPrevious();
        if(previous){
            this.swichStylish(this.currentItem, previous,'-prev');
            this.currentItem = previous;
        }        
    }

    getNext(){
        const currentIndex = this.currentItem.dataset.carusel_index;
        let nextIndex;
        if(this.lastItem == currentIndex){
            nextIndex = '0';
        }
        else{
            nextIndex = parseInt(currentIndex) + 1;
        }
        
        let searchQuery = ".item[data-carusel_index='" + nextIndex + "']";
        
        let indexItem = this.wrap.querySelectorAll(searchQuery);
        if(indexItem[0]){
            return indexItem[0];
        }
        else{
            searchQuery = ".item[data-carusel_index='0']"
            indexItem = this.wrap.querySelectorAll(searchQuery);
            if(indexItem[0]){
                return indexItem[0];
            }
            else{
                return false;
            }
        }
    }

    getPrevious(){
        const currentIndex = this.currentItem.dataset.carusel_index;
        let nextIndex;
        if(currentIndex == 0){
            nextIndex = this.lastItem;
        }
        else{
            nextIndex = parseInt(currentIndex) - 1;
        }
        let searchQuery = ".item[data-carusel_index='" + nextIndex + "']"
        let indexItem = this.wrap.querySelectorAll(searchQuery);
        if(indexItem[0]){
            return indexItem[0];
        }
        else{
            searchQuery = ".item[data-carusel_index='0']"
            indexItem = this.wrap.querySelectorAll(searchQuery);
            if(indexItem[0]){
                return indexItem[0];
            }
            else{
                return false;
            }
        }        
    }

    swichStylish(currentItem, nextItem, comingSufix = ''){
        currentItem.classList.add("carusel-removing"+comingSufix);
        nextItem.classList.add("carusel-coming"+comingSufix);
        nextItem.classList.add("carusel-on");
        nextItem.classList.remove("carusel-off");

        setTimeout(()=>{
            nextItem.classList.remove("carusel-coming"+comingSufix);
        },400);

        setTimeout(()=>{
            currentItem.classList.add("carusel-off");
            currentItem.classList.remove("carusel-on");
            currentItem.classList.remove("carusel-removing"+comingSufix);
        },800);
    }
    killAutoplayInterval(){
        if(this.autoPlayState == 'on'){
            this.autoPlayState == 'off'
            clearInterval(this.autoplayInterval);
        }
            
    }

    pickIndex(imgIndex){
        const selectedItem = this.wrap.querySelector(".item_id_"+imgIndex);
        if(selectedItem){
            this.currentItem.classList.remove("carusel-on");
            this.currentItem.classList.add("carusel-off");
            selectedItem.classList.add("carusel-on");
            selectedItem.classList.remove("carusel-off");
            this.currentItem = selectedItem;
        }
        
    }
}
