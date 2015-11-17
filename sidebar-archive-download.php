<?php
/**
 *
 * @package Marketify
 */

if ( ! is_active_sidebar( 'sidebar-download' ) ) {
    return;
}
?>
<div class="widget-area widget-area--shop col-md-3 col-sm-5 col-xs-12" role="complementary">
    <?php dynamic_sidebar( 'sidebar-download' ); ?>
</div>
