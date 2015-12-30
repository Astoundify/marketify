(function() {
  jQuery(function($) {
    var addVideo, marketifyVideoManager;
    addVideo = wp.media.controller.Library.extend({
      defaults: _.defaults({
        id: 'add-video',
        title: 'Video Header',
        library: wp.media.query({
          type: 'video'
        })
      }, wp.media.controller.Library.prototype.defaults)
    });
    marketifyVideoManager = {
      select: function(val, shortcode) {
        var defaultPostId;
        shortcode = wp.shortcode.next(shortcode, val);
        defaultPostId = wp.media.gallery.defaults.id;
        if (!shortcode) {
          return;
        }
        shortcode = shortcode.shortcode;
        if (_.isUndefined(shortcode.get('id')) && !_.isUndefined(defaultPostId)) {
          shortcode.set('id', defaultPostId);
        }
        if (!shortcode.attrs.named.ids) {
          return;
        }
        if (shortcode.attrs.named.ids.length === 0) {

        }
      }
    };
    wp.media.marketifyEditVideo = {
      frame: function() {
        this._frame = wp.media({
          state: 'add-video',
          states: [new addVideo()],
          button: {
            text: 'Set video header'
          }
        });
        return this._frame;
      },
      init: function() {
        return $('#set-video-header').click(function(e) {
          e.preventDefault();
          return wp.media.marketifyEditVideo.frame().open();
        });
      }
    };
    return $(wp.media.marketifyEditVideo.init);
  });

}).call(this);
