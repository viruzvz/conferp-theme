
/**
 * CONFERP — Componente global Load More
 * Carrega a próxima página sem atualizar a página atual.
 */
(() => {
  'use strict';

  async function initializeLoadMore(component) {
    const listSelector = component.dataset.list;
    const cardSelector = component.dataset.card;
    const paginationSelector = component.dataset.pagination;

    if (!listSelector || !cardSelector || !paginationSelector) return;

    const list = document.querySelector(listSelector);
    const pagination = document.querySelector(paginationSelector);
    const button = component.querySelector('[data-load-more-button]');
    const status = component.querySelector('[data-load-more-status]');

    if (!list || !pagination || !button) return;

    const nextSelector = '.next.page-numbers';
    let next = pagination.querySelector(nextSelector);

    if (!next) {
      component.hidden = true;
      return;
    }

    component.hidden = false;
    pagination.hidden = true;

    let loading = false;

    button.addEventListener('click', async () => {
      if (loading || !next) return;

      loading = true;
      button.disabled = true;

      const label = button.textContent;
      button.textContent = 'Carregando documentos...';

      try {
        const response = await fetch(next.href, {
          credentials: 'same-origin'
        });

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }

        const html = await response.text();
        const documentPage = new DOMParser().parseFromString(
          html,
          'text/html'
        );

        const newList = documentPage.querySelector(listSelector);
        const newPagination = documentPage.querySelector(
          paginationSelector
        );

        if (!newList) {
          throw new Error('Lista não encontrada.');
        }

        const cards = newList.querySelectorAll(cardSelector);

        if (!cards.length) {
          throw new Error('Nenhum item encontrado.');
        }

        const existingIds = new Set(
          Array.from(list.querySelectorAll(cardSelector))
            .map((card) => card.id)
            .filter(Boolean)
        );

        const fragment = document.createDocumentFragment();
        let added = 0;

        cards.forEach((card) => {
          if (card.id && existingIds.has(card.id)) return;

          fragment.appendChild(document.importNode(card, true));
          if (card.id) existingIds.add(card.id);
          added++;
        });

        if (!added) {
          throw new Error('Nenhum item novo foi recebido.');
        }

        list.appendChild(fragment);

        next = newPagination
          ? newPagination.querySelector(nextSelector)
          : null;

        if (status) {
          const count = list.querySelectorAll(cardSelector).length;
          const total = Number(component.dataset.total);

          status.textContent = total
            ? `Exibindo ${count} de ${total} documentos`
            : `${count} documentos exibidos`;
        }

        if (!next) {
          component.hidden = true;
        }
      } catch (error) {
        console.error('CONFERP Load More:', error);

        component.hidden = true;
        pagination.hidden = false;

        if (status) {
          status.textContent =
            'Não foi possível carregar mais documentos. ' +
            'Utilize a paginação.';
        }
      } finally {
        loading = false;
        button.disabled = false;
        button.textContent = label;
      }
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    document
      .querySelectorAll('[data-conferp-load-more]')
      .forEach(initializeLoadMore);
  });
})();
