(function() {
  jQuery(function($) {
    var el;
    el = '.facetwp-template.edd_downloads_list';
    $(document).on('facetwp-refresh', function() {});
    return $(document).on('facetwp-loaded', function() {
      var grid;
      $(el).find($('.edd_download.content-grid-download')).attr('style', '');
      grid = document.querySelector(el);
      salvattore['registerGrid'](grid);
      return $('.site-content').find($('#edd_download_pagination')).remove();
    });
  });

}).call(this);