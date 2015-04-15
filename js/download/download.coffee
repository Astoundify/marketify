jQuery ($) ->

  class Download
  
    constructor: ->
      @el = '.download-gallery'
      @elAsNav = '.download-gallery-navigation'
  
      @initTopSlider()
      @initContentSlider()
  
    initTopSlider: =>
      $(@el).slick
        adaptiveHeight: true

    initContentSlider: =>
      $(@elAsNav).slick
        slidesToShow: 6
        slidesToScroll: 1
        asNavFor: @el
        focusOnSelect: true 
        dots: true
        arrows: false
        slide: 'div'

      $(@el).slick
        slidesToShow: 1
        slidesToScroll: 1
        arrows: false
        fade: true
        asNavFor: @elAsNav
        adaptiveHeight: true
  

  $(document).ready () ->
    new Download
