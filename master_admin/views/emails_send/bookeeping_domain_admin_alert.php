<div style="direction:rtl;">
    <h2>התראה למנהל לפני תפוגת דומיין של לקוח: <?= $info['user']['biz_name'] ?><br/> <?= $info['user']['full_name'] ?></h2>
    <div style="padding:10px; background:#ffbbbb;">
        <h4>בעוד <?= $info['hosting_days'] ?> ימים יפוג תוקף הדומיין.</h4>
        <p>
            <b>
                פירוט תשלום :
            </b>
            <b>עלות הדומיין לשנה</b> <?= $info['domainPrice'] ?> ₪ + מע"מ
            <b>סה"כ תשלום</b> <?= $info['domainPriceTotal'] ?> ₪ כולל מע"מ
        </p>
        <p>
            *** עלות החזרת אתר שהורד על ידי המערכת האוטומטית מהאינטרנט – 250 ₪ + מע"מ
        </p>
    </div>
    <?php $this->include_view("emails_send/email_footer.php"); ?>

</div>