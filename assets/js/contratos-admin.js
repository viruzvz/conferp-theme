/** CONFERP — cadastro de contratos: vigência e documentos na Biblioteca de Mídia. */
(function ($) {
  'use strict';
  $(function () {
    const vigencia = $('#conferp-contrato-vigencia');
    const datas = $('#conferp-contrato-vigencia-datas');
    const toggleDates = () => datas.prop('hidden', vigencia.val() !== 'determinado');
    vigencia.on('change', toggleDates);
    toggleDates();

    if (!window.wp || !wp.media) return;
    const pdfField = $('#conferp-contrato-pdf-id');
    const pdfPreview = $('#conferp-contrato-pdf-preview');
    let pdfFrame;
    $('#conferp-contrato-pdf-select').on('click', function (e) {
      e.preventDefault();
      if (!pdfFrame) {
        pdfFrame = wp.media({ title: 'Selecionar PDF original do contrato', button: { text: 'Usar este PDF' }, library: { type: 'application/pdf' }, multiple: false });
        pdfFrame.on('select', function () {
          const model = pdfFrame.state().get('selection').first();
          if (!model) return;
          const file = model.toJSON();
          if (file.mime !== 'application/pdf') return;
          pdfField.val(file.id);
          pdfPreview.empty().append($('<a>', { href: file.url, text: file.title || file.filename || 'PDF', target: '_blank', rel: 'noopener noreferrer' }));
        });
      }
      pdfFrame.open();
    });
    $('#conferp-contrato-pdf-remove').on('click', function (e) {
      e.preventDefault();
      pdfField.val('');
      pdfPreview.text('Nenhum PDF selecionado.');
    });

    const list = $('#conferp-contrato-documentos-list');
    let index = list.find('.conferp-contrato-documento-item').length;
    const allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
    const types = [['aditivo', 'Termo Aditivo'], ['apostilamento', 'Apostilamento'], ['anexo', 'Anexo complementar'], ['outro', 'Outro documento']];
    function addDocument(file) {
      if (!file.id || !allowed.includes(file.mime)) return;
      const idx = index++;
      const base = `conferp_contrato_documentos[${idx}]`;
      const item = $('<div>', { class: 'conferp-contrato-documento-item', css: { border: '1px solid #dcdcde', padding: '12px', margin: '8px 0', background: '#fff' } });
      item.append($('<input>', { type: 'hidden', class: 'conferp-contrato-documento-id', name: `${base}[id]`, value: file.id }));
      const select = $('<select>', { class: 'widefat', name: `${base}[tipo]` });
      types.forEach(([value, label]) => select.append($('<option>', { value, text: label })));
      item.append($('<p>').append($('<label>').append($('<strong>').text('Tipo de documento'), '<br>', select)));
      [['titulo', 'Identificação / título', 'text', file.title || file.filename || ''], ['data', 'Data do documento', 'date', '']].forEach(([key, label, type, value]) => {
        item.append($('<p>').append($('<label>').append($('<strong>').text(label), '<br>', $('<input>', { class: 'widefat', type, name: `${base}[${key}]`, value }))));
      });
      item.append($('<p>').append($('<label>').append($('<strong>').text('Descrição / finalidade'), '<br>', $('<textarea>', { class: 'widefat', rows: 2, name: `${base}[descricao]` }))));
      item.append($('<p>', { class: 'conferp-contrato-documento-preview' }).append($('<a>', { href: file.url, text: file.filename || file.title || 'Arquivo', target: '_blank', rel: 'noopener noreferrer' })));
      item.append($('<p>').append($('<button>', { type: 'button', class: 'button conferp-contrato-documento-select', text: 'Trocar arquivo' }), ' ', $('<button>', { type: 'button', class: 'button conferp-contrato-documento-remove', text: 'Remover documento' })));
      list.append(item);
    }
    let documentFrame;
    $('#conferp-contrato-documento-add').on('click', function (e) {
      e.preventDefault();
      if (!documentFrame) {
        documentFrame = wp.media({ title: 'Adicionar documentos ao contrato', button: { text: 'Adicionar arquivos' }, library: { type: ['application/pdf', 'image'] }, multiple: true });
        documentFrame.on('select', function () {
          documentFrame.state().get('selection').each(model => addDocument(model.toJSON()));
        });
      }
      documentFrame.open();
    });
    list.on('click', '.conferp-contrato-documento-remove', function (e) {
      e.preventDefault();
      $(this).closest('.conferp-contrato-documento-item').remove();
    });
    list.on('click', '.conferp-contrato-documento-select', function (e) {
      e.preventDefault();
      const item = $(this).closest('.conferp-contrato-documento-item');
      const frame = wp.media({ title: 'Substituir arquivo', button: { text: 'Usar este arquivo' }, library: { type: ['application/pdf', 'image'] }, multiple: false });
      frame.on('select', function () {
        const model = frame.state().get('selection').first();
        if (!model) return;
        const file = model.toJSON();
        if (!file.id || !allowed.includes(file.mime)) return;
        item.find('.conferp-contrato-documento-id').val(file.id);
        item.find('.conferp-contrato-documento-preview').empty().append($('<a>', { href: file.url, text: file.filename || file.title || 'Arquivo', target: '_blank', rel: 'noopener noreferrer' }));
      });
      frame.open();
    });
  });
})(jQuery);
