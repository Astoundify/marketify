jQuery ($) ->
  el = '.facetwp-template.edd_downloads_list'

  $(document).on 'facetwp-refresh facetwp-loaded', ->
    $(el).find( $( '.edd_download.content-grid-download' ) ).attr( 'style', '' );

    grid = document.querySelector el
    salvattore[ 'recreateColumns' ](grid)

    $( '.site-content' ).find( $( '#edd_download_pagination' ) ).remove();
