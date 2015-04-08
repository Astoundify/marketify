(function() {
  var bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  jQuery(function($) {
    var Download, DownloadStandard, options;
    options = marketifyDownload;
    Download = (function() {
      function Download() {
        if ('standard' === options.format) {
          new DownloadStandard();
        }
      }

      return Download;

    })();
    DownloadStandard = (function() {
      function DownloadStandard() {
        this.initContentSlider = bind(this.initContentSlider, this);
        this.initTopSlider = bind(this.initTopSlider, this);
        this.el = '.download-gallery';
        this.elAsNav = '.download-gallery-navigation';
        if ('top' === options.featuredLocation) {
          this.initTopSlider();
        } else {
          this.initContentSlider();
        }
      }

      DownloadStandard.prototype.initTopSlider = function() {
        return $(this.el).slick({
          adaptiveHeight: true
        });
      };

      DownloadStandard.prototype.initContentSlider = function() {
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

      return DownloadStandard;

    })();
    return $(document).ready(function() {
      return new Download;
    });
  });

}).call(this);
