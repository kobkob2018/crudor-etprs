

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
});

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
