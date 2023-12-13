<section class="text-with-image" data-image-position="<?= $component['image_position']; ?>">

    <div class="image<?= !empty($component['image_class']) ? " ".$component['image_class'] : ''; ?>">
        <?php
        echo get_picture_tag([
            ['media' => '(min-width: 768px)', 'srcset' => $component['desktop_image']],
            ['media' => '(max-width: 768px)', 'srcset' => $component['mobile_image']],
        ]);
        ?>
        <?php if(!empty($component['caption'])): ?>
            <figcaption><?= $component['caption']; ?></figcaption>
        <?php endif; ?>
    </div>

    <div class="content">

        <?= wysiwyg_format($component['content']); ?>
        
        <?php if(!empty($component['cta']) || !empty($component['links'])): ?>
        <div class="call-to-action">
            <?= wysiwyg_format($component['cta']); ?>
            <div class="buttons left-aligned">
            <?php foreach($component['links'] as $link): ?>
                <a href="<?= $link['url']; ?>" class="<?= $link['class']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : '' ?>><?= $link['text']; ?></a>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>