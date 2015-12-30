jQuery ($) ->

  addVideo = wp.media.controller.Library.extend
    defaults :  _.defaults {
      id: 'add-video',
      title: 'Video Header',
      library: wp.media.query
        type: 'video'
    }, wp.media.controller.Library.prototype.defaults

  marketifyVideoManager =
    select: (val, shortcode) ->
      shortcode = wp.shortcode.next( shortcode, val )
      defaultPostId = wp.media.gallery.defaults.id

      if ! shortcode then return

      shortcode = shortcode.shortcode;

      if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
        shortcode.set( 'id', defaultPostId )

      if ( ! shortcode.attrs.named.ids )
        return

      if ( shortcode.attrs.named.ids.length == 0 )
        return

  wp.media.marketifyEditVideo =
    frame: ->
      @_frame = wp.media
        state: 'add-video'
        states: [ new addVideo() ]
        button:
          text: 'Set video header'

      @_frame

    init: ->
      $( '#set-video-header' ).click (e) ->
        e.preventDefault()

        wp.media.marketifyEditVideo.frame().open()

  $ wp.media.marketifyEditVideo.init
