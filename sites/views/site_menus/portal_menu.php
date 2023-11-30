<ul id="right_menu" class="item-group right-menu">
    
    <?php if($this->data['portal_menu_items']): ?>
        <?php foreach($this->data['portal_menu_items'] as $menu_item): ?>
            <li class="bar-item <?= $menu_item['css_class'] ?>">
                <?php if($menu_item['link_type'] != '2'): ?>
                    <a href="<?= $menu_item['final_url'] ?>" <?php if($menu_item['target']): ?> target="_BLANK" <?php endif; ?> title="<?= $menu_item['label'] ?>" class="a-link color-button"><?= $menu_item['label'] ?></a>
                <?php else: ?>
                    <b class='group-menu-item'><?= $menu_item['label'] ?></b>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>