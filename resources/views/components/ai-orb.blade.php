<a href="{{ route('ai-assistant.index') }}"
   class="ai-orb"
   data-ai-orb
   aria-label="Buka AI Assistant">

    <img src="{{ asset('images/ai.png') }}"
         alt="AI Assistant"
         class="ai-orb__art">
</a>

<style>
    .ai-orb {
        --orb-size: 150px;

        position: fixed;
        right: 25px;
        bottom: 25px;
        width: var(--orb-size);
        height: var(--orb-size);
        z-index: 9999;

        display: flex;
        align-items: center;
        justify-content: center;

        background: transparent;
        border: none;
        text-decoration: none;
        cursor: grab;
        touch-action: none;
        user-select: none;
        -webkit-user-select: none;

        transition: transform 0.2s ease;
    }

    .ai-orb__art {
        width: 100%;
        height: 100%;
        object-fit: contain;
        pointer-events: none;

        filter:
            drop-shadow(0 0 5px rgba(0, 190, 255, 0.8))
            drop-shadow(0 0 12px rgba(110, 70, 255, 0.6));
    }

    .ai-orb:hover {
        transform: scale(1.08);
    }

    .ai-orb:hover .ai-orb__art {
        filter:
            drop-shadow(0 0 8px rgba(0, 190, 255, 1))
            drop-shadow(0 0 18px rgba(110, 70, 255, 0.8));
    }

    .ai-orb:active {
        cursor: grabbing;
    }

    @media (max-width: 768px) {
        .ai-orb {
            --orb-size: 60px;
            right: 15px;
            bottom: 15px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const orb = document.querySelector('[data-ai-orb]');
    if (!orb) return;

    const storageKey = 'kulosehat-ai-orb-position';

    let dragging = false;
    let moved = false;
    let startX = 0;
    let startY = 0;
    let offsetX = 0;
    let offsetY = 0;

    const saved = localStorage.getItem(storageKey);

    if (saved) {
        const position = JSON.parse(saved);

        orb.style.left = position.x + 'px';
        orb.style.top = position.y + 'px';
        orb.style.right = 'auto';
        orb.style.bottom = 'auto';
    }

    orb.addEventListener('pointerdown', function (e) {
        if (e.button !== 0 && e.pointerType === 'mouse') return;

        dragging = true;
        moved = false;

        const rect = orb.getBoundingClientRect();

        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;

        startX = e.clientX;
        startY = e.clientY;

        orb.style.transition = 'none';
        orb.setPointerCapture(e.pointerId);

        e.preventDefault();
    });

    orb.addEventListener('pointermove', function (e) {
        if (!dragging) return;

        if (Math.hypot(
            e.clientX - startX,
            e.clientY - startY
        ) > 3) {
            moved = true;
        }

        if (!moved) return;

        let x = e.clientX - offsetX;
        let y = e.clientY - offsetY;

        x = Math.max(8, Math.min(
            x,
            window.innerWidth - orb.offsetWidth - 8
        ));

        y = Math.max(8, Math.min(
            y,
            window.innerHeight - orb.offsetHeight - 8
        ));

        orb.style.left = x + 'px';
        orb.style.top = y + 'px';
        orb.style.right = 'auto';
        orb.style.bottom = 'auto';
    });

    function stopDragging() {
        if (!dragging) return;

        dragging = false;
        orb.style.transition = 'transform 0.2s ease';

        if (moved) {
            const rect = orb.getBoundingClientRect();

            localStorage.setItem(storageKey, JSON.stringify({
                x: rect.left,
                y: rect.top
            }));
        }
    }

    orb.addEventListener('pointerup', stopDragging);
    orb.addEventListener('pointercancel', stopDragging);

    orb.addEventListener('click', function (e) {
        if (moved) {
            e.preventDefault();
            e.stopPropagation();
            moved = false;
        }
    });

    window.addEventListener('resize', function () {
        const rect = orb.getBoundingClientRect();

        const x = Math.max(8, Math.min(
            rect.left,
            window.innerWidth - orb.offsetWidth - 8
        ));

        const y = Math.max(8, Math.min(
            rect.top,
            window.innerHeight - orb.offsetHeight - 8
        ));

        orb.style.left = x + 'px';
        orb.style.top = y + 'px';
        orb.style.right = 'auto';
        orb.style.bottom = 'auto';
    });
});
</script>