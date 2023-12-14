<?php foreach($this->registered_scripts['foot'] as $label=>$script): ?>
    <?php if($script['type'] == 'js'): ?>
        <script type="text/javascript" src="<?= $script['ref'] ?>"></script>
    <?php endif; ?>

    <?php if($script['type'] == 'style'): ?>
        <link rel="stylesheet" href="<?= str_replace("{{css_dir}}",$this->data['directions']['css_dir'],$script['ref']); ?>" type="text/css" />
    <?php endif; ?>

    <?php if($script['type'] == 'str'): ?>
        <?= $script['ref']; ?>
    <?php endif; ?>

<?php endforeach; ?>