<?php if(session__isset('quotes_queue')): ?>
    <div class="focus-box sub-focus">
        <h4>קיים תור של הצעות מחיר לביצוע פעולות (<?= count(session__get('quotes_queue')) ?> פריטים): </h4>
        <ul>
            <?php if(isset($info['enable_assign_user']) && $info['enable_assign_user']): ?>
                <li>
                    <span class="red">*</span>
                    <a href="<?= inner_url('quotes/assign_queue_to_user/') ?>">לחץ כאן כדי לשייך את הצעות המחיר ללקוח זה</a>
                </li>
            <?php else: ?>
            <li>
                <span class="red">*</span>
                על מנת לשייך את הצעות המחיר שבתור ללקוח - יש לגשת את הצעות המחיר של הלקוח ושם ללחוץ על האפשרות: "לחץ כאן כדי לשייך את הצעות המחיר ללקוח זה"
            </li>
            <?php endif; ?>
        </ul>

        <div class="focus-box">
            <a href = "<?= inner_url('quotes/reset_queue/') ?>">לחץ כאן למחיקת התור של הצעות מחיר</a>
        </div>
    </div>
<?php endif; ?>