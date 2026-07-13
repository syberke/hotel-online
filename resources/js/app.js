

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const revealElements = () => {
    const elements = document.querySelectorAll('section, [data-ui-reveal]');

    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -48px' });

    elements.forEach((element) => {
        element.classList.add('ui-reveal');
        observer.observe(element);
    });
};

document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', revealElements)
    : revealElements();
