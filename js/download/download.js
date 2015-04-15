(function() {
  var bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  jQuery(function($) {
    var Download;
    Download = (function() {
      function Download() {
        this.initContentSlider = bind(this.initContentSlider, this);
        this.initTopSlider = bind(this.initTopSlider, this);
        this.el = '.download-gallery';
        this.elAsNav = '.download-gallery-navigation';
        this.initTopSlider();
        this.initContentSlider();
      }

      Download.prototype.initTopSlider = function() {
        return $(this.el).slick({
          adaptiveHeight: true
        });
      };

      Download.prototype.initContentSlider = function() {
        $(this.elAsNav).slick({
          slidesToShow: 6,
          slidesToScroll: 1,
          asNavFor: this.el,
          focusOnSelect: true,
          dots: true,
          arrows: false,
          slide: 'div'
        });
        return $(this.el).slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
          fade: true,
          asNavFor: this.elAsNav,
          adaptiveHeight: true
        });
      };

      return Download;

    })();
    return $(document).ready(function() {
      return new Download;
    });
  });

}).call(this);
