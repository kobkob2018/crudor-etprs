<?php if(isset($info['item']['test'])): ?>
OK OK OK 
<?php endif; ?>

<?php if(isset($info['item']['user_label'])): ?>
    <br/>
    <br/>
    <div class="user-id-select-wrap" data-user_id="<?= $info['item']['user_id'] ?>" data-item_id="<?= $info['item']['id'] ?>">
        <b>נוצר ע"י: </b>
        <a class="user-id-select-a" href="javascript://" onclick="select_item_user_id(this)">
            <?= $info['item']['user_label'] ?>
        </a>

        <div class="user-id-select-form hidden">

            <a class = 'close-list-x' href="javascript://" onclick="close_select_form(this)">
                X
            </a>
            <h4>החלפת משתמש</h4>
            <div class="user-list-finder-wrap">
                <input type="text" placeholder="הקלד שם משתמש" class="list-select" onkeyup="list_user_id_options(this)" />
                <div class="user-list-wrap">
                
                    <div class="user-list-results">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>