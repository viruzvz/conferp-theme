/**
 * CONFERP Global Components
 *
 * Handles shared interactions used by the Federal
 * CONFERP global layer.
 *
 * Currently:
 *
 * - Utility Bar regional selector
 * - Global Subfooter regional selector
 * - Accessibility Sticky
 * - Back to Top
 */

document.addEventListener('DOMContentLoaded', () => {

	'use strict';


	/**
	 * ======================================================
	 * CONRERP Regional Selectors
	 * ======================================================
	 *
	 * The same regional selector is displayed in:
	 *
	 * - Global Utility Bar
	 * - Global Subfooter
	 *
	 * Both components use:
	 *
	 * data-conferp-regional-selector
	 */

	const regionalSelectors = document.querySelectorAll(
		'[data-conferp-regional-selector]'
	);


	if (regionalSelectors.length) {

		regionalSelectors.forEach((selector) => {

			selector.addEventListener('change', (event) => {

				const url = event.currentTarget.value;


				/**
				 * Ignore the placeholder option.
				 */
				if (!url) {
					return;
				}


				/**
				 * Navigate to the selected CONRERP.
				 */
				window.location.href = url;

			});

		});

	}


	/**
	 * ======================================================
	 * Accessibility Sticky
	 * ======================================================
	 *
	 * Controls only the opening and closing behavior
	 * of the floating accessibility panel.
	 *
	 * Accessibility actions such as:
	 *
	 * - VLibras
	 * - High contrast
	 * - Font increase
	 * - Font decrease
	 *
	 * remain handled by accessibility.js.
	 */

	const accessibilitySticky = document.querySelector(
		'[data-conferp-accessibility-sticky]'
	);

	const accessibilityTrigger = document.querySelector(
		'[data-conferp-accessibility-trigger]'
	);

	const accessibilityPanel = document.querySelector(
		'[data-conferp-accessibility-panel]'
	);


	/**
	 * Only initialize when the accessibility
	 * sticky exists on the current page.
	 */
	if (
		accessibilitySticky &&
		accessibilityTrigger &&
		accessibilityPanel
	) {

		/**
		 * Close accessibility panel.
		 */
		const closeAccessibilityPanel = () => {

			accessibilitySticky.classList.remove(
				'is-open'
			);

			accessibilityTrigger.setAttribute(
				'aria-expanded',
				'false'
			);

			accessibilityTrigger.setAttribute(
				'aria-label',
				'Abrir recursos de acessibilidade'
			);

			accessibilityPanel.setAttribute(
				'aria-hidden',
				'true'
			);

		};


		/**
		 * Open accessibility panel.
		 */
		const openAccessibilityPanel = () => {

			accessibilitySticky.classList.add(
				'is-open'
			);

			accessibilityTrigger.setAttribute(
				'aria-expanded',
				'true'
			);

			accessibilityTrigger.setAttribute(
				'aria-label',
				'Fechar recursos de acessibilidade'
			);

			accessibilityPanel.setAttribute(
				'aria-hidden',
				'false'
			);

		};


		/**
		 * Toggle accessibility panel.
		 */
		accessibilityTrigger.addEventListener(
			'click',
			() => {

				const isOpen =
					accessibilitySticky.classList.contains(
						'is-open'
					);


				if (isOpen) {

					closeAccessibilityPanel();

					return;
				}


				openAccessibilityPanel();

			}
		);


		/**
		 * Close when clicking outside the component.
		 */
		document.addEventListener(
			'click',
			(event) => {

				if (
					!accessibilitySticky.classList.contains(
						'is-open'
					)
				) {
					return;
				}


				if (
					accessibilitySticky.contains(
						event.target
					)
				) {
					return;
				}


				closeAccessibilityPanel();

			}
		);


		/**
		 * Close with ESC.
		 *
		 * Return keyboard focus to the trigger after
		 * closing the accessibility panel.
		 */
		document.addEventListener(
			'keydown',
			(event) => {

				if (
					event.key !== 'Escape' ||
					!accessibilitySticky.classList.contains(
						'is-open'
					)
				) {
					return;
				}


				closeAccessibilityPanel();

				accessibilityTrigger.focus();

			}
		);

	}


	/**
	 * ======================================================
	 * Back to Top
	 * ======================================================
	 *
	 * Shows the global Back to Top control after the user
	 * scrolls down the page.
	 *
	 * The component uses:
	 *
	 * data-conferp-back-to-top
	 */

	const backToTop = document.querySelector(
		'[data-conferp-back-to-top]'
	);


	if (backToTop) {

		/**
		 * Distance from the top before displaying
		 * the Back to Top control.
		 */
		const BACK_TO_TOP_OFFSET = 300;


		/**
		 * Update button visibility according
		 * to the current scroll position.
		 */
		const updateBackToTopVisibility = () => {

			const shouldShow =
				window.scrollY > BACK_TO_TOP_OFFSET;


			backToTop.classList.toggle(
				'is-visible',
				shouldShow
			);

		};


		/**
		 * Return to the beginning of the page.
		 *
		 * Respect the user's reduced motion preference.
		 */
		backToTop.addEventListener(
			'click',
			() => {

				const prefersReducedMotion =
					window.matchMedia(
						'(prefers-reduced-motion: reduce)'
					).matches;


				window.scrollTo({
					top: 0,
					behavior: prefersReducedMotion
						? 'auto'
						: 'smooth'
				});

			}
		);


		/**
		 * Monitor page scrolling.
		 *
		 * Passive listener avoids blocking the browser's
		 * scrolling behavior.
		 */
		window.addEventListener(
			'scroll',
			updateBackToTopVisibility,
			{
				passive: true
			}
		);


		/**
		 * Check initial position.
		 *
		 * Important when the browser restores a previous
		 * scroll position after refreshing or returning
		 * to the page.
		 */
		updateBackToTopVisibility();

	}

});