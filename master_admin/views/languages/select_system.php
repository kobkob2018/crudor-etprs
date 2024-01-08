<h2>ניהול תרגומים - יש לבחור מערכת לתרגום:</h2>
<ul class="select_system">
    <?php foreach($info['system_options'] as $system_id=>$system_label): ?>
        <li>
            <a href="<?= inner_url("languages/list?system_id=".$system_id) ?>"><?= $system_label ?></a>
        </li>
    <?php endforeach; ?>
</ul>