(function ($) {
  'use strict';
  $(document).on('click', '.conferp-evento-pick', function (event) {
    event.preventDefault();
    var box = $(this).closest('.conferp-evento-media');
    var frame = wp.media({ title: 'Selecionar imagem do evento', library: { type: 'image' }, button: { text: 'Usar imagem' }, multiple: false });
    frame.on('select', function () {
      var item = frame.state().get('selection').first().toJSON();
      box.find('.conferp-evento-media-id').val(item.id);
      var url = item.sizes && item.sizes.thumbnail ? item.sizes.thumbnail.url : item.url;
      box.find('.conferp-evento-preview').empty().append($('<img>', { src: url, alt: '', style: 'max-width:160px;height:auto' }));
    });
    frame.open();
  });
  $(document).on('click', '.conferp-evento-clear', function (event) {
    event.preventDefault();
    var box = $(this).closest('.conferp-evento-media');
    box.find('.conferp-evento-media-id').val('');
    box.find('.conferp-evento-preview').empty();
  });
})(jQuery);
