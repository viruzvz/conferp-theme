/**
 * CONFERP Accessibility
 *
 * Global accessibility controls shared across
 * CONFERP and CONRERP websites.
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

    const contrastButton = document.querySelector(
        '[data-conferp-contrast]'
    );

    const fontIncreaseButton = document.querySelector(
        '[data-conferp-font-increase]'
    );

    const fontDecreaseButton = document.querySelector(
        '[data-conferp-font-decrease]'
    );


    // ======================================================
    // Contrast
    // ======================================================

    const applyContrast = (enabled) => {

        html.classList.toggle(
            'conferp-high-contrast',
            enabled
        );

        if (contrastButton) {

            contrastButton.setAttribute(
                'aria-pressed',
                enabled ? 'true' : 'false'
            );
        }
    };


    if (contrastButton) {

        contrastButton.addEventListener('click', () => {

            const enabled = !html.classList.contains(
                'conferp-high-contrast'
            );

            applyContrast(enabled);

            localStorage.setItem(
                STORAGE.contrast,
                enabled ? '1' : '0'
            );

        });
    }


    // ======================================================
    // Font size
    // ======================================================

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


    const getCurrentFontSize = () => {

        const current = parseInt(
            html.dataset.conferpFontSize,
            10
        );

        return Number.isNaN(current)
            ? FONT_DEFAULT
            : current;
    };


    if (fontIncreaseButton) {

        fontIncreaseButton.addEventListener('click', () => {

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
    }


    if (fontDecreaseButton) {

        fontDecreaseButton.addEventListener('click', () => {

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
    }


    // ======================================================
    // Restore preferences
    // ======================================================

    const storedContrast = localStorage.getItem(
        STORAGE.contrast
    );

    if (storedContrast === '1') {
        applyContrast(true);
    }


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