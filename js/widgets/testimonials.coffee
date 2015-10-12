jQuery ($) ->

  class Testimonials

    constructor: ->
      $list = $( '.testimonials-list' )

      if ! $list.length then return

      $list.each ->
        $inner = $(@).children().filter( ':first-child' )

        console.log $inner

        if $inner.hasClass( 'company-testimonial' )
          $inner.parent().slick(
            slidesToShow: 5
            slidesToScroll: 1
            arrows: true
            dots: false
            variableWidth: true
            centerMode: true
            responsive: [
              {
                breakpoint: 992,
                settings: {
                  slidesToShow: 3,
                }
              }
              {
                breakpoint: 500,
                settings: {
                  slidesToShow: 1,
                }
              }
            ]
          )
        else
          $inner.parent().slick(
            autoplay: true
            autoplaySpeed: 3000
            slidesToShow: 2
            slidesToScroll: 2
            arrows: false
            dots: false
            responsive: [
              {
                breakpoint: 500,
                settings: {
                  slidesToShow: 1,
                }
              }
            ]
         )

  new Testimonials()
