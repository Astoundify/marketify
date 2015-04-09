(function() {
  jQuery(function($) {
    var el;
    el = '.edd_downloads_list';
    $(document).on('facetwp-refresh', function() {
      return $(el).html('');
    });
    return $(document).on('facetwp-loaded', function() {
      var grid;
      grid = document.querySelector(el);
      return salvattore['recreateColumns'](grid);
    });
  });

}).call(this);
