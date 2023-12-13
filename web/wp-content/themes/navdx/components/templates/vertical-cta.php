<section class="vertical-cta">
<?php foreach($component['cta'] as $cta): ?>
    <div class="cta <?= $cta['color']; ?>"<?= !empty($cta['jumplink']) ? ' data-jumplink="'.$cta['jumplink'].'"' : ''; ?>>
        <h3><?= $cta['heading']; ?></h3>
        <div class="cta-border">

            <div class="content-icon">
                <?= img_from_id($cta['column_icon'], 'icon'); ?>
            </div>
            <div class="content-body">
                <?= wysiwyg_format($cta['body_text']); ?>
            </div>
            <div class="content-callout">
                <?= wysiwyg_format($cta['callout']); ?>
            </div>
            <div class="content-action condensed">
                <?= wysiwyg_format($cta['action']); ?>
            </div>

        </div>
    </div>
<?php endforeach; ?>
</section>