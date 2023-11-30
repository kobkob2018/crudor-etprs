<?php if($info['product_list']): ?>
    <div class="midpage-title-wrap">
        <h3 class="color-title med-title">קטלוג מוצרים</h3>
    </div>
    <div class="product-list center-flex-row flex-wrap box-list">
        <?php foreach($info['product_list'] as $product): ?>
            <div class="product-box list-box">
                <div class="box-title big-title">
                    <?= $product['label'] ?>
                </div>

                <div class="box-content">
                    <?php if($product['image']): ?>
                        <div class="box-image">
                            <a href="<?= inner_url("products/view/?p=".$product['id']) ?>" title="<?= $product['label'] ?>">
                                <img src="<?= $this->file_url_of('product_image',$product['image']) ?>" alt="<?= $product['label'] ?>" />
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="box-text">
                        <?= nl2br($product['description']) ?>
                    </div>
                    <?php if($product['price']): ?>
                        <div class="box-price color-b">
                            <?= $product['price'] ?> ש"ח 
                        </div> 
                    <?php endif; ?>
                    <div class="box-go-to">
                        <a href="<?= inner_url("products/view/?p=".$product['id']) ?>" title="<?= $product['label'] ?>">
                            למידע נוסף
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>