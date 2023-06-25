<h3>חיפוש ערך בכל האתרים</h3>

<hr/>
<div id="search_form_wrap" class="form-gen search-form">
    <form name="send_form" class="send-form form-validate" id="send_form" method="post" action="">
        <div class = "form-wrap">
            <div class='form-group <?= isset($build_field['css_class'])? $build_field['css_class']: "" ?>'>
                <div class="form-group-st">
                    <label for='search_term'>ערך החיפוש</label>
                </div>
                <div class='form-group-en'>
                    <input type='text' name='search_term' id='search_term' class='form-input' data-msg-required='*' value="<?= $info['search_term'] ?>"  />
                </div>	
                <div class="form-group-en">
                    <input type="submit"  class="submit-btn"  value="שליחה" />
                </div>
            </div>
        </div>
    </form>
</div>

<?php if($info['search_results']): ?>

    <h2>תוצאות חיפוש בכל האתרים לערך: "<?= $info['search_term'] ?>"</h2>
    <br/>
    <h2>תוצאות דפי תוכן</h2>
    <?php if(!empty($info['content_pages_list'])): ?>
        <?php foreach($info['content_pages_list'] as $item): ?>
            <div class="custom-list-item active-0<?= $item['active'] ?> visible-0<?= $item['visible'] ?>">

                <h3>
                    <a target="_BLANK" href="<?= $item['url'] ?>" title="<?= $item['title'] ?>">
                        <?= $item['title'] ?>
                    </a>
                </h3>
                
                <?= $item['description'] ?>
                
                <br/>
                אתר:                    
                <a target="_BLANK" href="<?= $item['site_info']['url'] ?>" title="<?= $item['site_info']['title'] ?>">
                    <?= $item['site_info']['domain'] ?>, <?= $item['site_info']['title'] ?>
                </a>
                <br/>
                סטטוס: 
                <?= $item['active'] == '0'? 'לא פעיל': 'פעיל' ?><?= $item['visible'] == '0'? ', לא נראה באתר': '' ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h4>לא נמצאו דפי תוכן</h4>
    <?php endif; ?>
    <br/>
    <h2>תוצאות דפי נחיתה</h2>
    <?php if(!empty($info['landing_pages_list'])): ?>
        <?php foreach($info['landing_pages_list'] as $item): ?>
            <div class="search-list-item">

                <h3>
                    <a target="_BLANK" href="<?= $item['url'] ?>" title="<?= $item['title'] ?>">
                        <?= $item['title'] ?>
                    </a>
                </h3>
                
                <?= $item['description'] ?>
                
                <br/>
                אתר:                    
                <a target="_BLANK" href="<?= $item['site_info']['url'] ?>" title="<?= $item['site_info']['title'] ?>">
                    <?= $item['site_info']['domain'] ?>, <?= $item['site_info']['title'] ?>
                </a>
                
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h4>לא נמצאו דפי נחיתה</h4>
    <?php endif; ?>
    <br/>
    <h2>תוצאות מוצרים</h2>
    <?php if(!empty($info['product_list'])): ?>
        <?php foreach($info['product_list'] as $item): ?>
            <div class="search-list-item">

                <h3>
                    <a target="_BLANK" href="<?= $item['url'] ?>" title="<?= $item['title'] ?>">
                        <?= $item['title'] ?>
                    </a>
                </h3>
                
                <?= $item['description'] ?>
                
                <br/>
                אתר:                    
                <a target="_BLANK" href="<?= $item['site_info']['url'] ?>" title="<?= $item['site_info']['title'] ?>">
                    <?= $item['site_info']['domain'] ?>, <?= $item['site_info']['title'] ?>
                </a>
                
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h4>לא נמצאו מוצרים</h4>
    <?php endif; ?>
    
<?php endif; ?>