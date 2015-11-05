<?php
/**
 *
 * @package Marketify
 */

if ( ! is_active_sidebar( 'sidebar-download-single' ) ) {
    return;
}
?>
    <div id="secondary" class="col-xs-12 col-sm-4" role="complementary">

        <?php dynamic_sidebar( 'sidebar-download-single' ); ?>

    </div><!-- #secondary -->
