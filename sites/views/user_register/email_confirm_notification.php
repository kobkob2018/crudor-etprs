<div style="direction:rtl;">

	<h2><?= __tr("Hello $1", array($info['full_name'])) ?>.<br/></h2>
	<h3><?= __tr("Your registration to the website $1 succided",array($info['site']['title'])) ?></h3>
	<br/><br/>
	<?= __tr("Click the following link to approve your email") ?>:
    <a href="<?= $info['confirm_url'] ?>" title="<?= __tr("Click here for email confirmation") ?>"><?= $info['confirm_url'] ?></a>

    <?php $this->include_view("emails_send/email_footer.php"); ?>

    <br/><br/>
</div>
