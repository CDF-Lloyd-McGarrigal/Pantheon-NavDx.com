<?php
$accordionDefaults = array(
    'title_content' => '',
    'body_content' => ''
);
?>

    <div class="accordion<?php echo ($component['only_one_open'] && $component['only_one_open'] == true) ? ' solo-mode' : '';?>">
        <div class="acc-items">

        <?php
        if( is_array( $component['accordion_items'] ) ) :
            foreach( $component['accordion_items'] as $item ) :
                array_default( $item, $accordionDefaults );
        ?>
            <div class="item">
                <div class="item-title js-accordion-trigger">
                    <?php echo $item['title_content']; ?>
                    <i class="accordion-state"></i>
                </div>

                <div class="item-content">
                    <?php echo $item['body_content']; ?>
                </div>
            </div>

        <?php endforeach;
        endif; ?>

        </div>
    </div>
    <link rel="preload" href="/wp-content/themes/images/close-form-icon.svg">
    <link rel="preload" href="/wp-content/themes/images/open-accordion.svg">


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->

    <script type="text/javascript">

        var accordionOnlyOneOpen = <?php echo ($component['only_one_open'] && $component['only_one_open'] == true) ? 'true' : 'false';?>;
        var accordionTrigger = "js-accordion-trigger";
        var accordionOpenClass = "is-open";


        if(typeof $ !== 'undefined' && $.fn && $.fn.jquery) {
            // If we have jQuery

            $('.'+accordionTrigger).on('click', function(e){
                if(accordionOnlyOneOpen) {
                    $(this).parent().parent().find("." + accordionOpenClass).removeClass(accordionOpenClass);
                }

                $(this).parent().toggleClass(accordionOpenClass);
                e.preventDefault();
            });

        } else {
            // No jQuery, use plain JavaScript

            // Helper Functions
                // TODO: abstract these into some place where we aren't repeating their declarations
                function hasClass(el, className) {
                    return el.classList ? el.classList.contains(className) : new RegExp('\\b'+ className+'\\b').test(el.className);
                }
                function addClass(el, className) {
                    if (el.classList) el.classList.add(className);
                    else if (!hasClass(el, className)) el.className += ' ' + className;
                }
                function removeClass(el, className) {
                    if (el.classList) el.classList.remove(className);
                    else el.className = el.className.replace(new RegExp('\\b'+ className+'\\b', 'g'), '');
                }


            var triggers = document.getElementsByClassName(accordionTrigger);

            var accordionClickFn = function(evt) {
                var targ = evt.currentTarget.parentNode;
                var activeClass = accordionOpenClass;
                var targIsOpen = hasClass(targ, activeClass);

                // if we only want one open at a time
                if(accordionOnlyOneOpen && !targIsOpen) {
                    var openedSiblings = targ.parentNode.getElementsByClassName(activeClass);

                    for (var i = 0; i < openedSiblings.length; i++) {
                        removeClass(openedSiblings[i], activeClass);
                    }
                }

                // toggle the active class on the parent
                if( targIsOpen == false ) {
                    addClass(targ, activeClass);
                } else {
                    removeClass(targ, activeClass);
                }


                evt.preventDefault();
            };

            for (var i=0; i<triggers.length; i++) {
                triggers[i].addEventListener('click', accordionClickFn);
            }

        }
    </script>
<!--  END : Accordions -->
