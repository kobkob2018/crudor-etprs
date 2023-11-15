<div class="import-box">
    <a href="javascript://" onclick="toggle_block('import-theme-form-wrap')">לחץ כאן לייבוא מבנה מוכן</a>
    <div class="import-theme-form-wrap hidden" data-view_state="hidden">
        
        <div class="import-theme-form-block focus-box">
            <div class="close-btn-wrap">
                <a href="javascript://" onclick="toggle_block('import-theme-form-wrap')">
                    X
                </a>
            </div>
            <h3>ייבוא מבנה קיים</h3>
            <div class="import-theme-form">
                <ul class="import-theme-option-list">
                    <?php foreach($this->data['cat_theme_list'] as $theme): ?>
                        <li class="import-theme-option" onclick="select_theme_option(this)" data-theme_id="<?= $theme['id'] ?>">
                            <?= $theme['label'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="import-theme-submit-wrap">
                    <button type="button" class="import-theme-submit pending" disabled onclick="import_selected_theme()">לחץ כאן לייבא את המבנה אל התיקייה</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function select_theme_option(li_option){
        const selected_li = document.querySelector(".import-theme-option.selected");
        const submit_btn = document.querySelector(".import-theme-submit");
        if(selected_li){
            selected_li.classList.remove("selected");
        }
        li_option.classList.add("selected");
        submit_btn.classList.remove("pending");
        submit_btn.classList.add("ready");
        submit_btn.disabled = false;
    }

    function import_selected_theme(){
        const selected_li = document.querySelector(".import-theme-option.selected");
        if(!selected_li){
            alert("יש לבחור עיצוב");
            return;
        }
        const import_theme_id = selected_li.dataset.theme_id;
        fetch("<?= inner_url("quote_cats/import_theme_info/") ?>?theme_id="+import_theme_id).then((res) => res.json()).then(info => {
            if(!info.theme){
                alert("Something went wrong. please reload the page");
                return;
            }
            document.querySelector("#row_custom_html_textarea").innerHTML = info.theme.custom_html;
            document.querySelector("#row_title_html_textarea").innerHTML = info.theme.title_html;
            alert("הייבוא בוצע. יש לשמור את התיקייה");
            hide_block("import-theme-form-wrap");
        }).catch(function(err) {
            console.log(err);
            alert("Something went wrong. please reload the page");
        });
    }

</script>

<style type="text/css">
    .import-theme-form-wrap{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: #00000080;
       
    }
    .import-theme-form-block{
        width: 401px;
        max-width: 80%;
        margin: auto;
        margin-top: 43px;
        box-shadow: 5px 5px 5px black;
    }
    .close-btn-wrap{
        text-align: left;
    }
    .close-btn-wrap a{
        text-decoration: none;
        font-family: sans-serif;
        color: red;
        font-weight: bold;
    }
    .import-theme-option-list{
        max-height: 150px;
        overflow: auto;
    }
    .import-theme-option{
        display: block;
        margin: 5px 5px;
        padding: 5px;
        background: #6382ed;
        border: 2px solid #4a5165;
        color: white;
        border-radius: 3px;
        font-family: sans-serif;
        cursor: pointer;
    }
    .import-theme-option:hover, .import-theme-option.selected{
        background: yellow;
        color: black;
    }
    .import-theme-submit-wrap{
        padding: 20px 5px;
        text-align: left;
    }
    .import-theme-submit{
        font-size: 20px;
        padding: 5px;
        
    }
    .import-theme-submit.ready{
        cursor: pointer;
    }
</style>