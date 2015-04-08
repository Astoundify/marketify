jQuery ($) ->

  class FeaturedPopular
  
    constructor: ->
      @initSwitcher()
      @initSliders()

    initSwitcher: =>
      $( '.featured-popular-tabs > div:first-child' ).removeClass( 'inactive' ).addClass( 'active' )

      $( '.featured-popular-switcher span' ).click (e) ->
        e.preventDefault();

        $( '.featured-popular-tabs > div' ).removeClass( 'active' ).addClass( 'inactive' )
        $( $(@).data( 'tab' ) ).addClass( 'active' )

        $( '.featured-popular-slick .edd_downloads_list' ).slick 'setPosition'

  
    initSliders: =>
      if ! $( '.featured-popular-slick .edd_downloads_list' ).length then return

      extras = marketifyFeaturedPopular

      $( '.featured-popular-slick .edd_downloads_list' ).slick
        autoPlay: extras.autoPlay
        autoPlaySpeed: parseInt extras.autoPlaySpeed
        slidesToShow: 3
        slidesToScroll: 3
        infinite: true
        arrows: false
        dots: true
        adaptiveHeight: true
        responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          }
          {
            breakpoint: 500,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1 
            }
          }
       ]

  new FeaturedPopular()
