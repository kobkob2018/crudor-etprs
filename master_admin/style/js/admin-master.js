

function assign_select_toggle(aDoor){
    const assign_id = aDoor.dataset.assign_id;
    const parentEl = aDoor.closest(".assign-checkbox-parent-wrap");
    console.log(parentEl);
    if(parentEl.classList.contains('closed')){
        parentEl.classList.remove('closed');
        parentEl.classList.add('open');
        aDoor.classList.remove('a-closed');
        aDoor.classList.add('a-open');
        
    }
    else{
        parentEl.classList.remove('open');
        parentEl.classList.add('closed');
        aDoor.classList.remove('a-open');
        aDoor.classList.add('a-closed');
    }
}