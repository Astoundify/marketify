(function() {
  var bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  jQuery(function($) {
    var FeaturedPopular;
    FeaturedPopular = (function() {
      function FeaturedPopular() {
        this.initSliders = bind(this.initSliders, this);
        this.initSwitcher = bind(this.initSwitcher, this);
        this.initSwitcher();
        this.initSliders();
      }

      FeaturedPopular.prototype.initSwitcher = function() {
        $('.featured-popular-tabs > div:first-child').removeClass('inactive').addClass('active');
        return $('.featured-popular-switcher span').click(function(e) {
          e.preventDefault();
          $('.featured-popular-tabs > div').removeClass('active').addClass('inactive');
          $($(this).data('tab')).addClass('active');
          return $('.featured-popular-slick .edd_downloads_list').slick('setPosition');
        });
      };

      FeaturedPopular.prototype.initSliders = function() {
        return $('.featured-popular-slick .edd_downloads_list').slick({
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          arrows: false,
          dots: true,
          adaptiveHeight: true,
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            }, {
              breakpoint: 500,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });
      };

      return FeaturedPopular;

    })();
    return new FeaturedPopular();
  });

}).call(this);
