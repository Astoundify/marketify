<?php
/**
 * Some old template tags people might use in child themes.
 */

function marketify_purchase_link( $download_id ) {
    marketify()->get( 'edd' )->purchase_form->purchase_link( $download_id );
}
