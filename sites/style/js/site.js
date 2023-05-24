

var open_sub_menu = false;
function show_sub_menu(sender_el){  
    menu_item_el = document.getElementById(sender_el.dataset.item_id);

    if(menu_item_el.classList.contains('active-sub')){
        menu_item_el.classList.remove('active-sub');
    }
    else{
        close_all_top_submenus();
        menu_item_el.classList.add('active-sub');
        open_sub_menu = menu_item_el;

        document.addEventListener("click", close_top_sub_menus, true);

    }
}

function close_top_sub_menus(event){
    if(!open_sub_menu.contains(event.target)){
        close_all_top_submenus();

    }
}

function close_all_top_submenus(){
    selectedItems = document.querySelectorAll('.active-sub');

    selectedItems.forEach(sel=>{
        sel.classList.remove("active-sub");
    });
    document.removeEventListener("click", close_top_sub_menus, true);
}


function toggleDrawer(drawerId){
    let drawer = document.getElementById(drawerId + "_wrap");
    if(!drawer.classList.contains('opened')){
        if(!drawer.classList.contains('right-menu-added')){
            drawer.classList.add('right-menu-added');
            let right_menu = document.getElementById("right_menu");
            drawer.append(right_menu);
        }
        drawer.classList.add('opened');
        drawer.classList.remove('closed');
        //drawer.style.width = "250px";
        drawer.style.right = "0px";
       // drawer.style.display = "block";
    }
    else{
        drawer.classList.remove('opened');
        drawer.classList.add('closed');
       // drawer.style.width = "0";
        drawer.style.right = "-400px";
       // drawer.style.display = "none";
    }
}

function openDrawer(drawerId) {
    
    document.getElementById(drawerId + "_wrap").style.width = "300px";
    document.getElementById(drawerId + "_drawer_overlay").style.display = "block";
  }
  
  function closeDrawer(drawerId) {
    document.getElementById(drawerId + "_wrap").style.width = "0";
    document.getElementById(drawerId + "_drawer_overlay").style.display = "none";
  }


document.addEventListener("DOMContentLoaded",()=>{
    initBannerClickers();
    initPageRearangement();
    initQuotesToggler();
});

initQuotesToggler = ()=>{
    document.querySelectorAll('.quote-cat-toggler').forEach(
        toggler=>{
            toggler.addEventListener("click",(event)=>{
                const quoteCat = toggler.closest(".quote-cat-wrap");
                const quoteList = quoteCat.querySelector(".quote-list-wrap");
                if(quoteCat.dataset.state == "open"){
                    quoteCat.dataset.state = "closed";
                    quoteList.classList.add("hidden");
                }
                else{
                    quoteCat.dataset.state = "open";
                    quoteList.classList.remove("hidden");                    
                }
            });
        }
    );
} 

initBannerClickers = ()=>{
    document.querySelectorAll('.banner-clicker').forEach(
        clicker=>{
            clicker.addEventListener("click",(event)=>{
                const pixel = document.createElement('span');
                const count_url = clicker.dataset.count_url;
                const link_url = clicker.dataset.link;
                pixel.innerHTML = "<img width='1' height='1' style='display:none' src='"+ count_url +"' />";
                clicker.appendChild(pixel);
                setTimeout(function(){    
                    window.location.href =  link_url;
                },500);
            });
        }
    );
} 

initPageRearangement = ()=>{
    rearangeLeftBar();
    relocateBizForm();
    grabMainTitle();
    grabSearchForm();
    fixHeaderSpacing();
    setTimeout(function(){fixHeaderSpacing()},300);
} 

grabMainTitle = ()=>{
    const titleHolder = document.querySelector(".main-title-holder");
    if(!titleHolder){
        return
    }
    const titleElement = document.querySelector("#content_title_wrap");
    if(titleElement){
        titleHolder.append(titleElement);
    }
}

grabSearchForm = ()=>{
    const searchHolder = document.querySelector(".search-form-holder");
    if(!searchHolder){
        return
    }
    const searchElement = document.querySelector("#content_title_wrap");
    if(searchElement){
        searchHolder.append(searchElement);
    }
}

rearangeLeftBar = ()=>{
    const leftBarTop = document.querySelector(".leftbar-top-holder");
    const leftBarMid = document.querySelector(".leftbar-mid-holder");
    const leftBarBottom = document.querySelector(".leftbar-bottom-holder");
    if(leftBarTop){ 
        document.querySelectorAll('.go-left, .go-left-top').forEach(
            goLeft=>{
                leftBarTop.append(goLeft);
            }
        );
        document.querySelectorAll('.go-left-mid').forEach(
            goLeft=>{
                leftBarMid.append(goLeft);
            }
        );
        document.querySelectorAll('.go-left-bottom').forEach(
            goLeft=>{
                leftBarBottom.append(goLeft);
            }
        );
    }
    document.querySelectorAll(".grab-content").forEach(
        grabber=>{   
            const grabClass = grabber.dataset.grab;
            document.querySelectorAll("."+grabClass).forEach(grabbedContent=>{
                grabber.append(grabbedContent);
            });
        }
    );
} 


relocateBizForm = ()=>{
    let formHolder = document.querySelector(".hero-form-holder");
    if(!formHolder){
        return;
    }
    if(window.innerWidth < 890){
        if(formHolder.classList.contains("wide-only")){
            return;
        }
    }
    if(!formHolder){
        return;
    }
    let bizForm = document.querySelector(".biz-form-wrap");
    if(bizForm){
        formHolder.append(bizForm);
    }
    
} 

fixHeaderSpacing = ()=>{
    let fixTop = document.querySelector(".top-fix");
    if(!fixTop){
        return;
    }
    let pageWrap = document.querySelector("#page_wrap");
    if(!pageWrap){
        return;
    }
    pageWrap.style.marginTop = fixTop.offsetHeight + "px";
} 