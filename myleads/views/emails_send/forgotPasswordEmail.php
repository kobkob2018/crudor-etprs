<div style="direction:rtl;">
	שלום <?= $this->data['forgot_password_user']['full_name']; ?>.<br/>
	
	<br/><br/>
	לבקשתך, נשלח אליך לינק לאיפוס סיסמה.
	לצורך איפוס הסיסמה, יש ללחוץ על הלינק המצורף, ואז בדף שנפתח לבחור סיסמה חדשה.
	<br/>
	לאחר איפוס הסיסמה יש להכנס שוב למערכת עם שם המשתמש שלך והסיסמה שהחרת.

	<br/>
	<br/>

	שם המשתמש שלך: <br/>
	<?= $this->data['forgot_password_user']['username']; ?>

	<br/>
	<br/>
	להלן לינק לאיפוס סיסמה:
	<br/>
	<a href="<?= outer_url('userLogin/resetPassword/'); ?>?row_id=<?= $this->data['forgot_password_token']['row_id'] ?>&token=<?= $this->data['forgot_password_token']['token'] ?>">לחץ כאן לאיפוס סיסמה</a> 

</div>
