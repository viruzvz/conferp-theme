/** CONFERP — Seletor administrativo do PDF oficial. */
(function ($) {
  'use strict';
  $(function () {
    const field = $('#conferp-instrucao-pdf-id');
    const preview = $('#conferp-instrucao-pdf-preview');
    if (!field.length || !window.wp || !wp.media) return;
    let frame;
    $('#conferp-instrucao-pdf-select').on('click', function (event) {
      event.preventDefault();
      if (!frame) {
        frame = wp.media({ title: 'Selecionar PDF oficial', button: { text: 'Usar este PDF' }, library: { type: 'application/pdf' }, multiple: false });
        frame.on('select', function () {
          const selected = frame.state().get('selection').first();
          if (!selected) return;
          const attachment = selected.toJSON();
          if (attachment.mime !== 'application/pdf') return;
          field.val(attachment.id).trigger('change');
          preview.empty().append($('<a>', { href: attachment.url, text: attachment.title || attachment.filename || 'PDF oficial', target: '_blank', rel: 'noopener noreferrer' }));
        });
      }
      frame.open();
    });
    $('#conferp-instrucao-pdf-remove').on('click', function (event) {
      event.preventDefault();
      field.val('').trigger('change');
      preview.text('Nenhum PDF selecionado.');
    });
    const list = $('#conferp-instrucao-anexos-list');
    const add = $('#conferp-instrucao-anexo-add');
    if (list.length && add.length && window.wp && wp.media) {
      let anexosFrame;
      let index = list.find('.conferp-instrucao-anexo-item').length;
      const allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
      add.on('click', function (event) {
        event.preventDefault();
        if (!anexosFrame) {
          anexosFrame = wp.media({ title: 'Selecionar anexos', button: { text: 'Adicionar arquivos' }, library: { type: ['application/pdf', 'image'] }, multiple: true });
          anexosFrame.on('select', function () {
            anexosFrame.state().get('selection').each(function (model) {
              const file = model.toJSON();
              if (!file.id || !allowed.includes(file.mime)) return;
              if (list.find('.conferp-instrucao-anexo-id').filter(function () { return Number($(this).val()) === Number(file.id); }).length) return;
              const n = index++;
              const item = $('<div>', { class: 'conferp-instrucao-anexo-item', css: { padding: '12px', margin: '8px 0', border: '1px solid #dcdcde' } });
              item.append($('<input>', { type: 'hidden', class: 'conferp-instrucao-anexo-id', name: `conferp_instrucao_anexos[${n}][id]`, value: file.id }));
              item.append($('<p>').append($('<label>').append($('<strong>').text('Título do anexo')).append('<br>').append($('<input>', { type: 'text', class: 'widefat conferp-instrucao-anexo-title', name: `conferp_instrucao_anexos[${n}][title]`, value: file.title || file.filename || '' }))));
              item.append($('<p>').append($('<a>', { href: file.url, target: '_blank', rel: 'noopener noreferrer', text: file.filename || 'Arquivo' })));
              item.append($('<button>', { type: 'button', class: 'button conferp-instrucao-anexo-remove', text: 'Remover anexo' }));
              list.append(item);
            });
          });
        }
        anexosFrame.open();
      });
      list.on('click', '.conferp-instrucao-anexo-remove', function (event) {
        event.preventDefault();
        $(this).closest('.conferp-instrucao-anexo-item').remove();
      });
    }
  });
})(jQuery);
