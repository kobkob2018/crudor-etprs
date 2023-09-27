<h2>העלאת קבצים לאתר</h2>
<form action="<?= inner_url("files/upload/") ?>" method="POST" enctype="multipart/form-data">
    <h4>העלאת קובץ חדש</h4>
    <div class='form-group'>
        בחר קובץ להעלאה: 
        <input type="file" name="upload" class="form-input" value="" />
    </div>
    <div class='form-group'>
        <input type="submit" class="form-submit" value="שלח" />
    </div>
</form> 


<h3>הקבצים שהועלו לאתר</h3>
    <?php foreach($this->data['library_files'] as $file): ?>
    <div id="lib_file_wrap" class = "lib-file-wrap">
        <?= $file['name'] ?>
        <br/>
            <a href="<?= $file['url'] ?>" target="_BLANK" >צפה בקובץ</a>
            | 
        
        <a href="<?= inner_url("files/delete_upload/?upload=").$file['url'] ?>" onclick="return confirm('האם למחוק את הקובץ <?= $file['name'] ?>?')">מחיקה</a>
        <br/><br/>
    </div>
<?php endforeach; ?>
<?php if(empty($this->data['library_files'])): ?>
    <p>תיקיית ההעלאות ריקה</p>
<?php endif; ?>
