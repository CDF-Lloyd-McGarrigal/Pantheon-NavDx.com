<section class="buttons">
    <?php foreach($component['links'] as $link): ?>
    <a class="<?= $link['class']; ?>" href="<?= $link['url']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : ''; ?>><?= $link['text']; ?></a>
    <?php endforeach; ?>
</section>