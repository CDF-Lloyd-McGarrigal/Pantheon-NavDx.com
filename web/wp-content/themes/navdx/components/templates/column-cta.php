<section class="column-cta cta">
<?php
    $calloutDefaults = array(
        'column_text'  => '',
        'cta_text'  => '',
        'column_icon'  => '',
        'color' => '',
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
            img_from_id($callout['column_icon'], 'icon');
        ?>
        </div>
        <?php
    if(!empty($callout['links'])):
        foreach( $callout['links'] as $link):
        ?>
        <div class="buttons">
            <a href="<?php echo $link['url']; ?>" class="<?php echo $link['button_class']; ?>"<?= !empty($link['target']) ? ' target="_blank"' : null; ?>>
            <?php
            if(empty($link['image'])):
            ?>
                <?php echo $link['text']; ?>
            <?php
            else:
                img_from_id($link['image']);
            endif;
            ?>
            </a>
        </div>
        <?php
        endforeach;
    endif;
        ?>
    </div>

    <?php 
        endforeach;
        endif;
    ?>
</section>