jQuery ($) ->
  el = '.facetwp-template.edd_downloads_list'

  $(document).on 'ready', ->
    $(el).removeData( 'columns' ).removeAttr( 'data-columns' )

  $(document).on 'facetwp-loaded', ->
    $(el).find( $( '.edd_download.content-grid-download' ) ).attr( 'style', '' );

    if ( typeof WPPlaylistView == 'function' )
      $( '.wp-playlist' ).each ->
        new WPPlaylistView { el: @ }

    grid = document.querySelector el
    salvattore[ 'registerGrid' ](grid)

    $( '#edd_download_pagination' ).remove()
