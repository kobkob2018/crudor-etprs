<?php if($this->data['top_menu_items']): ?>
    <ul class="item-group top-menu">
        
        
            <?php foreach($this->data['top_menu_items'] as $menu_item): ?>
                <li class="bar-item <?= $menu_item['css_class'] ?>" id = "menu_item_<?= $menu_item['id'] ?>">
                    <?php if($menu_item['link_type'] != '2'): ?>
                        <a href="<?= $menu_item['final_url'] ?>" target="<?= $menu_item['target'] ?>" title="<?= $menu_item['label'] ?>" class="main-spn main-a a-link"><?= $menu_item['label'] ?></a>
                    <?php else: ?>
                        <a href="javascript://" onClick="show_sub_menu(this)" data-item_id = "menu_item_<?= $menu_item['id'] ?>" class='group-menu-item main-spn main-b'>
                            <?= $menu_item['label'] ?>
                        </a>
                    <?php endif; ?>
                    <?php if(isset($menu_item['children'])): ?>
                        <a href="javascript://" class="top-menu-carret" onClick="show_sub_menu(this)" data-item_id = "menu_item_<?= $menu_item['id'] ?>">
                            <span class="fa fa-chevron-down"></span>
                        </a>
                        <ul class="sub-menu" id = "sub_menu_<?= $menu_item['id'] ?>">
                            <?php foreach($menu_item['children'] as $sub_item): ?>
                                <li class="sub-item <?= $sub_item['css_class'] ?>" id = "sub_item_<?= $sub_item['id'] ?>">
                                    <a href="<?= $sub_item['final_url'] ?>" target="<?= $sub_item['target'] ?>" title="<?= $sub_item['label'] ?>" class="sub-spn sub-a a-link"><?= $sub_item['label'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        
    </ul>
<?php endif; ?>
<div class="top-search">
    <form action = "<?= inner_url('search/result/') ?>" method="POST" >
        <input type="text" name="user_search" placeholder="חיפוש"/>
        <button type="submit">
            <span class="fa fa-search"></span>
        </button>
    </form> 
</div>