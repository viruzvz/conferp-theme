/**
 * CONFERP Hero Carousel
 *
 * Expected Elementor structure:
 *
 * .hero-carousel
 * ├── .hero-carousel__slide
 * │   └── .hero-carousel__inner
 * │       ├── .hero-carousel__content
 * │       └── .hero-carousel__media
 * │
 * └── .hero-carousel__slide
 *     └── .hero-carousel__inner
 *         ├── .hero-carousel__content
 *         └── .hero-carousel__media
 *
 * Responsibilities:
 *
 * Elementor:
 * - Content editing.
 * - Slide duplication.
 * - Slide deletion.
 * - Slide reordering.
 * - Editor visibility.
 *
 * Theme:
 * - Active slide.
 * - Fade transition.
 * - Pagination.
 * - Autoplay.
 * - Keyboard navigation.
 * - Accessibility.
 *
 * @package Conferp_Theme
 */

document.addEventListener('DOMContentLoaded', () => {
	'use strict';

	const carousels = document.querySelectorAll('.hero-carousel');

	if (!carousels.length) {
		return;
	}

	carousels.forEach((carousel) => {
		initHeroCarousel(carousel);
	});
});


/**
 * Check whether Elementor editor/preview is active.
 *
 * Carousel behavior must not initialize inside Elementor.
 *
 * @return {boolean}
 */
function isElementorEditor() {

	const body = document.body;
	const html = document.documentElement;

	return (
		body.classList.contains('elementor-editor-active') ||
		body.classList.contains('elementor-editor-preview') ||
		html.classList.contains('elementor-html') ||
		window.location.search.includes('elementor-preview=')
	);

}


/**
 * Initialize one Hero Carousel.
 *
 * @param {HTMLElement} carousel Carousel root.
 *
 * @return {void}
 */
function initHeroCarousel(carousel) {

	/**
	 * Elementor must retain full control over the component
	 * while the page is being edited.
	 */
	if (isElementorEditor()) {
		return;
	}


	/**
	 * IMPORTANT:
	 *
	 * Slides are now DIRECT children of .hero-carousel.
	 *
	 * We no longer search for:
	 *
	 * .hero-carousel__inner > .hero-carousel__slide
	 */
	const slides = Array.from(
		carousel.children
	).filter((element) => {
		return element.classList.contains(
			'hero-carousel__slide'
		);
	});


	if (!slides.length) {
		return;
	}


	// ======================================================
	// Configuration
	// ======================================================

	const AUTOPLAY_DELAY = 6000;

	const prefersReducedMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	let currentIndex = 0;
	let autoplayTimer = null;

	let pagination = null;
	let dots = [];


	// ======================================================
	// Carousel State
	// ======================================================

	carousel.classList.add(
		'is-initialized'
	);


	// ======================================================
	// Accessibility
	// ======================================================

	carousel.setAttribute(
		'role',
		'region'
	);

	carousel.setAttribute(
		'aria-roledescription',
		'carousel'
	);


	if (!carousel.hasAttribute('aria-label')) {

		carousel.setAttribute(
			'aria-label',
			'Destaques do CONFERP'
		);

	}


	slides.forEach((slide, index) => {

		slide.setAttribute(
			'role',
			'group'
		);

		slide.setAttribute(
			'aria-roledescription',
			'slide'
		);

		slide.setAttribute(
			'aria-label',
			`${index + 1} de ${slides.length}`
		);

	});


	// ======================================================
	// Pagination
	// ======================================================

	function createPagination() {

		if (slides.length <= 1) {
			return;
		}


		pagination = document.createElement(
			'div'
		);

		pagination.className =
			'hero-carousel__pagination';

		pagination.setAttribute(
			'aria-label',
			'Navegação dos destaques'
		);


		slides.forEach((slide, index) => {

			const dot = document.createElement(
				'button'
			);

			dot.type = 'button';

			dot.className =
				'hero-carousel__dot';

			dot.setAttribute(
				'aria-label',
				`Ir para o destaque ${index + 1}`
			);


			dot.addEventListener(
				'click',
				() => {

					goToSlide(index);

					restartAutoplay();

				}
			);


			pagination.appendChild(dot);

			dots.push(dot);

		});


		carousel.appendChild(
			pagination
		);

	}


	// ======================================================
	// Slide Navigation
	// ======================================================

	function goToSlide(index) {

		if (index < 0) {
			index = slides.length - 1;
		}


		if (index >= slides.length) {
			index = 0;
		}


		currentIndex = index;


		slides.forEach(
			(slide, slideIndex) => {

				const isActive =
					slideIndex === currentIndex;


				slide.classList.toggle(
					'is-active',
					isActive
				);


				slide.setAttribute(
					'aria-hidden',
					isActive
						? 'false'
						: 'true'
				);


				/**
				 * Inactive slides must not receive
				 * keyboard interaction.
				 */
				if (isActive) {

					slide.removeAttribute(
						'inert'
					);

				} else {

					slide.setAttribute(
						'inert',
						''
					);

				}

			}
		);


		dots.forEach(
			(dot, dotIndex) => {

				const isActive =
					dotIndex === currentIndex;


				dot.classList.toggle(
					'is-active',
					isActive
				);


				if (isActive) {

					dot.setAttribute(
						'aria-current',
						'true'
					);

				} else {

					dot.removeAttribute(
						'aria-current'
					);

				}

			}
		);

	}


	function nextSlide() {

		goToSlide(
			currentIndex + 1
		);

	}


	function previousSlide() {

		goToSlide(
			currentIndex - 1
		);

	}


	// ======================================================
	// Autoplay
	// ======================================================

	function stopAutoplay() {

		if (!autoplayTimer) {
			return;
		}


		window.clearInterval(
			autoplayTimer
		);

		autoplayTimer = null;

	}


	function startAutoplay() {

		if (
			prefersReducedMotion ||
			slides.length <= 1
		) {
			return;
		}


		stopAutoplay();


		autoplayTimer = window.setInterval(
			nextSlide,
			AUTOPLAY_DELAY
		);

	}


	function restartAutoplay() {

		stopAutoplay();

		startAutoplay();

	}


	// ======================================================
	// Keyboard Navigation
	// ======================================================

	carousel.addEventListener(
		'keydown',
		(event) => {

			if (event.key === 'ArrowLeft') {

				event.preventDefault();

				previousSlide();

				restartAutoplay();

				return;
			}


			if (event.key === 'ArrowRight') {

				event.preventDefault();

				nextSlide();

				restartAutoplay();

			}

		}
	);


	// ======================================================
	// Mouse Interaction
	// ======================================================

	carousel.addEventListener(
		'mouseenter',
		stopAutoplay
	);


	carousel.addEventListener(
		'mouseleave',
		startAutoplay
	);


	// ======================================================
	// Keyboard Focus
	// ======================================================

	carousel.addEventListener(
		'focusin',
		stopAutoplay
	);


	carousel.addEventListener(
		'focusout',
		(event) => {

			if (
				event.relatedTarget &&
				carousel.contains(
					event.relatedTarget
				)
			) {
				return;
			}


			startAutoplay();

		}
	);


	// ======================================================
	// Browser Tab Visibility
	// ======================================================

	document.addEventListener(
		'visibilitychange',
		() => {

			if (document.hidden) {

				stopAutoplay();

				return;
			}


			startAutoplay();

		}
	);


	// ======================================================
	// Initialize
	// ======================================================

	createPagination();

	goToSlide(0);

	startAutoplay();

}