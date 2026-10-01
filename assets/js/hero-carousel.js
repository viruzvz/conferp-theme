/**
 * CONFERP Hero Carousel
 *
 * Elementor structure:
 *
 * .hero-carousel
 * ├── .hero-carousel__slide
 * │   └── .hero-carousel__inner
 * │       ├── .hero-carousel__content
 * │       └── .hero-carousel__media
 * │
 * └── .hero-carousel__slide
 *
 * Features:
 * - Fade transition
 * - Pagination
 * - Autoplay
 * - Keyboard navigation
 * - Mobile swipe
 * - Pause on hover/focus
 * - Reduced motion
 * - Accessibility
 *
 * @package Conferp_Theme
 */

document.addEventListener(
	'DOMContentLoaded',
	() => {

		const carousels =
			document.querySelectorAll(
				'.hero-carousel'
			);


		if (!carousels.length) {
			return;
		}


		carousels.forEach(
			(carousel) => {

				initHeroCarousel(
					carousel
				);

			}
		);

	}
);


/**
 * Detect Elementor editor.
 *
 * @return {boolean}
 */
function isElementorEditor() {

	const body =
		document.body;

	const html =
		document.documentElement;


	return (
		body.classList.contains(
			'elementor-editor-active'
		) ||
		body.classList.contains(
			'elementor-editor-preview'
		) ||
		html.classList.contains(
			'elementor-html'
		) ||
		window.location.search.includes(
			'elementor-preview='
		)
	);

}


/**
 * Initialize carousel.
 *
 * @param {HTMLElement} carousel
 *
 * @return {void}
 */
