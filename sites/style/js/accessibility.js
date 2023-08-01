let acc_cookie = {};

document.addEventListener("DOMContentLoaded",()=>{
    initAccessibility();
});


function initAccessibility(){
    init_back_to_top_button();
    acc_local_cookie = read_acc_cookie();
    if(acc_local_cookie && acc_local_cookie != null){
        acc_cookie = acc_local_cookie;
    }
    if(acc_cookie.biggerfont){
        console.log(acc_cookie.biggerfont);
        document.body.classList.add("biggerfont-"+acc_cookie.biggerfont);
    }
    if(acc_cookie.contrastOn && acc_cookie.contrastOn == '1' ){
        document.body.classList.add("contrastOn");
    }
    
    if(acc_cookie.linksOn && acc_cookie.linksOn == '1' ){
        document.body.classList.add("linksOn");
    }
}

function biggerfont(){
    let currentFont = 0;
    for(let i = 1;  i < 4; i++){
        const sizeClass = "biggerfont-"+i;
        if(document.body.classList.contains(sizeClass)){
            currentFont = i;
            
        }
    }
    if(currentFont == 3){
        return;
    }
    if(currentFont != 0){
        document.body.classList.remove("biggerfont-"+currentFont);
    }
    document.body.classList.add("biggerfont-"+(currentFont+1));
    acc_cookie.biggerfont = currentFont+1;
    setup_acc_cookie();
}

function smallerfont(){
    let currentFont = 0;
    for(let i = 1;  i < 4; i++){
        const sizeClass = "biggerfont-"+i;
        if(document.body.classList.contains(sizeClass)){
            currentFont = i;
        }
    }
    if(currentFont == 0){
        acc_cookie.biggerfont = null;
        setup_acc_cookie();
        return;
    }
    
    document.body.classList.remove("biggerfont-"+currentFont);
    if(currentFont != 1){
        document.body.classList.add("biggerfont-"+(currentFont-1));
        acc_cookie.biggerfont = currentFont-1;
        setup_acc_cookie();
    }
}

function biggerfontOff(){
    for(let i = 1;  i < 4; i++){
        const sizeClass = "biggerfont-"+i;
        document.body.classList.remove(sizeClass);
        acc_cookie.biggerfont = null;
        setup_acc_cookie();
    }
}

function contrastOn(){
    document.body.classList.add("contrastOn");
    acc_cookie.contrastOn = 1;
    setup_acc_cookie();
}

function contrastOff(){
    document.body.classList.remove("contrastOn");
    acc_cookie.contrastOn = null;
    setup_acc_cookie();
}

function linksOn(){
    document.body.classList.add("linksOn");
    acc_cookie.linksOn = 1;
    setup_acc_cookie();
}

function linksOff(){
    document.body.classList.remove("linksOn");
    acc_cookie.linksOn = null;
    setup_acc_cookie();
}

function resetAcessability(){
    biggerfontOff();
    linksOff();
    contrastOff();
}



function setup_acc_cookie() {
    let cookie_name = get_acc_cookie_name(); 
    let accCookieValue = {};
    for (const [key, value] of Object.entries(acc_cookie)) {
        if(value != null && value != '0'  && value != 0){
            accCookieValue[key] = value;
        }
    }
    const json_data = JSON.stringify(accCookieValue);
    var date = new Date();
    date.setTime(date.getTime() + Number(500) * 3600 * 1000);
    document.cookie = cookie_name + "=" + json_data + "; path=/;expires = " + date.toGMTString();   
}

function read_acc_cookie(){
    let cookie_name = get_acc_cookie_name(); 
    
    function escape(s) { return s.replace(/([.*+?\^$(){}|\[\]\/\\])/g, '\\$1'); }
    var match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(cookie_name) + '=([^;]*)'));
    if(match){
        
        return JSON.parse(match[1]);
    }
    return null; 
}

function get_acc_cookie_name(){
    let cookie_name = "ACC_SETTINGS"; 
    if(cookie_prefix){
        cookie_name = cookie_prefix + "_" + cookie_name;
    }
    return cookie_name;
}

function toggleAccNav(){
    
    const accNav = document.querySelector(".acc-nav");
    const doorEl = document.querySelector(".acc-nav-door");
    if(!doorEl.classList.contains("init")){
        initAccNavItems();
        doorEl.classList.add("init");
    }
    if(!accNav){
        return;
    }
    if(accNav.classList.contains("hidden")){
        document.querySelector(".acc-nav").classList.remove("hidden");
        doorEl.querySelector(".open-label").classList.add("hidden");
        doorEl.querySelector(".close-label").classList.remove("hidden");

    }
    else{
        document.querySelector(".acc-nav").classList.add("hidden");
        doorEl.querySelector(".open-label").classList.remove("hidden");
        doorEl.querySelector(".close-label").classList.add("hidden");
    }
}

function initAccNavItems(){
    let index = 0;
    const navUl = document.querySelector(".acc-nav");
    document.querySelectorAll("h2").forEach(h2=>{
        if(h2.id == ""){
            index++;
            h2.id = "acc_item_"+index;
        }
        const anchor = "#"+h2.id;
        const liItem = document.createElement('li');
        liItem.classList.add("acc-nav-item");
        const aItem = document.createElement('a');
        aItem.innerHTML = h2.innerText;
        aItem.href = anchor;
        aItem.title = h2.innerText;
        aItem.addEventListener("click",function(){
            
        });
        liItem.append(aItem);  
        navUl.append(liItem);       
    });
    navUl.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault(e);
    
            document.location.hash=this.getAttribute('href').replace("#","");

            afterAccAnchorClick(true);
        });
    });
}

function afterAccAnchorClick(withFixTop){
    toggleAccNav();
    closeDrawer('accessibility');
    if(false && withFixTop){

        setTimeout(function(){
            window.scrollBy(0, -100);
        },100);
    }
    
    document.querySelector("#accessibility_wrap").scrollTop = '0';
}

function init_back_to_top_button(){
    const back_to_top_button = document.querySelector(".back-to-top-button-wrap");
    document.addEventListener("scroll",function(){
        toggle_back_to_top_button(back_to_top_button);
    });
}

function toggle_back_to_top_button(back_to_top_button){
    if(window.scrollY < 250){
        back_to_top_button.classList.add("hidden");
    }
    else{
        back_to_top_button.classList.remove("hidden");
    }
}

function scroll_back_to_top(){
    const back_to_top_button = document.querySelector(".back-to-top-button-wrap");
    back_to_top_button.classList.add("clicked");
    window.scrollTo({top: 0, behavior: 'smooth'});
    setTimeout(function(){
        back_to_top_button.classList.add("hidden");
        back_to_top_button.classList.remove("clicked");
    }, 1000);

}