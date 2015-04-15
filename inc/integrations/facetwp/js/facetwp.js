(function() {
  jQuery(function($) {
    var el;
    el = '.edd_downloads_list';
    $(document).on('facetwp-refresh', function() {});
    return $(document).on('facetwp-loaded', function() {
      var grid;
      return grid = document.querySelector(el);
    });
  });

}).call(this);
