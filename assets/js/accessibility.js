/**
 * CONFERP Accessibility
 *
 * Global accessibility controls shared across
 * CONFERP and CONRERP websites.
 *
 * Supports multiple accessibility control instances,
 * including:
 *
 * - Global Utility Bar
 * - Global Accessibility Sticky
 */

document.addEventListener('DOMContentLoaded', () => {

	'use strict';


	const html = document.documentElement;


	// ======================================================
	// Settings
	// ======================================================

	const STORAGE = {
		contrast: 'conferp_accessibility_contrast',
		fontSize: 'conferp_accessibility_font_size'
	};

	const FONT_MIN = 90;
	const FONT_MAX = 120;
	const FONT_STEP = 10;
	const FONT_DEFAULT = 100;


	// ======================================================
	// Controls
	// ======================================================

	const contrastButtons = document.querySelectorAll(
		'[data-conferp-contrast]'
	);

	const fontIncreaseButtons = document.querySelectorAll(
		'[data-conferp-font-increase]'
	);

	const fontDecreaseButtons = document.querySelectorAll(
		'[data-conferp-font-decrease]'
	);


	// ======================================================
	// Contrast
	// ======================================================

	/**
	 * Synchronize the state of every contrast control.
	 */
	const updateContrastButtons = (enabled) => {

		contrastButtons.forEach((button) => {

			button.setAttribute(
				'aria-pressed',
				enabled ? 'true' : 'false'
			);

		});

	};


	/**
	 * Apply or remove high contrast mode.
	 */
	const applyContrast = (enabled) => {

		html.classList.toggle(
			'conferp-high-contrast',
			enabled
		);

		updateContrastButtons(enabled);

	};


	/**
	 * Attach the contrast behavior to every control.
	 */
	contrastButtons.forEach((button) => {

		button.addEventListener('click', () => {

			const enabled = !html.classList.contains(
				'conferp-high-contrast'
			);

			applyContrast(enabled);

			localStorage.setItem(
				STORAGE.contrast,
				enabled ? '1' : '0'
			);

		});

	});


	// ======================================================
	// Font size
	// ======================================================

	/**
	 * Apply the global font scale.
	 */
	const applyFontSize = (size) => {

		const normalizedSize = Math.min(
			FONT_MAX,
			Math.max(FONT_MIN, size)
		);

		html.style.setProperty(
			'--conferp-font-scale',
			`${normalizedSize / 100}`
		);

		html.dataset.conferpFontSize = normalizedSize;

	};


	/**
	 * Get the currently applied font size.
	 */
	const getCurrentFontSize = () => {

		const current = parseInt(
			html.dataset.conferpFontSize,
			10
		);

		return Number.isNaN(current)
			? FONT_DEFAULT
			: current;

	};


	/**
	 * Increase font size.
	 *
	 * Every A+ control on the page uses
	 * the same global font scale.
	 */
	fontIncreaseButtons.forEach((button) => {

		button.addEventListener('click', () => {

			const current = getCurrentFontSize();

			const next = Math.min(
				current + FONT_STEP,
				FONT_MAX
			);

			applyFontSize(next);

			localStorage.setItem(
				STORAGE.fontSize,
				next.toString()
			);

		});

	});


	/**
	 * Decrease font size.
	 *
	 * Every A- control on the page uses
	 * the same global font scale.
	 */
	fontDecreaseButtons.forEach((button) => {

		button.addEventListener('click', () => {

			const current = getCurrentFontSize();

			const next = Math.max(
				current - FONT_STEP,
				FONT_MIN
			);

			applyFontSize(next);

			localStorage.setItem(
				STORAGE.fontSize,
				next.toString()
			);

		});

	});


	// ======================================================
	// Restore preferences
	// ======================================================

	const storedContrast = localStorage.getItem(
		STORAGE.contrast
	);


	/**
	 * Always explicitly restore the contrast state.
	 *
	 * This also synchronizes aria-pressed across
	 * Utility Bar and Accessibility Sticky.
	 */
	applyContrast(
		storedContrast === '1'
	);


	const storedFontSize = parseInt(
		localStorage.getItem(STORAGE.fontSize),
		10
	);


	applyFontSize(
		Number.isNaN(storedFontSize)
			? FONT_DEFAULT
			: storedFontSize
	);

});