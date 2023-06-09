<div class="content-helper hidden">
    <div class="helper-close">
        <a href="javascript://" class="content-helper-close">X</a>
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
            
            <input class="code-input" type="text" value="{{% mod | products | cubes | limit:4 %}}" />
            <br/>
            ניתן לשנות את המספר 4 כדי לשנות את מספר המוצרים שיופיעו
            <br/>
            כדי לשים את זה בעמודה השמאלית של הדף יש להוסיף תגית עיצוב<br/>
            <input class="code-input-inline" type="text" value="go-left-bottom" />
        </div>


        <div class = "helper-code">
             מודול הצעות מחיר<br/>
            
            סגור: <input class="code-input" type="text" value="{{% mod | quotes | print_cat | cat_id:1 %}}" />
            <br/>
            פתוח: <input class="code-input" type="text" value="{{% mod | quotes | print_cat | cat_id:1 state:open %}}" />
            <br/>
            יש לשנות את המספר 1 למספר הקטגוריה הרצוייה
            <br/>
            על מנת למנוע מעטפת מיותרת, מומלץ לשלב עם תגית עיצוב<br/>
            <input class="code-input-inline" type="text" value="nowrap" />
        </div>

        
    </div>


    <h3>
        תגיות עיצוב - אפשרויות(יש להוסיף עם רווחים)
    </h3>
    <div class="css-classes-helper">
        <div class="helper-code">
            <input class="code-input-inline" type="text" value="c-clock" />
            <br/>
            קופסא עם פינות עגולות וצללית, בא כברירת מחדל כבלוק תוכן
        </div>

        <div class="helper-code">
            <input class="code-input-inline" type="text" value="nowrap" />
            <br/>
            אם רוצים לא לעטוף אלמנט בכלל. כשאין צורך בשום עיצוב זה חוסך שכבת HTML וגוגל אוהבים את זה
        </div>

        <div class="helper-code">
            <input class="code-input-inline" type="text" value="nowrap" />
            <br/>
            אם רוצים לא לעטוף אלמנט בכלל. כשאין צורך בשום עיצוב זה חוסך שכבת HTML וגוגל אוהבים את זה
        </div>
        <div class="helper-code">
            <input class="code-input-inline" type="text" value="kova" />
            <br/>
            קובייה אלגנטית עם כובע עגול
            <br/>
            <input class="code-input-inline" type="text" value="kova-b" />
            <br/>
            קובייה אלגנטית עם כובע עגול בצבע מודגש
            <b>ניתן לשלב עם תגיות h-r, h-l לתוצאה נהדרת</b>
        </div>
        <div class="helper-code">
            <input class="code-input-inline" type="text" value="h-r" />
            <br/>
            <input class="code-input-inline" type="text" value="h-l" />
            <br/>
            חלוקת קוביות לימין ושמאל
            <b>ניתן לשלב עם תגיות kova, kova-b לתוצאה נהדרת</b>
            <br/>
            <b>חשוב לשים אותן ביחד אחת ליד השנייה ולפי הסדר על מנת לשמור על רציפות הדף</b>
        </div>
        <div class="helper-code">
            <input class="code-input-inline" type="text" value="go-left" />
            <br/>
            <input class="code-input-inline" type="text" value="go-left-top" />
            <br/>
            <input class="code-input-inline" type="text" value="go-left-mid" />
            <br/>
            <input class="code-input-inline" type="text" value="go-left-bottom" />
            <br/>
            כך ניתן לשלוח בלוק לעמודה השמאלית, למעלה, באמצע או למטה בהתאמה
            <br/>
            
            <input class="code-input-inline" type="text" value="go-right" />
            <br/>
            שליחה לעמודה ימנית
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