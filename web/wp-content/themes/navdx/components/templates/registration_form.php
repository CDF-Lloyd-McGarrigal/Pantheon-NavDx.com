<section class="registration-form" data-modal-trigger="registration-modal">
    <?= wysiwyg_format($component['trigger_form']) ?>
</section>

<script>
    $('.registration-form [data-field-name="email"]').on('change', function(e){
        $('.modal-popup [data-field-name="email"]').val($('.registration-form [data-field-name="email"]').val());
    });
</script>