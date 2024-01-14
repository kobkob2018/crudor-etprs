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

<?php if(isset($info['filter_form'])): ?>
    <?php $this->include_view('form_builder/filter_form.php',$info); ?>
<?php endif; ?>

<?php if(!empty($info['list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">תווית</div>
            <div class="col">פעיל</div>
            <div class="col">שיוך לתיקיות</div>
            <div class="col"></div>
        </div>
        <?php foreach($info['list'] as $item): ?>
           

                <form class="table-tr row"  action = "" method = "POST" >
                    <input type="hidden" name="sendAction" value="update_gallerySend" />
                    <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 
                    <div class="col longer-col">
                        <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                        <br/>
                        <a href = "<?= inner_url('gallery_images/list/') ?>?gallery_id=<?= $item['id'] ?>" title="בחירה">רשימת תמונות בגלריה</a>

                        <?php $this->include_view("portal_user\item_assign_label.php",array('item'=>$item,'global_info'=>$info)) ?>
                        <?php if($item['status'] == '5'): ?>
                            <br/>
                            <b class="red">ממתין לאישור מנהל</b>
                        <?php endif; ?>
                        <?php if($item['status'] == '9'): ?>
                            <br/>
                            <b class="red">הגלרייה לא אושרה</b>
                        <?php endif; ?>
                        <?php if($view->site_user_is('admin')): ?>
                            <br/>
                            <div class="focus-box">
                                שינוי סטטוס:
                                <br/>
                                <a class="set-status-1<?= $item['status'] ?>" href = "<?= inner_url('gallery_images/status_update/') ?>?row_id=<?= $item['id'] ?>&status=1" title="מאשר">מאשר</a>
                                | 
                                <a class="set-status-9<?= $item['status'] ?>" href = "<?= inner_url('gallery_images/status_update/') ?>?row_id=<?= $item['id'] ?>&status=9" title="לא מאשר">לא מאשר</a>
                                | 
                                <a class="set-status-5<?= $item['status'] ?>" href = "<?= inner_url('gallery_images/status_update/') ?>?row_id=<?= $item['id'] ?>&status=5" title="ממתין לאישור">ממתין לאישור</a>
                            </div>
                        <?php endif; ?>
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


<?php if($this->view->site_user_is('admin')): ?>
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
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= str_replace('"',"&quot;",$cat['label']) ?>" />
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
<?php endif; ?>


<?php $this->include_view("portal_user\items_assign_scripts.php",array('api_url'=>"gallery_images/ajax_assign_user/",'site_users'=>$info['site_users'],'global_info'=>$info)) ?>
