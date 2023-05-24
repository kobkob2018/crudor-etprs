<h3>גלריות</h3>
<div class="watch-helper">
    <a target="_BLANK" href="<?= $this->data['work_on_site']['url'] ?>/gallery/view/">צפה באתר</a>
</div>
<h4>הוספת גלריה</h4>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">תווית</div>
        <div class="col">פעיל</div>
        <div class="col"></div>
    </div>

    <form  class="table-tr row" action = "" method = "POST" >
        <input type="hidden" name="sendAction" value="create_gallerySend" />
        <input type="hidden" name="db_row_id" value="new" />

        <div class="col longer-col">
            <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
        </div>
        <div class = "col">
            <select name='row[active]' class='form-select'>
                <option value="1"  selected>פעיל</option>
                <option value="0">לא פעיל</option>
            </select>
        </div>  
        <div class="col"><input type="submit" value="שמור" /></div>
    </form>


</div>

<hr/>


<h4>רשימת גלריות</h4>


<?php if(!empty($this->data['item_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">תווית</div>
            <div class="col">פעיל</div>
            <div class="col">שיוך לתיקיות</div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['item_list'] as $item): ?>
           

                <form class="table-tr row"  action = "" method = "POST" >
                    <input type="hidden" name="sendAction" value="update_gallerySend" />
                    <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 
                    <div class="col longer-col">
                        <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                        <br/>
                        <a href = "<?= inner_url('gallery_images/list/') ?>?gallery_id=<?= $item['id'] ?>" title="בחירה">רשימת תמונות בגלריה</a>
                    </div>
                    <div class = "col">
                        <select name='row[active]' class='form-select'>
                            <option value="1" <?= $item['active']? ' selected ': '' ?>>פעיל</option>
                            <option value="0" <?= $item['active']? '': ' selected ' ?>>לא פעיל</option>
                        </select>
                    </div>  
                    <div class = "col">
                        <?php foreach($item['cat_assign_checkbox_list'] as $checkbox): ?>
                            <input type="checkbox" name="assign[<?= $checkbox['id'] ?>]" value="1" <?= $checkbox['checked_str'] ?>/> <?= $checkbox['label'] ?>
                            <br/>
                        <?php endforeach; ?>
                    </div>

                    <div class="col"><input type="submit" value="שמור" /></div>
                    <div class="col">
                        <a class = 'delete-item-x' href="<?= inner_url('/gallery_images/delete_gallery/') ?>?gallery_id=<?= $item['id'] ?>">
                            X
                        </a>
                    </div>
                </form>
            
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין גלריות</h4>
<?php endif; ?>



<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"><h4>הוספת תיקייה</h4></div>
        <div class="col">פעיל</div>
        <div class="col"></div>
    </div>

    <form  class="table-tr row" action = "" method = "POST" >
        <input type="hidden" name="sendAction" value="create_gallery_catSend" />
        <input type="hidden" name="db_row_id" value="new" />

        <div class="col longer-col">
            <input type="text" class = 'table-input' name = 'row[label]' value = "" />
        </div>
        <div class = "col">
            <select name='row[active]' class='form-select'>
                <option value="1"  selected>פעיל</option>
                <option value="0">לא פעיל</option>
            </select>
        </div>  
        <div class="col"><input type="submit" value="שמור" /></div>
    </form>


</div>
<h4>רשימת התיקיות</h4>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"><h4>תוית</h4></div>
        <div class="col">פעיל</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['gallery_cats'] as $cat): ?>
        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="update_gallery_catSend" />
            <input type="hidden" name="db_row_id" value="<?= $cat['id'] ?>" />

            <div class="col longer-col">
                <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $cat['label'] ?>" />
            </div>
            <div class = "col">
                <select name='row[active]' class='form-select'>
                    <option value="1" <?= $cat['active']? ' selected ': '' ?>>פעיל</option>
                    <option value="0" <?= $cat['active']? '': ' selected ' ?>>לא פעיל</option>
                </select>
            </div>  
            <div class="col"><input type="submit" value="שמור" /></div>
            <div class="col">
                <a class = 'delete-item-x' href="<?= inner_url('/gallery_images/delete_gallery_cat/') ?>?cat_id=<?= $cat['id'] ?>">
                    X
                </a>
            </div>
        </form>
    <?php endforeach; ?>

</div>