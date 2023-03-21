<div style="direction:rtl;">

	<h2>שלום <?= $info['user']['info']['full_name']; ?>.<br/></h2>
	<h3>התקבלה בקשה להצעת מחיר מהאתר <?= $info['site']['domain'] ?></h3>
	<br/><br/>
	
    קטגוריה: <?= $info['lead']['cat_tree_name'] ?> <br/>
    שם: <?= $info['lead']['full_name'] ?> <br/>
    טלפון: <?= $info['lead']['phone'] ?> <br/>
    אימייל: <?= $info['lead']['email'] ?> <br/>
    עיר: <?= $info['lead']['city_name'] ?> <br/>
    הערות\בקשות: <?= $info['lead']['note'] ?> <br/>
    <br/><br/>
	<a href="<?= $info['auth_link'] ?>">לחץ כאן לצפייה במערכת הלידים</a> 
    <?php if($info['lead']['alert_leads_credit']): ?>
        <b>שים לב</b><br/>
        <div style="color:red;">
            פנייה זו מסומנת בכוכביות מכיוון שהסתיימה לך חבילת הלידים.
            <br/>
            אנא פנה לשירות הלקוחות.
        </div>
    <?php endif; ?>


    <?php $this->include_view("emails_send/email_footer.php"); ?>


    <br/><br/>
</div>
