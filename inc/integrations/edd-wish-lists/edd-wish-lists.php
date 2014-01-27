<?php

remove_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );

add_action( 'marketify_single_download_content_after', 'edd_wl_load_wish_list_link' );