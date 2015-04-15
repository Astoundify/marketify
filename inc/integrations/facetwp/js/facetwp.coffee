jQuery ($) ->
  el = '.edd_downloads_list'
  
  $(document).on 'facetwp-refresh', ->
    
  $(document).on 'facetwp-loaded', ->
    grid = document.querySelector el
    # salvattore[ 'recreateColumns' ](grid)
