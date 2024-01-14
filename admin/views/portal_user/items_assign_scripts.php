
<style type="text/css">
    .user-id-select-form{
        border: 1px solid;
        padding: 10px;
        position: absolute;
        background: #e9e9d3;
        border-radius: 5px;
        box-shadow: 5px 5px 5px gray;
    }
    .user-list-finder-wrap{
        position: relative;
    }
    .user-list-wrap{
        position: absolute;
        left: 0px;
        z-index: 2;
        background: #c2c2c2;
        padding: 5px;
        margin-top: 5px;
    }
    .close-list-x{
        text-decoration: none;
        font-family: sans-serif;
        color: red;
        font-weight: bold;
    }
    .close-list-x{
        display: block;
        text-align: left;
        padding: 4px 5px 1px;
        
        font-size: 22px;

    }

    .user-result-template{
        background: #e3e3c4;
        border: 2px solid black;
        padding: 6px 12px;
        margin: 2px 0px;
        font-family: sans-serif;
        cursor: pointer;
        font-weight: bold;
    }
    .user-result-template:hover{
        background: blue;
    }
</style>


<script type="text/javascript">
    const api_url_base = "<?= inner_url($info['api_url']) ?>";
    const user_list = <?= json_encode($info['site_users']) ?>;
    function find_user_by_str(search){
        if(search.length == 1){
            return user_list.filter((user) => user.full_name.startsWith(search));
        }
        return user_list.filter((user) => user.full_name.includes(search));
    }

    function select_item_user_id(a_el){
        const select_wrap = a_el.closest(".user-id-select-wrap");
        const select_a = select_wrap.querySelector(".user-id-select-a");
        const select_form = select_wrap.querySelector(".user-id-select-form");
        select_form.classList.remove("hidden");
    }

    function list_user_id_options(input){
        const finder_wrap = input.closest(".user-list-finder-wrap");
        const result_wrap = finder_wrap.querySelector(".user-list-wrap");
        const result_list = finder_wrap.querySelector(".user-list-results");
        const apitoui = document.querySelector(".api-to-ui");
        result_list.innerHTML = "";
        //console.log(result_list);
        if(input.value.length < 1){
            return;
        }
        const found_list = find_user_by_str(input.value);
        found_list.forEach(user => {

            const user_result_el = apitoui.querySelector(".user-result-template").cloneNode(true);
            console.log(user_result_el);
            user_result_el.innerHTML = user.full_name;
            user_result_el.dataset.user_id = user.id;
            result_list.append(user_result_el);  
        });
        //console.log(found_list);
        
        return;
    }


    function select_user_assign(selected_el){
        if(!confirm("<?= __tr("replace the item user?") ?>")){
            return;
        }
        const select_wrap = selected_el.closest(".user-id-select-wrap");
        const close_a_el = select_wrap.querySelector(".close-list-x");
        const user_id_select_a = select_wrap.querySelector(".user-id-select-a");
        const user_id = selected_el.dataset.user_id;
        const item_id = select_wrap.dataset.item_id;
        const user_label = selected_el.innerHTML;

        if(user_id == select_wrap.dataset.user_id){
            return close_select_form(close_a_el);
        }

        //todo ajax call to change user_id for page and then change the label and dataset of the item
        fetch(api_url_base+"?item_id="+item_id+"&user_id="+user_id).then((res) => res.json()).then(info => { 
           if(info['success'] == 'ok'){
                select_wrap.dataset.user_id = info['user_id'];
                user_id_select_a.innerHTML = info['user_label'];
           }
           else{if(info['err_msg']){
            alert(info['err_msg']);
           }}
        }).catch(function(err) {
            console.log(err);
            alert("Something went wrong. please reload the page");
        });
        return close_select_form(close_a_el);
    }

    function close_select_form(a_el){
        const select_wrap = a_el.closest(".user-id-select-wrap");
        const select_form = select_wrap.querySelector(".user-id-select-form");
        const user_list_results = select_form.querySelector(".user-list-results");
        const list_select = select_wrap.querySelector(".list-select");
        user_list_results.innerHTML = "";
        select_form.classList.add("hidden");
        list_select.value = "";
    }
</script>

<div class="api-to-ui hidden">
    <div class="user-result-template" onclick="select_user_assign(this)">

    </div>
</div>