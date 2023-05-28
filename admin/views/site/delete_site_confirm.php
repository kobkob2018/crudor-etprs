<h3>מחיקת אתר לצמיתות: <?= $this->data['site_info']['title'] ?></h3>
דומיין: <?= $this->data['site_info']['domain'] ?>
<hr/>
<div class="messages err-messages">
        <h4>שים לב. בעת מחיקת האתריימחקו לצמיתות כל הרכיבית הבאים, הקשורים לאתר:</h4>
    <ul>
        <li class="message err-message">
            <b>
                דפים
            </b>
        </li>
        <li class="message err-message">
            <b>
                טפסים
            </b>
        </li>
        <li class="message err-message">
            <b>
                הגדרות עיצוב
            </b>
        </li>
        <li class="message err-message">
            <b>
                תפריטים
            </b>
        </li>
        </ul>
        <h4>אנא אשר סופית את מחיקת האתר</h4>
</div>
<div id="page_form_wrap" class="focus-box form-gen page-form">
<form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
    <div class='form-group' >
        רשום כאן את דומיין האתר ולחץ אישור
        <input type="text" name = "confirm_delete_final" value = "" />
    </div>
    <div class="form-group submit-form-group">
        <div class="form-group-st">
            <label id="submit_label"></label>
        </div>
        <div class="form-group-en">
            <input type="submit"  class="submit-btn"  value="אני מאשר סופית את מחיקת האתר" />
        </div>
    </div>
</form>
</div>