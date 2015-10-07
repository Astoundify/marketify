<?php

class Marketify_Template_Page_Header {

    public function __construct() {
        add_filter( 'get_the_archive_title', array( $this, 'get_the_archive_title' ) );

        add_filter( 'marketify_page_header', array( $this, 'tag_atts' ), 10, 2 );
        add_action( 'marketify_entry_before', array( $this, 'close_header_outer' ) );

        add_action( 'marketify_entry_before', array( $this, 'archive_title' ), 5 );
        add_action( 'marketify_entry_before', array( $this, 'page_title' ), 5 );
        add_action( 'marketify_entry_before', array( $this, 'post_title' ), 6 );
        add_action( 'marketify_entry_before', array( $this, 'home_title' ), 5 );
    }

    public function get_the_archive_title( $title ) { 
        if ( is_post_type_archive( 'download' ) ) {
            $title = edd_get_label_plural();
        } else if ( is_tax() ) {
            $title = single_term_title( '', false );

            if ( did_action( 'marketify_downloads_before' ) ) {
                $title = sprintf( __( 'All %s', 'marketify' ), $title );
            }
        }

        return $title;
    }

    public function close_header_outer() {
        echo '</div></div>';
    }

    public function home_title() { 
        if ( ! is_front_page() || is_home() ) {
            return;
        }

        the_post();

        the_content();

        rewind_posts();
    }

    public function page_title() {
        if ( ! is_singular( 'page' ) ) {
            return;
        }

        the_post();
    ?>
        <div class="page-header page-header--singular container">
            <h1 class="page-title"><?php the_title(); ?></h1>
    <?php
        rewind_posts();
    }

    public function archive_title() {
        if ( ! is_archive() ) {
            return;
        }
    ?>
        <div class="page-header container">
            <h1 class="page-title"><?php the_archive_title(); ?></h1>
    <?php
    }

    public function post_title() {
        if ( ! is_singular( 'post' ) ) {
            return;
        }
?>
<div class="page-header container">
    <div class="user-bubble user-bubble--with-social">
        <?php
            $social = marketify()->template->entry->social_profiles();
            printf( '<div class="user-bubble__gravatar">%1$s %2$s</div>',
                sprintf( '<div class="user-bubble__social-profiles">%1$s</div>', $social ),
                get_avatar( get_the_author_meta( 'ID' ), 140 )
            );
        ?>
    </div>
    <div class="page-header__entry-meta page-header__entry-meta--author">
        <?php
            printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                esc_attr( sprintf( __( 'View all posts by %s', 'marketify' ), get_the_author() ) ),
                esc_html( get_the_author() )
            );
        ?>
    </div>

    <h1 class="page-title"><?php the_title(); ?></h1>

    <div class="page-header__entry-meta page-header__entry-meta--date"><?php echo get_the_date(); ?></div>
</div>
<?php
    }

    public function tag_atts( $args ) {
        $defaults = apply_filters( 'marketify_page_header_defaults', array(
            'classes' => 'header-outer',
            'object_ids' => false,
            'size' => 'large'
        ) );

        $args = wp_parse_args( $args, $defaults );
        $atts = $this->build_tag_atts( $args );

        $output = '';

        foreach ( $atts as $attribute => $properties ) {
            $output .= sprintf( '%s="%s"', $attribute, trim( $properties ) );
        }

        return $output;
    }

    private function build_tag_atts( $args ) {
        $atts = array(
            'class' => $args[ 'classes' ],
            'style' => ''
        );

        $atts = $this->add_background_image( $atts, $args );

        return $atts;
    }

    private function add_background_image( $atts, $args ) {
        $background_image = $this->find_background_image( $args );

        if ( $background_image ) {
            $atts[ 'style' ] .= ' background-image:url(' . $background_image . ');';
            $atts[ 'class' ] .= ' has-image';
        } else {
            $atts[ 'class' ] .= ' no-image';
        }

        return $atts;
    }

    private function find_background_image( $args ) {
        $background_image = false;
		$format_style_is_background = false;

		if ( marketify()->get( 'edd' ) ) {
			$format_style_is_background = marketify()->get( 'edd' )->template->download->is_format_style( 'background' );
		}

        if (
            is_singular( array( 'post', 'page' ) ) ||
            ( is_singular( 'download' ) && $format_style_is_background )
        ) {
            $background_image = wp_get_attachment_image_src( get_post_thumbnail_id(), $args[ 'size' ] );
            $background_image = $background_image[0];
        }

        return apply_filters( 'marketify_page_header_image', $background_image, $args );
    }

}
