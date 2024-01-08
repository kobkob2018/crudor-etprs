<div style="<?= $this->data['directions']['direction'] ?>">

	<h2><?= __tr("Hello $1", array($info['user']['info']['full_name'])) ?>.<br/></h2>
	<h3><?= __tr("A quote request from $1 has arrived", array($info['site']['domain'])) ?></h3>
	<br/><br/>
	
    <?= __tr("Category") ?>: <?= $info['lead']['cat_tree_name'] ?> <br/>
    <?= __tr("Name") ?>: <?= $info['lead']['full_name'] ?> <br/>
    <?= __tr("Phone") ?>: <?= $info['lead']['phone'] ?> <br/>
    <?= __tr("Email") ?>: <?= $info['lead']['email'] ?> <br/>
    <?= __tr("City") ?>: <?= $info['lead']['city_name'] ?> <br/>
    <?= __tr("Notes") ?>: <?= $info['lead']['note'] ?> <br/>
    <br/><br/>
	<a href="<?= $info['auth_link'] ?>"><?= __tr("Click here to view in Myleads") ?></a> 
    <?php if($info['lead']['alert_leads_credit']): ?>
        <b><?= __tr("To your info") ?></b><br/>
        <div style="color:red;">
            <?= __tr("This request is marked with '*' because your package is expired") ?>.
            <br/>
            <?= __tr("Please reffer to customer service") ?>.
        </div>
    <?php endif; ?>


    <?php $this->include_view("emails_send/email_footer.php"); ?>


    <br/><br/>
</div>
