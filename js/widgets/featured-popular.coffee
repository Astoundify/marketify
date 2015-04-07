jQuery ($) ->

  class FeaturedPopular
  
    constructor: ->
      @initSliders()
  
    initSliders: =>
      $( '.featured-popular-slick .edd_downloads_list' ).slick
        slidesToShow: 3
        slidesToScroll: 3
        infinite: true
        arrows: false
        dots: true
        adaptiveHeight: true

  new FeaturedPopular()
