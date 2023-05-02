

<?php if(isset($this->data['gallery'])): ?>
    <h1>
        <?= $this->data['gallery']['label'] ?>
    </h1>
<?php endif; ?>

<div class="gallery-cat-select">
    <form action = "<?= inner_url("gallery/view/") ?>" class="cat-select-form" name="cat_select_form" method="GET" >
        <select name="cat_id" class="cat-select select-cat-auto-submit">
            <?php foreach($this->data['cat_list'] as $cat): ?>
                <option name="cat_id" value="<?= $cat['id'] ?>" <?= $cat['selected_str'] ?> >
                    <?= $cat['label'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>


<div class="gallery-menu-list flex-row">
    <?php foreach($this->data['gallery_list'] as $gallery): ?>
        <div class="galery-menu-item">
            <a href = "<?= inner_url('gallery/view/?cat_id='.$this->data['cat_id'].'&gallery_id='.$gallery['id']) ?>" $title="<?= $gallery['label'] ?>"><?= $gallery['label'] ?></a>
        </div>

    <?php endforeach; ?>
</div>


<script type = "text/javascript">
    document.querySelectorAll(".select-cat-auto-submit").forEach(function(select){
        const select_form = select.closest(".cat-select-form");
        select.addEventListener('change',function(){

            select_form.submit();
        }
        );
        
    });
</script>
