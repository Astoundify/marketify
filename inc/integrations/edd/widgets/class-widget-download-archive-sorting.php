<?php

class Marketify_Widget_Download_Archive_Sorting extends Marketify_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'marketify_widget_download_archive_sorting';
        $this->widget_description = __( 'Display a way to sort the current product archives.', 'marketify' );
        $this->widget_id          = 'marketify_widget_download_archive_sorting';
        $this->widget_name        = __( 'Marketify - Shop: Download Sorting', 'marketify' );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => '',
                'label' => __( 'Title:', 'marketify' )
            )
        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        if ( is_page_template( 'page-templates/popular.php' ) ) {
            return;
        }

        extract( $args );

        $title   = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $order   = get_query_var( 'm-order' ) ? strtolower( get_query_var( 'm-order' ) ) : 'desc';
        $orderby = get_query_var( 'm-orderby' ) ? get_query_var( 'm-orderby' ) : 'post_date';

        echo $before_widget;

        if ( $title ) echo $before_title . $title . $after_title;
        ?>

        <form action="" method="get" class="download-sorting">
            <label for="orderby">
                <?php _e( 'Sort by:', 'marketify' ); ?>
                <?php
                    echo EDD()->html->select( array(
                        'name' => 'm-orderby',
                        'id' => 'm-orderby',
                        'selected' => $orderby,
                        'show_option_all' => false,
                        'show_option_none' => false,
                        'options' => marketify()->get( 'edd' )->sorting->options()
                    ) );
                ?>
            </label>

            <label for="order-asc">
                <input type="radio" name="m-order" id="order-asc" value="asc" <?php checked( 'asc', $order ); ?>><span
                class="<?php echo checked( 'asc', $order, false ) ? 'active ': ''; ?>icon-arrow-up"></span>
            </label>

            <label for="order-desc">
                <input type="radio" name="m-order" id="order-desc" value="desc" <?php checked( 'desc', $order );
                ?>><span class="<?php echo checked( 'desc', $order, false ) ? 'active ': ''; ?>icon-arrow-down2"></span>
            </label>

            <?php if ( is_search() ) : ?>
                <input type="hidden" name="s" value="<?php echo get_query_var( 's' ); ?>" />
                <input type="hidden" name="post_type" value="download" />
            <?php endif; ?>
        </form>

        <?php
        echo $after_widget;
    }

}
