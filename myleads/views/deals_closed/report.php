<h3>
    תשלומים על עסקאות סגורות
</h3>


<div id='listTable_wrap'>
    <div id='responsive-tables'>
        <table border='1' style='border-collapse: collapse;' width='100%' cellpadding='15' borderc>
            <thead>
                <tr>
                    <th>#</th>
                    <th>תאור העסקה</th>
                    <th>תאריך</th>
                    <th>סכום</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($info['payments'] as $payment): ?>
                        <tr>
                            <td data-title='#'><?= $payment['id'] ?></td>
                            <td data-title='תאור העסקה'><?= $payment['description'] ?></td>
                            <td data-title='תאריך'><?= $payment['pay_time'] ?></td>
                            <td data-title='סכום'><?= $payment['amount'] ?></td>
                            <td><a href='deals_closed_payments/delete/?row_id=<?= $payment['id'] ?>' class='right_menu'>מחק</a></td>		
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php if(empty($info['payments'])): ?>
    <tr><td colspan='5'>לא קיימים תשלומים</td></tr>
<?php endif; ?>