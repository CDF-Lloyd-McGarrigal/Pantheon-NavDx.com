<section class="cta-chevrons cta">
<?php foreach($component['cta'] as $cta): ?>
    <div class="cta <?= $cta['color']; ?>">

        <div class="cta-content">

            <h3><?= $cta['heading']; ?></h3>
            <div class="content">
                <?= wysiwyg_format($cta['body']); ?>
            </div>
            <div class="buttons centered">
                <?php foreach($cta['links'] as $link): ?>
                <a href="<?= $link['url']; ?>" class="<?= $link['class']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : null; ?>>
                    <?php if(!empty($link['image'])): ?>
                    <img src="<?= $link['image']; ?>">
                    <?php endif; ?>
                    <?= $link['text']; ?>
                </a>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
<?php endforeach; ?>
</section>