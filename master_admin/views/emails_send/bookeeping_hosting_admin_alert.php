<div style="direction:rtl;">
    <h2>התראה למנהל לפני תפוגת אחסון של לקוח: <?= $info['user']['biz_name'] ?><br/> <?= $info['user']['full_name'] ?></h2>
    <div style="padding:10px; background:#ffbbbb;">
        <h4>בעוד <?= $info['hosting_days'] ?> ימים יפוג תוקף האתר.</h4>
        <p>
            <b>
                פירוט תשלום :
            </b>
            <b>עלות אחסון חודשית</b> <?= $info['hostPriceMon'] ?> ₪ + מע"מ
            <b>סה"כ תשלום</b> <?= $info['hostPriceYear'] ?> ₪ כולל מע"מ
        </p>
        <p>
            *** עלות החזרת אתר שהורד על ידי המערכת האוטומטית מהאינטרנט – 250 ₪ + מע"מ
        </p>
    </div>
    <?php $this->include_view("emails_send/email_footer.php"); ?>

</div>