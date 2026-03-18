document.addEventListener('DOMContentLoaded', function() {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                }
                
                imageObserver.unobserve(img);
            }
        });
    }, {
        rootMargin: '50px 0px',
        threshold: 0.01
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
    
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const section = entry.target;
                
                if (section.dataset.endpoint) {
                    fetch(section.dataset.endpoint)
                        .then(response => response.text())
                        .then(html => {
                            section.innerHTML = html;
                        });
                }
                
                sectionObserver.unobserve(section);
            }
        });
    });
    
    document.querySelectorAll('[data-lazy-section]').forEach(section => {
        sectionObserver.observe(section);
    });
});
