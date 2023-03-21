<h3>עריכת תפקיד משתמש: <?= $this->data['site_user_info']['user_name'] ?></h3>
<div class="eject-box">
	<a href="<?= inner_url("siteUsers/list/") ?>">חזרה לרשימה</a>
</div>
<hr/>
<div id="site_user_form_wrap" class="form-gen site-user-form">
    <?php $this->include_view('messages/formMessages.php'); ?>
	<form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
		<input type="hidden" name="sendAction" value="updateSend" />
        <input type="hidden" name="row_id" value="<?= $this->data['site_user_info']['id'] ?>" />	
		<div class="row-fluid">	
            
			<div class="form-group span3">
				<label for="row[roll]">תפקיד</label>

                <select  id='row_roll' name='row[roll]' class='form-select roll-row input_style' data-msg="יש לבחור תפקיד">
                    <?php foreach($this->get_select_options('roll') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
				
            </div>

			<div class="form-group span3">
				<label for="row[status]">סטטוס פעיל</label>

                <select  id='row_status' name='row[status]' class='form-select status-row input_style' data-msg="יש לבחור סטטוס">
                    <?php foreach($this->get_select_options('status') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
				
            </div>

		</div>
		<hr/>
		<div class="row-fluid">	
			<div class="form-group span3">
				<label id="submit_label"></label>
				<input type="submit"  class="submit-btn"  value="שליחה" />
			</div>

            <div class="form-group span3">
                <hr/>
				<a href="<?= inner_url('siteUsers/delete/') ?>?row_id=<?= $this->data['site_user_info']['id'] ?>"  class="delete-link" >מחיקה</a>
			</div>
		</div>
	</form>
</div>
