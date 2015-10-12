(function() {
  var bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  jQuery(function($) {
    var Testimonials;
    Testimonials = (function() {
      function Testimonials() {
        this.initCompany = bind(this.initCompany, this);
        this.initCompany();
      }

      Testimonials.prototype.initCompany = function() {
        if (!$('.company-testimonial').length) {
          return;
        }
        return $('.testimonials-list').slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          arrows: true,
          dots: false,
          variableWidth: true,
          centerMode: true,
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 3
              }
            }, {
              breakpoint: 500,
              settings: {
                slidesToShow: 1
              }
            }
          ]
        });
      };

      return Testimonials;

    })();
    return new Testimonials();
  });

}).call(this);
