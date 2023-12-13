<section class="text-with-chart" data-image-position="<?= $component['image_position']; ?>">
    <div class="image">
        <?php
        echo get_picture_tag([
            ['media' => '(min-width: 768px)', 'srcset' => $component['desktop_image']],
            ['media' => '(max-width: 768px)', 'srcset' => $component['mobile_image']],
        ]);
        ?>
    </div>
    <div class="content"><?= wysiwyg_format($component['content']); ?></div>
    <div class="footnotes"><?= $component['footnote']; ?></div>
</section>