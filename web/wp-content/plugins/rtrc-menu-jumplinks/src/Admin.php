<?php
namespace rtrcMenuJumplniks;

use rtrcMenuJumplniks\App;

class Admin
{

    public function __construct(){

        $this->createHooks();
    }

    private function createHooks(){

        \add_action('wp_nav_menu_item_custom_fields', [$this, 'menuHook'], 10, 2);
        \add_action( 'wp_update_nav_menu_item', [$this,'saveHook'], 10, 2 );
        \add_filter( 'nav_menu_link_attributes', [$this,'attributeModifyHook'], 999, 4 );
    }

    /**
    * Add custom fields to menu item
    *
    * This will allow us to play nicely with any other plugin that is adding the same hook
    *
    * @param  int $item_id 
    * @params obj $item - the menu item
    * @params array $args
    */
    public function menuHook($item_id, $item){
        wp_nonce_field( 'jumplink_nonce', '_jumplink_nonce_name' );
        $jumplink = get_post_meta( $item_id, '_jumplink', true );
        ?>
        <div class="field-jumplink description-wide" style="margin: 5px 0;">
            <span class="description"><?php _e( "Jump Link", 'jumplink' ); ?></span>
            <br />

            <input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />

            <div class="logged-input-holder">
                <input type="text" name="jumplink[<?php echo $item_id ;?>]" id="jumplink-for-<?php echo $item_id ;?>" value="<?php echo esc_attr( $jumplink ); ?>" />
                <label for="jumplink-for-<?php echo $item_id ;?>">
                    <br>
                    <?php _e( '(optional) Sets a jumplink. Do not include the #', 'jumplink'); ?>
                </label>
            </div>

        </div>

        <?php
    }

    /**
    * Save the menu item meta
    * 
    * @param int $menu_id
    * @param int $menu_item_db_id   
    */
    function saveHook( $menu_id, $menu_item_db_id ) {

        // Verify this came from our screen and with proper authorization.
        if ( ! isset( $_POST['_jumplink_nonce_name'] ) || ! wp_verify_nonce( $_POST['_jumplink_nonce_name'], 'jumplink_nonce' ) ) {
            return $menu_id;
        }

        if ( isset( $_POST['jumplink'][$menu_item_db_id]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST['jumplink'][$menu_item_db_id] );
            update_post_meta( $menu_item_db_id, '_jumplink', $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, '_jumplink' );
        }
    }

    /**
    * Displays text on the front-end.
    *
    * @param string   $title The menu item's title.
    * @param WP_Post  $item  The current menu item.
    * @return string      
    */
    function attributeModifyHook( $atts, $item, $args, $depth ) {

        if( is_object( $item ) && isset( $item->ID ) ) {

            $jumplink = get_post_meta( $item->ID, '_jumplink', true );

            if ( !empty( $jumplink ) ) {
                $atts['href'] .= '#' . $jumplink;
            }
        }
        return $atts;
    }
}
