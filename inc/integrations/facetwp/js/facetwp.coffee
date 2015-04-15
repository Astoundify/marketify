jQuery ($) ->
  el = '.facetwp-template.edd_downloads_list'
  
  $(document).on 'facetwp-refresh', ->
    
  $(document).on 'facetwp-loaded', ->
    $( '.edd_download.content-grid-download' ).attr( 'style', '' );

    grid = document.querySelector el
    salvattore[ 'registerGrid' ](grid)

    $( '.site-content' ).find( $( '#edd_download_pagination' ) ).remove();
