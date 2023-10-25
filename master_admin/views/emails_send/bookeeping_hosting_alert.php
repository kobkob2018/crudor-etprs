<div style="direction:rtl;">
    <div style="padding:10px; background:#ffbbbb;">
        <h4>בעוד <?= $info['hosting_days'] ?> ימים יפוג תוקף האתר.</h4>
        <p>
            כדי שהמערכת האוטומטית לא תוריד את אתרך מהאינטרנט בתאריך הנ"ל יש לבצע תשלום
        </p>
        <p>
            <b>
                פירוט תשלום :
            </b>
            <b>עלות אחסון חודשית</b> <?= $info['hostPriceMon'] ?> ₪ + מע"מ
            <b>סה"כ תשלום</b> <?= $info['hostPriceYear'] ?> ₪ כולל מע"מ
        </p>
            

        <h4>
            ניתן לחדש את אחסון האתר באחת מהדרכים הבאות :
        </h4>
        <ol>
            <li>
                באמצעות כרטיס אשראי, עד 12 תשלומים ללא רבית והצמדה, 
                <a href="<?= get_config('base_url') ?>/myleads/bookkeeping/view/">                       
                    לחץ כאן לתשלום בכרטיס אשראי
                </a>
                    * 

            </li>
            <li>
                הפקדה לחשבון בנק ע"ש איי. אל. ביז קידום עסקים באינטרנט בע"מ, בנק הפועלים, סניף 160 , מספר חשבון 71732
            </li>
        </ol>
        <p>
            * לאחר התשלום ישלח לאימייל שיצויין בטופס, חשבונית מס קבלה מקורית עם חתימה דגיטלית
        </p>
        <p>
            אם שולם באמצעות הפקדת כסף לחשבון הבנק, נא להודיע לשירות לקוחות
        </p>
        <p>
            *** עלות החזרת אתר שהורד על ידי המערכת האוטומטית מהאינטרנט – 250 ₪ + מע"מ
        </p>
    </div>
    <?php $this->include_view("emails_send/email_footer.php"); ?>

</div>