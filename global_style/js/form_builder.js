document.addEventListener("DOMContentLoaded",()=>{
    init_form_comments();
});

function init_form_comments(){
    document.querySelectorAll('.form-builder-comment').forEach(comment=>{
        const field_class = comment.dataset.for;
        const form_group = document.querySelector("."+field_class);
        if(!field_class){
            return;
        }
        const comment_target = form_group.querySelector(".form-group-st");
        if(!comment_target){
            returnl
        }
        comment_target.append(comment);
    });
}