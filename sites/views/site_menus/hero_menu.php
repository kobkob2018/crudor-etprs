
<?php if($this->data['hero_menu_items']): ?>
    <ul class="item-group hero-menu">
        
        
            <?php foreach($this->data['hero_menu_items'] as $menu_item): ?>
                <li class="bar-item <?= $menu_item['css_class'] ?>" id = "menu_item_<?= $menu_item['id'] ?>">
                    
                    <b class='main-menu-ietm-wrap'>

                        <?php if($menu_item['link_type'] != '2'): ?>
                            <a href="<?= $menu_item['final_url'] ?>" <?php if($menu_item['target']): ?> target="_BLANK" <?php endif; ?> title="<?= $menu_item['label'] ?>" class="main-spn main-a a-link">
                                <div class="hero-a-icon"></div> 
                                <?= $menu_item['label'] ?>
                            </a>
                        <?php else: ?>
                            <b>
                                <?= $menu_item['label'] ?>
                            </b>
                            
                        <?php endif; ?>
                    </b>
                </li>
            <?php endforeach; ?>
        
    </ul>
<?php endif; ?>