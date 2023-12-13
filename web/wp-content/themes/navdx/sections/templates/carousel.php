<section id="<?= !empty($section['jumpLinkName']) ? $section['jumpLinkName'] : 'carousel'; ?>-section" class="<?= !empty($section['custom_classes']) ? $section['custom_classes'] : '' ?>">
    <div class="contentWrapper">
        <div class="slick-slider">
            <?php
            foreach($section['carousel'] as $panel):
                $buttons = $panel['links'];
            ?>
            <div class="slide">
                <div class="content">
                    <?= wysiwyg_format($panel['eyebrow']); ?>
                    <?= wysiwyg_format($panel['headline']); ?>
                    <div class="buttons right-aligned">
                        <?php foreach($buttons as $link): ?>
                        <a href="<?= $link['url']; ?>" class="<?= $link['button_class']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : ''; ?>><?= $link['text']; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="slide-image">
                    <?= get_picture_tag([
                        ['media' => '(max-width: 768px)', 'srcset' => $panel['mobile_image']],
                        ['media' => '(min-width: 768px)', 'srcset' => $panel['desktop_image']],
                    ]); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>