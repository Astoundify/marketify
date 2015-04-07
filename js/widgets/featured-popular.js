(function() {
  var bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  jQuery(function($) {
    var FeaturedPopular;
    FeaturedPopular = (function() {
      function FeaturedPopular() {
        this.initSliders = bind(this.initSliders, this);
        this.initSliders();
      }

      FeaturedPopular.prototype.initSliders = function() {
        return $('.featured-popular-slick .edd_downloads_list').slick({
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          arrows: false,
          dots: true,
          adaptiveHeight: true
        });
      };

      return FeaturedPopular;

    })();
    return new FeaturedPopular();
  });

}).call(this);
