
<?php if($this->data['bottom_menu_items']): ?>
    <ul class="item-group bottom-menu">
        
        
            <?php foreach($this->data['bottom_menu_items'] as $menu_item): ?>
                <li class="bar-item <?= $menu_item['css_class'] ?>" id = "menu_item_<?= $menu_item['id'] ?>">
                    <b class='main-menu-ietm-wrap'>

                        <?php if($menu_item['link_type'] != '2'): ?>
                            <a href="<?= $menu_item['final_url'] ?>" target="<?= $menu_item['target'] ?>" title="<?= $menu_item['label'] ?>" class="main-spn main-a a-link"><?= $menu_item['label'] ?></a>
                        <?php else: ?>
                            
                                <?= $menu_item['label'] ?>
                            
                        <?php endif; ?>
                    </b>
                    <?php if(isset($menu_item['children'])): ?>
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