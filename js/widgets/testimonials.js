(function() {
  jQuery(function($) {
    var Testimonials;
    Testimonials = (function() {
      function Testimonials() {
        var $list;
        $list = $('.testimonials-list');
        if (!$list.length) {
          return;
        }
        $list.each(function() {
          var $inner;
          $inner = $(this).children().filter(':first-child');
          console.log($inner);
          if ($inner.hasClass('company-testimonial')) {
            return $inner.parent().slick({
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
          } else {
            return $inner.parent().slick({
              autoplay: true,
              autoplaySpeed: 3000,
              slidesToShow: 2,
              slidesToScroll: 2,
              arrows: false,
              dots: false,
              responsive: [
                {
                  breakpoint: 500,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
          }
        });
      }

      return Testimonials;

    })();
    return new Testimonials();
  });

}).call(this);
