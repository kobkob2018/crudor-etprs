<div style="direction:rtl;">

	<h2>שלום <?= $info['user']['full_name']; ?>.<br/></h2>
	<h3>בהמשך לבקשתך לזיכוי ליד <?= $info['lead']['full_name'] ?> <?= $info['lead']['phone'] ?>:</h3>
	<?php if((!$info['denied']) && !$info['cancel_refund']): ?>
        הליד זוכה בהצלחה.
    <?php else: ?>
        הוחלט לא לזכות את הליד.
        <br/>
        הערות מנהל האתר:
        <br/>
        <?= $info['admin_comment'] ?>
    <?php endif; ?>

    <?php $this->include_view("emails_send/email_footer.php"); ?>


    <br/><br/>
</div>
