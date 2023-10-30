<div style="direction:rtl;">

	<h2>שלום <?= $info['full_name']; ?>.<br/></h2>
	<h3>הרשמתך לאתר <?= $info['site']['title'] ?> בוצעה בהצלחה</h3>
	<br/><br/>
	אנא לחץ על הלינק הבא על מנת לאשר את כתובת המייל שלך:
    <a href="<?= $info['confirm_url'] ?>" title="לחץ כאן לאישור כתובת המייל"><?= $info['confirm_url'] ?></a>

    <?php $this->include_view("emails_send/email_footer.php"); ?>

    <br/><br/>
</div>
