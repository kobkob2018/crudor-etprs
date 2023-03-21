<div style="direction:rtl;">
	שלום <?= $this->data['forgot_password_user']['full_name']; ?>.<br/>
	
	<br/><br/>
	לבקשתך, נשלח אליך לינק לאיפוס סיסמה:
	<a href="<?= outer_url('userLogin/resetPassword/'); ?>?row_id=<?= $this->data['forgot_password_token']['row_id'] ?>&token=<?= $this->data['forgot_password_token']['token'] ?>">לחץ כאן לאיפוס סיסמה</a> 

</div>
