<div class="content-helper hidden">
    <div class="helper-close">
        <a href="javascript://" class="content-helper-close">X</a>
    </div>
    <h3>
        תגיות עיצוב - אפשרויות(יש להוסיף עם רווחים)
    </h3>
    <div class="css-classes-helper">

    </div>

    <h3>
        קודים מקוצרים
    </h3>
    <div class="css-classes-helper">
        <div class = "helper-code">
            רשימת כתבות לדף הבית <br/>
            <input class="code-input" type="text" value="{{% mod | homepage | pages_list %}}" />
            
        </div>

        <div class = "helper-code">
             רשימת מוצרים לדף הבית <br/>
            
            <input class="code-input" type="text" value="{{% mod | homepage | product_list | limit:4 %}}" />
            <br/>
            ניתן לשנות את המספר 4 כדי לשנות את מספר המוצרים שיופיעו
            <br/>
            כדי לשים את זה בעמודה השמאלית של הדף יש להוסיף תגית עיצוב<br/>
            <input class="code-input-inline" type="text" value="go-left-bottom" />
        </div>

    </div>
</div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded",()=>{
        document.querySelectorAll(".content-helper-close").forEach(closeButton=>{
            closeButton.addEventListener("click",function(){
                const helperEl = document.querySelector(".content-helper");
                if(!helperEl){
                    return;
                }
                helperEl.classList.add("hidden");
            });
        });
        document.querySelectorAll(".content-helper-open").forEach(closeButton=>{
            closeButton.addEventListener("click",function(){
                const helperEl = document.querySelector(".content-helper");
                if(!helperEl){
                    return;
                }
                helperEl.classList.remove("hidden");
            });
        });
    });
</script>