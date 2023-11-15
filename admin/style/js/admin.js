document.addEventListener("DOMContentLoaded",()=>{
    document.querySelectorAll(".show-off").forEach(function(showOff){
        setTimeout(function(){showOff.classList.remove("show-off");},100);
        
    });
});

function toggle_block(block_id){
    const block = document.querySelector("."+block_id);
    if(!block){
        return;
    }
    if(block.dataset.view_state == 'show'){
        hide_block(block_id);
    }
    else{
        show_block(block_id);
    }
}

function hide_block(block_id){
    const block = document.querySelector("."+block_id);
    block.classList.add("hidden");
    block.dataset.view_state = 'hidden';
}

function show_block(block_id){
    const block = document.querySelector("."+block_id);
    block.classList.remove("hidden");
    block.dataset.view_state = 'show';
}