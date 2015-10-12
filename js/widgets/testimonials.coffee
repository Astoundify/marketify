jQuery ($) ->

  class Testimonials

    constructor: ->
      @initCompany()

    initCompany: =>
      if ! $( '.company-testimonial' ).length then return

      $( '.testimonials-list' ).slick(
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

  new Testimonials()
