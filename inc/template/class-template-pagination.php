<?php

class Marketify_Template_Pagination {

	public function __construct() {
		add_action( 'marketify_loop_after', array( $this, 'output' ) );
	}

	public function output() {
		global $wp_query;

		$big = 999999999;

		$links = paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'prev_text' => '<i class="icon-left-open-big"></i>',
			'next_text' => '<i class="icon-right-open-big"></i>'
		) );
	?>
		<div class="paginate-links container">
			<?php echo $links; ?>
		</div>
	<?php
	}

}
