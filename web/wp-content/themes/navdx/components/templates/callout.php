<section class="container">
<?php

    $calloutDefaults = array(
        'column_text'  => '',
        'cta_text'  => '',
        'cta_link'  => ''
    );
?>
    <?php
        if(!empty($component['column_icon'])){
            img_from_id($component['column_icon'], 'icon');
        }
    ?>
    <div class="content"><?= wysiwyg_format($component['column_text']); ?></div>
<?php if(!empty($component['links'])): ?>
    <div class="buttons">
        <?php foreach($component['links'] as $link): ?>
        <a href="<?= $link['url']; ?>" class="<?= $link['button_class']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : ''; ?>><?= $link['text']; ?></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</section>