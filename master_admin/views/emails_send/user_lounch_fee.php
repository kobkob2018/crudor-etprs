שלום רב, <?= $info['full_name'] ?><br><br> 
קיבלת הודעת תשלום עבור: '.<?= nl2br($info['details']) ?>
 על סך <?= $info['price'] ?> ש"ח כולל מע"מ.<br><br>
<a href="<?= get_config('base_url') ?>/myleads/payments/lounch_fee_token/?row_id=<?= $info['row_id'] ?>&user=<?= $info['user_id'] ?>&token=<?= $info['token'] ?>"><u>לחץ כאן</u></a> בתשלום מיידי בכרטיס אשראי.<br><br>
בברכה,<br>
איי אל ביז קידום עסקים באינטרנט בע"מ