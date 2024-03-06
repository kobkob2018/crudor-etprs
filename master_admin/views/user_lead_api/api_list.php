
<?php $this->include_view("users/header.php"); ?>
<div class="focus-box">
    


<h2>API לשליחה לאחר כניסת ליד אל הלקוח</h2>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col  col-first">url</div>
            <div class="col"></div>
        </div>

        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="createApiSend" />
            <input type="hidden" name="api_id" value="new" />
            <div class="col col-first">                   
                <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url') ?>" />
            </div>
           
            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>





    <?php if(!empty($this->data['api_list'])): ?>
        <h4>רשימת API</h4>
        <div class="items-table flex-table">
            <div class="table-th row">
                <div class="col  col-first">כתובת</div>
                <div class="col"></div>
                <div class="col"></div>
            </div>
            <?php foreach($this->data['api_list'] as $api): ?>
                <form  class="table-tr row" action = "" method = "POST" >
                    <input type="hidden" name="sendAction" value="apiListUpdateSend" />
                    <input type="hidden" name="api_id" value="<?= $api['id'] ?>" /> 
                    <div class="col col-first">
                        <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url',$api['form_identifier']) ?>" />
                    </div>
                   
                    <div class="col"><input type="submit" value="שמור" /></div>
                    <div class="col">
                        <a class = 'delete-item-x' href="<?= inner_url('user_lead_api/delete_api/') ?>?api_id=<?= $api['id'] ?>&user_id=<?= $this->data['user_info']['id'] ?>">
                            X
                        </a>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h4>רשימה ריקה</h4>
    <?php endif; ?>
</div>

