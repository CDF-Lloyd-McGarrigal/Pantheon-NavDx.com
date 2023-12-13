<section class="padded-cta cta">
<?php

    $calloutDefaults = array(
        'column_text'  => '',
        'cta_text'  => '',
        'cta_link'  => ''
    );
?>
    <?php
        if( is_array( $component['callouts' ] ) ) :
            foreach( $component['callouts'] as $callout ) :
                array_default( $callout, $calloutDefaults );
    ?>

    <div class="cta-item" data-border-color="<?= $callout['color']; ?>">
        <div class="cta-text">
            <?php
            echo  wysiwyg_format($callout['column_text']);
            ?>
        </div>
        <?php
        if(!empty($callout['column_icon'])){
            img_from_id($callout['column_icon'], 'icon');
        }
        ?>
        <div class="buttons centered">
            <?php
            foreach( $callout['links'] as $link):
            ?>
            <a href="<?php echo $link['url']; ?>" class="<?php echo $link['button_class']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : null; ?>>
            <?php
            if(empty($link['image'])):
            ?>
                <?php echo $link['text']; ?>
            <?php
            else:
                img_from_id($link['image'], 'comm-logo');
            endif;
            ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php 
        endforeach;
        endif;
    ?>
</section>