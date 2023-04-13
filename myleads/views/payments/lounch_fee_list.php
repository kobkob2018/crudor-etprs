<h2>בקשות לתשלום</h2>
<?php if($this->data['fee_list']): ?>

    <?php foreach($this->data['fee_list'] as $fee): ?>
        <hr style='border-top-color: #6b6b96;'/>
        <div class='billing-list-item'>			
            <div><?= $fee['details'] ?>
            <br> עלות: <b><?= $fee['price'] ?> כולל מע"מ.</b>
            <br>
                נותרו עוד <b><?= $fee['days_left_to_pay'] ?></b> ימים לתשלום החוב.<br>
                <br>
                ניתן לשלם באמצעות כרטיס אשראי, עד <?= $fee['tash'] ?> תשלומים ללא רבית והצמדה, 
                <a href='<?= inner_url("payments/lounch_fee/") ?>?row_id=<?= $fee['id'] ?>' class='maintext' style='color: blue;'>
                    <b>לחץ כאן לתשלום</b>
                </a> &nbsp;&nbsp; <a href='<?= inner_url("payments/lounch_fee/") ?>?row_id=<?= $fee['id'] ?>'><img src='style/image/paypage_61.gif'></a><br>חשבונית מס קבלה תישלח אליכם לכתובת האימייל שתציינו לאחר סיום התשלום
                <br><br>
                <b>כתובת עבור העברה בנקאית:</b><br>
                חשבון 71732 , בנק הפועלים, סניף 160, ח.פ 514351097<br><br>
                
                <b>ניתן לשלם באמצעות אפליקצית ביט:</b>  052-5572555<br>
            </div>						
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <h3>לא קיימים חיובים</h3>
<?php endif; ?>