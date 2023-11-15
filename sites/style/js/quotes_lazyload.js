document.addEventListener("DOMContentLoaded",()=>{
    initQuotesLazyload();
});

function initQuotesLazyload(){
    const quote_users = {'users':[]};
    
    document.querySelectorAll('.quote-wrap.row-info').forEach(
        user_row=>{
            if(user_row.dataset.user_id){
                quote_users.users.push(user_row.dataset.user_id); 
            }
        }
    );
    fetch("quotes/fetch_users/",{            
        method: 'POST', // Specify the HTTP method
        body: JSON.stringify(quote_users),

    }).then((res) => res.json()).then(info => {
        
        if(info.res != 'ok'){
            return;
        }
        document.querySelectorAll('.quote-wrap.row-info').forEach(

            user_row=>{
                if(user_row.dataset.user_id){
                    const user_id = user_row.dataset.user_id;
                    if(info.users[user_id]){
                        setQuotesLazyloadInfo(info.users[user_id], user_row)
                    }
                }
            }
        );
    });



    
}

function setQuotesLazyloadInfo(quote_info, user_row){
    user_row.querySelectorAll(".quote-user-info-holder").forEach(holder=>{
        
        if(quote_info[holder.dataset.get_info]){
            
            if(!holder.dataset.holder_type){
                
                holder.innerHTML = quote_info[holder.dataset.get_info];
            }
            else{
                if(holder.dataset.holder_type == 'user_logo'){
                    const image_html = "<img class='quote-img' alt='"+quote_info['label']+"' src='"+quote_info['image_url']+"'>";
                    holder.innerHTML = image_html;
                }
                if(holder.dataset.holder_type == 'link'){

                    const link = holder.querySelector(".href_holder");
                    link.href = quote_info.link;
                    holder.classList.remove("hidden");
                }
            }
        }
    });
}