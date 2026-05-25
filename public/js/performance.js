(function () {
    function optimizeImages() {
        document.querySelectorAll('img:not([loading])').forEach(function (img) {
            img.loading = 'lazy';
            img.decoding = 'async';
            if (!img.width && !img.height && img.naturalWidth && img.naturalHeight) {
                img.width = img.naturalWidth;
                img.height = img.naturalHeight;
            }
        });
    }

    function reduceCLS() {
        var style = document.createElement('style');
        style.textContent = '.hero-section{contain:layout style paint}.tech-card-carousel{contain:layout}img{contain:layout}';
        document.head.appendChild(style);
    }

    function monitorPerf() {
        if (!('PerformanceObserver' in window)) return;
        try {
            new PerformanceObserver(function (list) {
                list.getEntries().forEach(function (entry) {
                    if (entry.entryType === 'largest-contentful-paint') {
                        window._lcpTime = entry.startTime;
                    }
                });
            }).observe({ entryTypes: ['largest-contentful-paint'] });
        } catch (e) {}
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            optimizeImages();
            reduceCLS();
        });
    } else {
        optimizeImages();
        reduceCLS();
    }

    monitorPerf();
})();
