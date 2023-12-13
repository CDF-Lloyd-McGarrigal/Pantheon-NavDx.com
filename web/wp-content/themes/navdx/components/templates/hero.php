<section class="hero">
    <div class="slide">

        <div class="content">
            <?= wysiwyg_format($component['eyebrow']); ?>
            <?= wysiwyg_format($component['headline']); ?>
        </div>
    <?php if(!empty($component['mobile_image']) || !empty($component['desktop_image'])): ?>
        <div class="slide-image">
            <?= get_picture_tag([
                ['media' => '(max-width: 768px)', 'srcset' => $component['mobile_image']],
                ['media' => '(min-width: 768px)', 'srcset' => $component['desktop_image']],
            ]); ?>
        </div>
    <?php endif; ?>
    </div>
</section>