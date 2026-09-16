/**
 * CONFERP Mobile Navigation
 *
 * Controls:
 *
 * - Mobile primary menu;
 * - Mobile search;
 * - Menu accessibility states;
 * - Mobile submenus.
 *
 * @author Carlos Neri
 */

document.addEventListener('DOMContentLoaded', () => {

	'use strict';


	/**
	 * ======================================================
	 * Elements
	 * ======================================================
	 */

	const menuToggle = document.querySelector(
		'[data-conferp-mobile-menu-toggle]'
	);

	const menuPanel = document.querySelector(
		'[data-conferp-mobile-menu]'
	);

	const searchToggle = document.querySelector(
		'[data-conferp-mobile-search-toggle]'
	);

	const searchPanel = document.querySelector(
		'[data-conferp-mobile-search]'
	);


	/**
	 * ======================================================
	 * Mobile Menu
	 * ======================================================
	 */

	const closeMenu = () => {

		if (!menuToggle || !menuPanel) {
			return;
		}

		menuToggle.setAttribute(
			'aria-expanded',
			'false'
		);

		menuToggle.setAttribute(
			'aria-label',
			'Abrir menu principal'
		);

		menuPanel.hidden = true;
	};


	const openMenu = () => {

		if (!menuToggle || !menuPanel) {
			return;
		}

		/**
		 * Search and menu should not remain
		 * open simultaneously.
		 */
		closeSearch();

		menuToggle.setAttribute(
			'aria-expanded',
			'true'
		);

		menuToggle.setAttribute(
			'aria-label',
			'Fechar menu principal'
		);

		menuPanel.hidden = false;
	};


	const toggleMenu = () => {

		if (!menuToggle || !menuPanel) {
			return;
		}

		const isOpen =
			menuToggle.getAttribute('aria-expanded') === 'true';

		if (isOpen) {
			closeMenu();
			return;
		}

		openMenu();
	};


	if (menuToggle && menuPanel) {

		menuToggle.addEventListener(
			'click',
			toggleMenu
		);
	}


	/**
	 * ======================================================
	 * Mobile Search
	 * ======================================================
	 */

	const closeSearch = () => {

		if (!searchToggle || !searchPanel) {
			return;
		}

		searchToggle.setAttribute(
			'aria-expanded',
			'false'
		);

		searchToggle.setAttribute(
			'aria-label',
			'Abrir busca'
		);

		searchPanel.hidden = true;
	};


	const openSearch = () => {

		if (!searchToggle || !searchPanel) {
			return;
		}

		/**
		 * Menu and search should not remain
		 * open simultaneously.
		 */
		closeMenu();

		searchToggle.setAttribute(
			'aria-expanded',
			'true'
		);

		searchToggle.setAttribute(
			'aria-label',
			'Fechar busca'
		);

		searchPanel.hidden = false;


		/**
		 * Focus search field automatically.
		 */
		const searchField = searchPanel.querySelector(
			'.search-field'
		);

		if (searchField) {

			window.requestAnimationFrame(() => {
				searchField.focus();
			});
		}
	};


	const toggleSearch = () => {

		if (!searchToggle || !searchPanel) {
			return;
		}

		const isOpen =
			searchToggle.getAttribute('aria-expanded') === 'true';

		if (isOpen) {
			closeSearch();
			return;
		}

		openSearch();
	};


	if (searchToggle && searchPanel) {

		searchToggle.addEventListener(
			'click',
			toggleSearch
		);
	}


	/**
	 * ======================================================
	 * Mobile Submenus
	 * ======================================================
	 */

	if (menuPanel) {

		const parentItems = menuPanel.querySelectorAll(
			'.menu-item-has-children'
		);

		parentItems.forEach((menuItem) => {

			const link = menuItem.querySelector(
				':scope > a'
			);

			const submenu = menuItem.querySelector(
				':scope > .sub-menu'
			);

			if (!link || !submenu) {
				return;
			}


			/**
			 * Creates a dedicated submenu button.
			 *
			 * This preserves the original WordPress link:
			 *
			 * "O CONFERP" → still navigates normally.
			 * Button     → opens/closes submenu.
			 */

			const submenuToggle =
				document.createElement('button');

			submenuToggle.type = 'button';

			submenuToggle.className =
				'mobile-navigation__submenu-toggle';

			submenuToggle.setAttribute(
				'aria-expanded',
				'false'
			);

			submenuToggle.setAttribute(
				'aria-label',
				`Abrir submenu: ${link.textContent.trim()}`
			);


			const submenuIcon =
				document.createElement('span');

			submenuIcon.className =
				'mobile-navigation__submenu-toggle-icon';

			submenuIcon.setAttribute(
				'aria-hidden',
				'true'
			);

			submenuToggle.appendChild(
				submenuIcon
			);


			/**
			 * Insert button after parent link.
			 */
			link.insertAdjacentElement(
				'afterend',
				submenuToggle
			);


			/**
			 * Toggle submenu.
			 */
			submenuToggle.addEventListener(
				'click',
				() => {

					const isOpen =
						menuItem.classList.contains('is-open');


					menuItem.classList.toggle(
						'is-open',
						!isOpen
					);


					submenuToggle.setAttribute(
						'aria-expanded',
						String(!isOpen)
					);


					submenuToggle.setAttribute(
						'aria-label',
						`${isOpen ? 'Abrir' : 'Fechar'} submenu: ${link.textContent.trim()}`
					);
				}
			);

		});
	}


	/**
	 * ======================================================
	 * Escape Key
	 * ======================================================
	 */

	document.addEventListener(
		'keydown',
		(event) => {

			if (event.key !== 'Escape') {
				return;
			}

			closeMenu();
			closeSearch();
		}
	);


	/**
	 * ======================================================
	 * Reset When Returning to Desktop
	 * ======================================================
	 *
	 * Prevents mobile panels from remaining logically
	 * open after resizing the browser beyond Bootstrap LG.
	 */

	const desktopBreakpoint =
		window.matchMedia('(min-width: 992px)');


	const resetMobileNavigation = (event) => {

		if (!event.matches) {
			return;
		}

		closeMenu();
		closeSearch();


		if (!menuPanel) {
			return;
		}

		const openSubmenus = menuPanel.querySelectorAll(
			'.menu-item-has-children.is-open'
		);

		openSubmenus.forEach((menuItem) => {

			menuItem.classList.remove(
				'is-open'
			);

			const submenuToggle = menuItem.querySelector(
				':scope > .mobile-navigation__submenu-toggle'
			);

			if (submenuToggle) {

				submenuToggle.setAttribute(
					'aria-expanded',
					'false'
				);
			}
		});
	};


	desktopBreakpoint.addEventListener(
		'change',
		resetMobileNavigation
	);

});