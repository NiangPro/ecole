<script>
document.addEventListener('DOMContentLoaded', function () {
    var viewport = document.getElementById('eprDocViewport');
    if (!viewport) return;

    var prevBtn = document.getElementById('eprDocPrev');
    var nextBtn = document.getElementById('eprDocNext');

    function step() {
        var card = viewport.querySelector('.epr-doc-card');
        var gap = 20;
        return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth * 0.8;
    }

    function updateNavState() {
        var max = viewport.scrollWidth - viewport.clientWidth - 2;
        prevBtn.disabled = viewport.scrollLeft <= 0;
        nextBtn.disabled = viewport.scrollLeft >= max;
    }

    prevBtn.addEventListener('click', function () {
        viewport.scrollBy({ left: -step(), behavior: 'smooth' });
    });
    nextBtn.addEventListener('click', function () {
        viewport.scrollBy({ left: step(), behavior: 'smooth' });
    });
    viewport.addEventListener('scroll', updateNavState, { passive: true });
    window.addEventListener('resize', updateNavState);
    updateNavState();
});
</script>
