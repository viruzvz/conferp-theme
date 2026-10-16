jQuery(function($) {
  const mode = $('#ata_modo');
  const update = () => $('.conferp-ata-historico').toggle(mode.val() === 'historico');
  const $title = $('#title');
  const $wrap = $('#titlewrap');
  // O editor clássico sobrepõe seu label flutuante ao placeholder customizado.
  // Ocultamos apenas esse label neste CPT, mantendo o campo nativo #title.
  $wrap.find('#title-prompt-text').hide();
  const $hint = $('<p class="description" id="ata-native-title-hint">').insertAfter($wrap);
  const refreshTitle = () => {
    const historical = mode.val() === 'historico';
    $title.prop('readOnly', !historical);
    $title.attr('placeholder', historical ? 'Digite o título original da ata' : 'Título gerado automaticamente ao salvar');
    $hint.text(historical ? 'Título histórico: digite o nome original aqui.' : 'Título padronizado: gerado a partir de número, data e tipo ao salvar.');
  };
  mode.on('change', function(){update();refreshTitle();}); update(); refreshTitle();
  $('#ata_pdf_select').on('click', function(e) {
    e.preventDefault();
    const frame = wp.media({title:'Selecionar PDF oficial', button:{text:'Usar este PDF'}, library:{type:'application/pdf'}, multiple:false});
    frame.on('select', function() {
      const item = frame.state().get('selection').first().toJSON();
      $('#ata_pdf_id').val(item.id);
      $('#ata_pdf_preview').text(item.filename || 'PDF selecionado');
    });
    frame.open();
  });
  $('#ata_pdf_remove').on('click', function(e) {
    e.preventDefault(); $('#ata_pdf_id').val(''); $('#ata_pdf_preview').text('Nenhum PDF selecionado.');
  });
});

// PDFs complementares: seleção múltipla, ordenação e remoção.
jQuery(function($) {
  const $list = $('#ata_anexos_lista');
  const $field = $('#ata_anexos_pdf_ids');
  if (!$list.length) return;
  const sync = () => $field.val($list.children('li[data-id]').map(function(){return $(this).attr('data-id');}).get().join(','));
  $list.sortable({items:'li[data-id]', handle:'.dashicons-move', update:sync});
  $list.on('click', '.ata-anexo-remove', function(e){ e.preventDefault(); $(this).closest('li').remove(); sync(); });
  $('#ata_anexos_add').on('click', function(e){
    e.preventDefault();
    const frame = wp.media({title:'Adicionar PDFs complementares', button:{text:'Adicionar PDFs'}, library:{type:'application/pdf'}, multiple:true});
    frame.on('select', function(){
      const selected = frame.state().get('selection').toJSON();
      const primary = String($('#ata_pdf_id').val() || '');
      selected.forEach(function(item){
        const id = String(item.id);
        if (id === primary || $list.children('li[data-id="'+id+'"]').length) return;
        const $li = $('<li>').attr('data-id',id).css({padding:'9px 10px',background:'#fff',border:'1px solid #c3c4c7',margin:'5px 0',display:'flex',gap:'12px',alignItems:'center'});
        $('<span>').addClass('dashicons dashicons-move').attr('aria-hidden','true').css('cursor','move').appendTo($li);
        $('<a>').css('flex','1').attr({href:item.url || '#',target:'_blank',rel:'noopener noreferrer'}).text(item.filename || item.title || 'PDF').appendTo($li);
        $('<button>').attr({type:'button','aria-label':'Remover anexo'}).addClass('button-link-delete ata-anexo-remove').text('Remover').appendTo($li);
        $list.append($li);
      });
      sync();
    });
    frame.open();
  });
});
