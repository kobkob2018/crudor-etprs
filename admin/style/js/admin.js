document.addEventListener("DOMContentLoaded",()=>{
    document.querySelectorAll(".show-off").forEach(function(showOff){
        setTimeout(function(){showOff.classList.remove("show-off");},100);
        
    });
});