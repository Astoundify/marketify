(function() {
  jQuery(function($) {
    var el;
    el = '.facetwp-template.edd_downloads_list';
    return $(document).on('facetwp-refresh facetwp-loaded', function() {
      var grid;
      $(el).find($('.edd_download.content-grid-download')).attr('style', '');
      grid = document.querySelector(el);
      salvattore['recreateColumns'](grid);
      return $('.site-content').find($('#edd_download_pagination')).remove();
    });
  });

}).call(this);
