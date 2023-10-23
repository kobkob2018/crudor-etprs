<div style="direction:rtl;">
    <h2>(צד שלישי) התראה למנהל לפני תפוגת דומיין של לקוח: <?= $info['user']['biz_name'] ?><br/> <?= $info['user']['full_name'] ?></h2>
    <div style="padding:10px; background:#ffbbbb;">
        <h4>בעוד <?= $info['domain_days'] ?> ימים יפוג תוקף הדומיין.</h4>
        <p>
            *** זהו דומיין המוחזק ע"י צד שלישי. יש לדאוג שהלקוח מעדכן את תוקף הדומיין
        </p>
    </div>
    <?php $this->include_view("emails_send/email_footer.php"); ?>

</div>