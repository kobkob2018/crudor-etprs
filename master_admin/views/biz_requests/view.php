<div class="eject-box">
    <a href="<?= $this->eject_url() ?>">חזרה לרשימה</a>
</div>

<h2>שליחת ליד ידנית ללקוחות</h2>

<div class="request-list flex-table">
    <div class="request-list-th table-th row">
        <div class="col">
            תאריך
        </div>
        <div class="col">
            שם מלא
        </div>
        <div class="col">
            טלפון
        </div>
        <div class="col">
            אימייל
        </div>
        <div class="col">
            הערות
        </div>
        <div class="col">
            IP
        </div>
        <div class="col">
            עיר
        </div>
        <div class="col">
            קטגוריה
        </div>
        <div class="col">
            באנר
        </div>
        <div class="col">
            סטטוס עבודה
        </div>
    </div>
    <?php $biz_request = $info['biz_request']; ?>
    <div class="request-list-tr table-tr row  campaign_type-0<?= $biz_request['campaign_type'] ?>">
        <div class="col">
            <?= hebdt($biz_request['date_in'],"d-m-Y") ?><br/>
            <?= hebdt($biz_request['date_in'],"H:i") ?><br/>

            <?php if($biz_request['is_mobile'] == '1'): ?>
                <div class="mark mark-is-mobile">m</div>
            <?php endif; ?>
            <?php if($biz_request['aff_id'] != ''): ?>
                <div class="mark mark-affiliate">a</div>
            <?php endif; ?>
            <?php if($biz_request['recivers'] == '0'): ?>
                <div class="mark mark-not-sent">X</div>
            <?php endif; ?>
        </div>
        <div class="col">
            <?= $biz_request['full_name'] ?>
        </div>
        <div class="col">
            <?= $biz_request['phone'] ?>
        </div>
        <div class="col">
            <?= $biz_request['email'] ?>
        </div>
        <div class="col">
            <?= $biz_request['note'] ?>
            <br/>
            <?= $biz_request['extra_info'] ?>
        </div>
        <div class="col">
            <?= $biz_request['ip'] ?>
        </div>
        <div class="col">
            <?= $biz_request['city_name'] ?>
        </div>
        <div class="col">
            <?php foreach($biz_request['cat_tree'] as $cat): ?>
                <?= $cat['label'] ?><br/>
            <?php endforeach; ?>
        </div>
        <div class="col">
            <?= $biz_request['banner_name'] ?>
        </div>
        <div class="col">
            <select class="auto-change-status" data-row_id="<?= $biz_request['id'] ?>">
                <?php foreach($info['status_options'] as $option_key=>$option): ?>
                    <?php if($option_key != 'all'): ?>
                        <?php $selected_str = $option['value'] == $biz_request['status']? "selected" : ""; ?>
                        <option value="<?= $option['value'] ?>" <?= $selected_str ?> ><?= $option['label'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>       
    
</div>

<script type="text/javascript">
    document.querySelectorAll(".auto-change-status").forEach(selectEl=>{
        
        selectEl.addEventListener("change",function(event){
            const select = event.target;
            const rowId = select.dataset.row_id;
            const status = select.value;
            const url = "<?= inner_url('biz_requests/status_update/?row_id=') ?>" + rowId + "&status="+status;
           
            window.location.href = url;
        })
    });
</script>

<div class="users-send-form-wrap focus-box">
    <h3>התאמת נותני שירות לשליחה</h3>
    <form  class="users-send-form" action = "<?= inner_url('biz_requests/send_lead_to_users/') ?>?row_id=<?= $biz_request['id'] ?>" method = "POST" >
        <div class="users-send-list flex-table">
            <div class="table-th row">
                <div class="col col-first col-tiny">#</div>
                <div class="col col-tiny">שלח</div>
                <div class="col">שם העסק <br/> סיום השירות</div>
                <div class="col">עיר</div>
                <div class="col">
                    שליחות בחודש אחרון
                    <br/>
                    יתרת לידים
                </div>
                <div class="col">התאמה בקטגוריה</div>
                <div class="col">התאמה בעיר</div>
                <div class="col">שליחה אוטומטית</div>
                <div class="col"> מצב שליחה</div>
                <div class="col">התאמה סופית</div>
                
            </div>
            <?php foreach($info['users_fit'] as $user): ?>
                <div class="table-tr row user_isactive_0<?= $user['is_active'] ?>">
                    <div class="col col-first col-tiny">
                        <?= $user['id'] ?>
                    </div>
                    <div class="col col-first col-tiny">
                        <?php if(!$user['lead_info']): ?> 
                            <input type="checkbox" class="input-checkbox" value="1" name="send_to_users[<?= $user['info']['user']['id'] ?>]" />
                        <?php endif; ?>
                    </div>

                    <div class="col">
                        <a target="_BLANK" href="<?= inner_url("users/edit/?&row_id=") ?><?= $user['info']['user']['id'] ?>">
                            <?= $user['info']['user']['biz_name'] ?>
                        </a>
                        <br/>
                        <?= hebdt($user['info']['lead_settings']['end_date'],"d-m-Y") ?>
                        <?php if(!$user['is_active']): ?>
                            <br/>
                            <b class="red">
                                <?php if(!$user['info']['lead_settings']['active']): ?>
                                    מסומן כלא פעיל
                                <?php else: ?>
                                    תאריך מחוץ לתוקף
                                <?php endif; ?>

                            </b>
                        <?php endif; ?>
                    </div>
                    <div class="col"><?= $user['info']['user']['city_name'] ?></div>
                    <div class="col">
                    <?= $user['monthly_sent_leads'] ?>
                    <br/>    
                    <?= $user['info']['lead_settings']['lead_credit'] ?>
                    </div>
                    <div class="col">
                        <?php if($user['fit_cat'] == '1'): ?>
                            <b class="green">כן</b>
                        <?php else: ?>
                            <b class="red">לא</b>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <?php if($user['fit_city'] == '1'): ?>
                            <b class="green">כן</b>
                        <?php else: ?>
                            <b class="red">לא</b>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <?php if($user['info']['lead_settings']['auto_send'] == '1'): ?>
                            <b class="green">כן</b>
                        <?php else: ?>
                            <b class="red">לא</b>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <?php if($user['lead_info']): ?>
                            <?php if( $user['lead_info']['send_state'] == '0'): ?>
                                <b class="red">בהשהייה</b>
                            <?php else: ?>
                                רגיל
                            <?php endif; ?>
                            <br/>
                            <a target="_BLANK" href="<?= inner_url("refund_requests/add_request/?lead_id=") ?><?= $user['lead_info']['id'] ?>">
                                בקש זיכוי
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <?php if($user['final_fit'] == '1'): ?>
                            <b class="green">כן</b>
                        <?php else: ?>
                            <b class="red">לא</b>
                        <?php endif; ?>
                    </div>
                    
                </div>                
            <?php endforeach; ?>
        </div>
        <div class="form-group submit-form-group">
            <input type="submit"  class="submit-btn"  value="שליחה לנבחרים" />
        </div>
    </form>
</div>