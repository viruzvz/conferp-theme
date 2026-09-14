/**
 * CONFERP Global Utility Bar
 */

document.addEventListener('DOMContentLoaded', () => {

    'use strict';

    const regionalSelector = document.querySelector(
        '[data-conferp-regional-selector]'
    );

    if (!regionalSelector) {
        return;
    }

    regionalSelector.addEventListener('change', (event) => {

        const url = event.target.value;

        if (!url) {
            return;
        }

        window.location.href = url;

    });

});