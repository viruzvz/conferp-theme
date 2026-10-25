/** CONFERP — Seletor administrativo do PDF assinado. */
(function ($) {
  'use strict';
  $(function () {
    const field = $('#conferp-portaria-pdf-id');
    const preview = $('#conferp-portaria-pdf-preview');
    if (!field.length || !window.wp || !wp.media) return;
    let frame;
    $('#conferp-portaria-pdf-select').on('click', function (event) {
      event.preventDefault();
      if (!frame) {
        frame = wp.media({ title: 'Selecionar PDF assinado', button: { text: 'Usar este PDF' }, library: { type: 'application/pdf' }, multiple: false });
        frame.on('select', function () {
          const selected = frame.state().get('selection').first();
          if (!selected) return;
          const attachment = selected.toJSON();
          if (attachment.mime !== 'application/pdf') return;
          field.val(attachment.id).trigger('change');
          preview.empty().append($('<a>', { href: attachment.url, text: attachment.title || attachment.filename || 'PDF assinado', target: '_blank', rel: 'noopener noreferrer' }));
        });
      }
      frame.open();
    });
    $('#conferp-portaria-pdf-remove').on('click', function (event) {
      event.preventDefault();
      field.val('').trigger('change');
      preview.text('Nenhum PDF selecionado.');
    });

    // Anexos complementares: seleção múltipla, títulos e remoção.
    const list = $('#conferp-portaria-anexos-list');
    const add = $('#conferp-portaria-anexo-add');
    if (list.length && add.length && window.wp && wp.media) {
      let attachmentsFrame;
      let nextIndex = list.find('.conferp-portaria-anexo-item').length;
      const allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
      add.on('click', function (event) {
        event.preventDefault();
        if (!attachmentsFrame) {
          attachmentsFrame = wp.media({
            title: 'Selecionar anexos complementares',
            button: { text: 'Adicionar arquivos' },
            library: { type: ['application/pdf', 'image'] },
            multiple: true
          });
          attachmentsFrame.on('select', function () {
            const selected = attachmentsFrame.state().get('selection');
            selected.each(function (model) {
              const file = model.toJSON();
              if (!allowed.includes(file.mime) || !file.id) return;
              const exists = list.find('.conferp-portaria-anexo-id').filter(function () {
                return Number($(this).val()) === Number(file.id);
              }).length;
              if (exists) return;
              const idx = nextIndex++;
              const item = $('<div>', {
                class: 'conferp-portaria-anexo-item',
                css: { padding: '12px', margin: '8px 0', border: '1px solid #dcdcde', background: '#fff' }
              });
              item.append($('<input>', {
                type: 'hidden', class: 'conferp-portaria-anexo-id',
                name: `conferp_portaria_anexos[${idx}][id]`, value: file.id
              }));
              const label = $('<label>').append($('<strong>').text('Título do anexo')).append('<br>');
              label.append($('<input>', {
                type: 'text', class: 'widefat conferp-portaria-anexo-title',
                name: `conferp_portaria_anexos[${idx}][title]`,
                value: file.title || file.filename || ''
              }));
              item.append($('<p>').append(label));
              item.append($('<p>').append($('<a>', {
                href: file.url, target: '_blank', rel: 'noopener noreferrer',
                text: file.filename || file.title || 'Arquivo'
              })));
              item.append($('<button>', {
                type: 'button', class: 'button conferp-portaria-anexo-remove',
                text: 'Remover anexo'
              }));
              list.append(item);
            });
          });
        }
        attachmentsFrame.open();
      });
      list.on('click', '.conferp-portaria-anexo-remove', function (event) {
        event.preventDefault();
        $(this).closest('.conferp-portaria-anexo-item').remove();
      });
    }
  });
})(jQuery);
