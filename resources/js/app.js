import './bootstrap';

import Alpine from 'alpinejs';

// Mark the document before the DOM-ready callback. This lets CSS prepare
// animated elements immediately instead of first painting them visibly and
// hiding them later, which causes the initial-page blink.
document.documentElement.classList.add('aos-js');

window.Alpine = Alpine;

Alpine.start();

/**
 * Central reveal-on-scroll controller.
 *
 * Existing data-aos attributes are respected. Semantic sections, hero content,
 * card grids, and footers receive a small, consistent set of defaults so the
 * animation language remains predictable across Blade and dynamic content.
 */
const initAosAnimations = () => {
    const body = document.body;

    if (!body || body.dataset.aosInitialized === 'true') return;

    body.dataset.aosInitialized = 'true';

    const motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');
    const supportsObserver = 'IntersectionObserver' in window;
    let observedElements = new WeakSet();
    const allowedAnimations = new Set(['fade', 'fade-up', 'fade-down', 'fade-left', 'fade-right', 'zoom-in']);
    const blockedSelector = [
        '[data-aos-disabled]',
        '[hidden]',
        '[x-cloak]',
        'template',
        'dialog',
        '[role="dialog"]',
        '[aria-live]',
        '.fixed',
        '.sticky',
    ].join(',');
    let observer;
    let decorationFrame;
    let initialViewportReady = false;
    const pendingRoots = new Set();

    const clampNumber = (value, fallback, min, max) => {
        const parsed = Number.parseInt(value, 10);
        return Number.isFinite(parsed) ? Math.min(Math.max(parsed, min), max) : fallback;
    };

    const canAnimate = (element) => {
        if (!(element instanceof HTMLElement) || element === body) return false;
        if (element.matches(blockedSelector) || element.closest(blockedSelector)) return false;
        if (['BUTTON', 'INPUT', 'TEXTAREA', 'SELECT', 'OPTION', 'LABEL'].includes(element.tagName)) return false;
        return true;
    };

    const hasAnimatedDescendant = (element) => element.querySelector('[data-aos]') !== null;

    const applyTiming = (element) => {
        const duration = clampNumber(element.dataset.aosDuration, 820, 400, 1100);
        const delay = clampNumber(element.dataset.aosDelay, 0, 0, 640);

        element.dataset.aosDuration = String(duration);
        element.dataset.aosDelay = String(delay);
        element.style.setProperty('--aos-duration', `${duration}ms`);
        element.style.setProperty('--aos-delay', `${delay}ms`);
    };

    const observe = (element) => {
        if (!observer || observedElements.has(element)) return;
        observer.observe(element);
        observedElements.add(element);
    };

    const addReveal = (element, animation = 'fade-up', delay = 0, duration = 820) => {
        if (!canAnimate(element)) return;

        if (!element.hasAttribute('data-aos')) {
            element.dataset.aos = animation;
            element.dataset.aosAuto = 'true';
            element.dataset.aosDelay = String(delay);
            element.dataset.aosDuration = String(duration);
        }

        if (!allowedAnimations.has(element.dataset.aos)) element.dataset.aos = 'fade-up';
        if (!element.hasAttribute('data-aos-once')) element.dataset.aosOnce = 'true';

        element.classList.add('aos-init');
        applyTiming(element);

        if (motionPreference.matches || !supportsObserver) {
            element.classList.add('aos-animate');
            return;
        }

        observe(element);
    };

    const cardCandidates = (grid) => Array.from(grid.children).filter((element) => {
        if (!canAnimate(element)) return false;
        if (['ARTICLE', 'LI'].includes(element.tagName)) return true;
        if (element.tagName === 'A') return element.children.length > 0;

        const classes = element.className;
        return typeof classes === 'string'
            && /(?:^|\s)(?:rounded(?:-|\s)|shadow(?:-|\s)|border(?:-|\s)|bg-white(?:\/\d+)?(?:\s|$))/.test(classes);
    });

    const decorateHero = (hero) => {
        // Pages may define their own hero sequence. In that case the
        // explicit children are the source of truth and must not receive an
        // additional automatic parent/child animation layer.
        if (hero.hasAttribute('data-aos') || hasAnimatedDescendant(hero)) return;

        const heading = hero.querySelector('h1, h2');
        if (!heading) return;

        addReveal(heading, 'fade-up', 40, 720);

        const copy = Array.from(hero.querySelectorAll('p')).filter((paragraph) => canAnimate(paragraph)).slice(0, 2);
        copy.forEach((paragraph, index) => addReveal(paragraph, 'fade-up', 110 + (index * 55), 700));

        const actions = Array.from(hero.querySelectorAll('a')).filter((link) => {
            if (!canAnimate(link)) return false;
            return link.classList.contains('inline-flex')
                || link.classList.contains('rounded-full')
                || link.classList.contains('rounded-xl')
                || link.classList.contains('rounded-2xl');
        }).slice(0, 2);
        actions.forEach((action, index) => addReveal(action, 'fade-up', 220 + (index * 60), 680));

        const visual = hero.querySelector('figure, picture, img');
        if (visual) addReveal(visual.closest('figure, picture') || visual, 'fade', 160, 760);
    };

    const decorateGrid = (grid) => {
        const cards = cardCandidates(grid);
        if (cards.length < 2) return;

        cards.forEach((card, index) => {
            addReveal(card, 'fade-up', Math.min(index * 65, 260), 680);
        });
    };

    const decorate = (root = document) => {
        if (!(root instanceof Document || root instanceof Element)) return;

        const scope = root;

        if (root instanceof Element && root.hasAttribute('data-aos')) addReveal(root);
        scope.querySelectorAll('[data-aos]').forEach((element) => addReveal(element));

        const mainElements = [];
        if (root instanceof HTMLElement && root.matches('main')) mainElements.push(root);
        mainElements.push(...scope.querySelectorAll('main'));

        mainElements.forEach((main) => {
            // Only animate sections that belong directly to this main. Nested
            // sections are content groups and animating both parent and child
            // transforms makes the entrance look stuck or jerky.
            const sections = Array.from(main.children)
                .filter((element) => element.tagName === 'SECTION' && canAnimate(element));
            const hero = sections[0];

            if (hero) decorateHero(hero);

            sections.slice(hero ? 1 : 0).forEach((section) => {
                // Do not animate a wrapper and its animated children at the
                // same time. Nested opacity/transform transitions produce a
                // blink instead of a clean staggered entrance.
                const hasCardGrid = section.querySelector('.grid article, .grid > article, .grid > li');
                if (!hasAnimatedDescendant(section) && !hasCardGrid) {
                    addReveal(section, 'fade-up', 0, 680);
                }
            });

            Array.from(main.children).forEach((child) => {
                if (!sections.length
                    && ['DIV', 'ARTICLE', 'FORM'].includes(child.tagName)
                    && !hasAnimatedDescendant(child)) {
                    addReveal(child, 'fade-up', 0, 680);
                }
            });

            if (!sections.length) {
                Array.from(main.children)
                    .filter((child) => canAnimate(child) && !hasAnimatedDescendant(child))
                    .forEach((child, index) => addReveal(child, 'fade-up', Math.min(index * 60, 240), 680));
            }

            main.querySelectorAll('.grid').forEach((grid) => {
                const owner = grid.closest('section');
                if (owner?.classList.contains('aos-init')) return;
                decorateGrid(grid);
            });
        });

        // Some authentication and verification views intentionally use a
        // standalone layout without <main>. Give those pages an entry reveal
        // too, without broad selectors that would animate application chrome.
        if (root instanceof Document && mainElements.length === 0) {
            const pageRoot = Array.from(body.children).find((element) => (
                element instanceof HTMLElement && element.tagName === 'DIV' && canAnimate(element)
            ));
            if (pageRoot) addReveal(pageRoot, 'fade', 0, 620);
        }

        const pageEntries = [];
        if (root instanceof HTMLElement && root.matches('.page-enter')) pageEntries.push(root);
        pageEntries.push(...scope.querySelectorAll('.page-enter'));
        pageEntries.forEach((entry) => {
            if (entry.tagName !== 'MAIN') addReveal(entry, 'fade-up', 0, 660);
        });

        const footers = [];
        if (root instanceof HTMLElement && root.matches('footer')) footers.push(root);
        footers.push(...scope.querySelectorAll('footer'));
        footers.forEach((footer) => addReveal(footer, 'fade', 0, 620));
    };

    const revealAll = () => {
        observer?.disconnect();
        document.querySelectorAll('[data-aos]').forEach((element) => {
            element.classList.add('aos-animate');
            element.style.removeProperty('will-change');
        });
    };

    const replayPageEntrance = () => {
        if (motionPreference.matches || !supportsObserver) {
            revealAll();
            return;
        }

        observer?.disconnect();
        observedElements = new WeakSet();
        initialViewportReady = false;

        document.querySelectorAll('[data-aos].aos-init').forEach((element) => {
            element.classList.remove('aos-animate');
            element.style.removeProperty('will-change');
        });

        document.documentElement.offsetHeight;
        document.querySelectorAll('[data-aos].aos-init').forEach(observe);

        window.requestAnimationFrame(() => {
            revealInitialViewport();
            initialViewportReady = true;
        });
    };

    // IntersectionObserver is asynchronous. Reveal elements already visible on
    // first paint explicitly so hero and page-intro content never waits for a
    // scroll event or an observer callback.
    const revealInitialViewport = () => {
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

        document.querySelectorAll('[data-aos].aos-init').forEach((element) => {
            const bounds = element.getBoundingClientRect();
            if (bounds.top < viewportHeight && bounds.bottom > 0) {
                element.classList.add('aos-animate');
            }
        });
    };

    if (!motionPreference.matches && supportsObserver) {
        const compactViewport = window.matchMedia('(max-width: 767px)').matches;
        observer = new IntersectionObserver((entries, currentObserver) => {
            // The observer can report initial intersections immediately after
            // observe(). Hold those callbacks until the first paint has shown
            // the hidden state; otherwise the first viewport can appear
            // without a visible entrance transition.
            if (!initialViewportReady) return;

            entries.forEach((entry) => {
                const element = entry.target;
                const repeats = element.dataset.aosOnce === 'false';

                if (entry.isIntersecting) {
                    element.classList.add('aos-animate');
                    if (!repeats) currentObserver.unobserve(element);
                } else if (repeats) {
                    element.classList.remove('aos-animate');
                }
            });
        }, {
            threshold: compactViewport ? 0.08 : 0.12,
            rootMargin: compactViewport ? '0px 0px -20px' : '0px 0px -44px',
        });
    }

    decorate();
    if (motionPreference.matches || !supportsObserver) {
        revealAll();
    } else {
        // Commit the hidden state before revealing the first viewport. Two
        // short frames are enough for the browser to paint the initial state
        // without introducing the long blank delay that causes a flash.
        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                revealInitialViewport();
                initialViewportReady = true;
            });
        });
    }

    const scheduleDecoration = (root) => {
        if (root.closest?.(blockedSelector)) return;

        pendingRoots.add(root.closest?.('main') || root);
        if (decorationFrame) return;

        decorationFrame = window.requestAnimationFrame(() => {
            pendingRoots.forEach((pendingRoot) => decorate(pendingRoot));
            pendingRoots.clear();
            decorationFrame = undefined;
            if (!motionPreference.matches && supportsObserver) revealInitialViewport();
        });
    };

    const mutationObserver = new MutationObserver((mutations) => {
        const addedRoots = mutations.flatMap(({ addedNodes }) => Array.from(addedNodes))
            .filter((node) => node.nodeType === Node.ELEMENT_NODE);

        addedRoots.forEach(scheduleDecoration);
    });

    mutationObserver.observe(body, { childList: true, subtree: true });

    motionPreference.addEventListener?.('change', ({ matches }) => {
        if (matches) revealAll();
    });

    window.addEventListener('load', () => {
        if (motionPreference.matches || !supportsObserver) {
            revealAll();
            return;
        }

        // Images and fonts may settle after DOMContentLoaded. Reveal any
        // initial-viewport targets still waiting, without reinitializing AOS.
        revealInitialViewport();
        initialViewportReady = true;
    }, { once: true });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) replayPageEntrance();
    });

    document.addEventListener('aos:refresh', () => decorate());
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAosAnimations, { once: true });
} else {
    initAosAnimations();
}