function initHeroCarousel(carousel) {

	// ------------------------------------------------------
	// Elementor protection
	// ------------------------------------------------------

	if (isElementorEditor()) {
		return;
	}


	// ------------------------------------------------------
	// Slides
	// ------------------------------------------------------

	const slides =
		Array.from(
			carousel.children
		).filter(
			(element) => {

				return element.classList.contains(
					'hero-carousel__slide'
				);

			}
		);


	if (!slides.length) {
		return;
	}


	// ------------------------------------------------------
	// Configuration
	// ------------------------------------------------------

	const AUTOPLAY_DELAY =
		6000;

	const SWIPE_THRESHOLD =
		50;


	const prefersReducedMotion =
		window.matchMedia(
			'(prefers-reduced-motion: reduce)'
		).matches;


	// ------------------------------------------------------
	// State
	// ------------------------------------------------------

	let currentIndex = 0;

	let autoplayTimer = null;

	let pagination = null;

	let dots = [];


	let touchStartX = 0;
	let touchStartY = 0;

	let touchEndX = 0;
	let touchEndY = 0;


	// ------------------------------------------------------
	// Carousel
	// ------------------------------------------------------

	carousel.classList.add(
		'is-initialized'
	);


	carousel.setAttribute(
		'role',
		'region'
	);


	carousel.setAttribute(
		'aria-roledescription',
		'carousel'
	);


	if (
		!carousel.hasAttribute(
			'aria-label'
		)
	) {

		carousel.setAttribute(
			'aria-label',
			'Destaques do CONFERP'
		);

	}


	// ------------------------------------------------------
	// Slides Accessibility
	// ------------------------------------------------------

	slides.forEach(
		(slide, index) => {

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

		}
	);


	// ------------------------------------------------------
	// Pagination
	// ------------------------------------------------------

	function createPagination() {

		if (slides.length <= 1) {
			return;
		}


		pagination =
			document.createElement(
				'div'
			);


		pagination.className =
			'hero-carousel__pagination';


		pagination.setAttribute(
			'aria-label',
			'Navegação dos destaques'
		);


		slides.forEach(
			(slide, index) => {

				const dot =
					document.createElement(
						'button'
					);


				dot.type =
					'button';


				dot.className =
					'hero-carousel__dot';


				dot.setAttribute(
					'aria-label',
					`Ir para o destaque ${index + 1}`
				);


				dot.addEventListener(
					'click',
					() => {

						goToSlide(
							index
						);


						restartAutoplay();

					}
				);


				pagination.appendChild(
					dot
				);


				dots.push(
					dot
				);

			}
		);


		carousel.appendChild(
			pagination
		);

	}


	// ------------------------------------------------------
	// Navigate
	// ------------------------------------------------------

	function goToSlide(index) {

		if (index < 0) {

			index =
				slides.length - 1;

		}


		if (
			index >=
			slides.length
		) {

			index = 0;

		}


		currentIndex =
			index;


		slides.forEach(
			(slide, slideIndex) => {

				const isActive =
					slideIndex ===
					currentIndex;


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
					dotIndex ===
					currentIndex;


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


	// ------------------------------------------------------
	// Autoplay
	// ------------------------------------------------------

	function stopAutoplay() {

		if (!autoplayTimer) {
			return;
		}


		window.clearInterval(
			autoplayTimer
		);


		autoplayTimer =
			null;

	}


	function startAutoplay() {

		if (
			prefersReducedMotion ||
			slides.length <= 1 ||
			document.hidden
		) {
			return;
		}


		stopAutoplay();


		autoplayTimer =
			window.setInterval(
				nextSlide,
				AUTOPLAY_DELAY
			);

	}


	function restartAutoplay() {

		stopAutoplay();

		startAutoplay();

	}


	// ------------------------------------------------------
	// Keyboard
	// ------------------------------------------------------

	carousel.addEventListener(
		'keydown',
		(event) => {

			if (
				event.key ===
				'ArrowLeft'
			) {

				event.preventDefault();

				previousSlide();

				restartAutoplay();

			}


			if (
				event.key ===
				'ArrowRight'
			) {

				event.preventDefault();

				nextSlide();

				restartAutoplay();

			}

		}
	);


	// ------------------------------------------------------
	// Swipe
	// ------------------------------------------------------

	carousel.addEventListener(
		'touchstart',
		(event) => {

			if (
				!event.touches.length
			) {
				return;
			}


			touchStartX =
				event.touches[0]
					.clientX;


			touchStartY =
				event.touches[0]
					.clientY;


			touchEndX =
				touchStartX;


			touchEndY =
				touchStartY;


			stopAutoplay();

		},
		{
			passive: true
		}
	);


	carousel.addEventListener(
		'touchmove',
		(event) => {

			if (
				!event.touches.length
			) {
				return;
			}


			touchEndX =
				event.touches[0]
					.clientX;


			touchEndY =
				event.touches[0]
					.clientY;

		},
		{
			passive: true
		}
	);


	carousel.addEventListener(
		'touchend',
		() => {

			const distanceX =
				touchEndX -
				touchStartX;


			const distanceY =
				touchEndY -
				touchStartY;


			const horizontalDistance =
				Math.abs(
					distanceX
				);


			const verticalDistance =
				Math.abs(
					distanceY
				);


			const horizontalSwipe =
				horizontalDistance >
				verticalDistance;


			const validSwipe =
				horizontalDistance >=
				SWIPE_THRESHOLD;


			if (
				horizontalSwipe &&
				validSwipe
			) {

				if (
					distanceX < 0
				) {

					nextSlide();

				} else {

					previousSlide();

				}

			}


			restartAutoplay();

		},
		{
			passive: true
		}
	);


	// ------------------------------------------------------
	// Hover
	// ------------------------------------------------------

	carousel.addEventListener(
		'mouseenter',
		stopAutoplay
	);


	carousel.addEventListener(
		'mouseleave',
		startAutoplay
	);


	// ------------------------------------------------------
	// Focus
	// ------------------------------------------------------

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


	// ------------------------------------------------------
	// Browser Visibility
	// ------------------------------------------------------

	document.addEventListener(
		'visibilitychange',
		() => {

			if (
				document.hidden
			) {

				stopAutoplay();

				return;

			}


			startAutoplay();

		}
	);


	// ------------------------------------------------------
	// Initialize
	// ------------------------------------------------------

	createPagination();

	goToSlide(0);

	startAutoplay();

}