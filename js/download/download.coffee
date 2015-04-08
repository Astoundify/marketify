jQuery ($) ->

  options = marketifyDownload;
  
  class Download
  
    constructor: ->
      if 'standard' == options.format 
        new DownloadStandard()
  
  class DownloadStandard
  
    constructor: ->
      @el = '.download-gallery'
      @elAsNav = '.download-gallery-navigation'
  
      if 'top' == options.featuredLocation
        @initTopSlider()
      else
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
