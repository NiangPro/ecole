// Service d'optimisation WebP simple
function getOptimizedImage(path, alt, className, width, height) {
    const webpPath = path.replace(/\.(jpg|jpeg|png)$/i, '.webp');
    const baseUrl = window.location.origin;
    
    // Créer l'élément picture avec WebP
    return `<picture>
        <source srcset="${baseUrl}/${webpPath}" type="image/webp">
        <img src="${baseUrl}/${path}" alt="${alt}" class="${className}" loading="lazy" decoding="async">
    </picture>`;
}

// Exporter la fonction
window.getOptimizedImage = getOptimizedImage;
