<section class="container text-with-video" data-video-position="<?= $component['video_position']; ?>">
    <div class="content">
        <?= wysiwyg_format($component['content']); ?>
        <?php if(!empty($component['cta'])): ?>
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
    <a class="video video-container" href="<?= get_permalink(); ?>#video-modal" data-video-id="<?= $component['video_url']; ?>">
        <img class="video-thumb" src="https://img.youtube.com/vi/<?= $component['video_url']; ?>/hqdefault.jpg">
        <img class="play-button" src="/wp-content/uploads/2023/05/play-button-01.png">
    </a>
</section>

<section class="modal-popup" data-remodal-id="video-modal" data-remodal-options='{ "hashTracking": false, "closeOnEscape": true, "closeOnOutsideClick": true }'>
    <i class="close-button" data-remodal-action="cancel"></i>
    <div id="video-player"></div>
</section>
