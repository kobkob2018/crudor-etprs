<a class="order-by-a orderby-0<?= $this->session_order_by == $info['order_by'] ?> orderbydesc-0<?= $this->session_order_by == $info['order_by'].' desc' ?>" 
    href="<?= current_url(array('order_by'=>$info['order_by'],'desc'=>'1')) ?>">
    <span class="order-by-arraow order-by-arraow-up">
        <i class="fa fa-sort-down"></i>  
    </span>
    <span class="order-by-arraow order-by-arraow-down">
        <i class="fa fa-sort-desc"></i>
    </span>
    <?= $info['label'] ?>
</a>