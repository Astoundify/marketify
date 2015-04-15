(function() {
  jQuery(function($) {
    var el;
    el = '.edd_downloads_list';
    $(document).on('facetwp-refresh', function() {});
    return $(document).on('facetwp-loaded', function() {
      var grid;
      $('.edd_download.content-grid-download').attr('style', '');
      grid = document.querySelector(el);
      salvattore['registerGrid'](grid);
      return $('.site-content').find($('#edd_download_pagination')).remove();
    });
  });

}).call(this);
