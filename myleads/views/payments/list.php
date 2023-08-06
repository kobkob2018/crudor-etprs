<h2>תשלומים אחרונים</h2>
<?php if($this->data['pay_log_list']): ?>
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
                    <?php foreach($this->data['pay_log_list'] as $pay_log): ?>
                        <tr>
                            <td data-title='#'><?= $pay_log['id'] ?></td>
                            <td data-title='תאור העסקה'><?= $pay_log['details'] ?></td>
                            <td data-title='תאריך'><?= $pay_log['pay_date_heb'] ?></td>
                            <td data-title='סכום'><?= $pay_log['sum_total'] ?></td>
                            <td><a href='payments/get_invoice/?row_id=<?= $pay_log['id'] ?>' class='right_menu'>הצג חשבונית</a></td>		
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <h3>לא קיימים תשלומים</h3>
<?php endif; ?>

<?php if($this->data['old_user_payments']): ?>
    <h2>תשלומים ישנים</h2>
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
                    <?php foreach($this->data['old_user_payments'] as $pay_log): ?>
                        <tr>
                            <td data-title='#'><?= $pay_log['id'] ?></td>
                            <td data-title='תאור העסקה'><?= $pay_log['description'] ?></td>
                            <td data-title='תאריך'><?= $pay_log['pay_date_heb'] ?></td>
                            <td data-title='סכום'><?= $pay_log['sum_total'] ?></td>
                            <td><a href='payments/get_invoice/?row_id=<?= $pay_log['id'] ?>' class='right_menu'>הצג חשבונית</a></td>		
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>