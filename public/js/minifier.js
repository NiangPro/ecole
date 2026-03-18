// Minification simple des fichiers JS
function minifyJS(content) {
    return content
        .replace(/\/\*[\s\S]*?\*\//g, '') // Supprimer les commentaires multi-lignes
        .replace(/\/\/.*$/gm, '') // Supprimer les commentaires mono-lignes
        .replace(/\s+/g, ' ') // Remplacer les espaces multiples par un seul
        .replace(/;\s*}/g, '}') // Supprimer les points-virgules avant }
        .replace(/\s*([{}();,])\s*/g, '$1') // Supprimer les espaces autour des opérateurs
        .replace(/^\s+|\s+$/gm, ''); // Supprimer les espaces en début/fin de ligne
}

// Minification simple des fichiers CSS
function minifyCSS(content) {
    return content
        .replace(/\/\*[\s\S]*?\*\//g, '') // Supprimer les commentaires
        .replace(/\s+/g, ' ') // Remplacer les espaces multiples par un seul
        .replace(/;\s*}/g, '}') // Supprimer les points-virgules avant }
        .replace(/\s*([{};:,])\s*/g, '$1') // Supprimer les espaces autour des opérateurs
        .replace(/^\s+|\s+$/gm, ''); // Supprimer les espaces en début/fin de ligne
}

// Exporter les fonctions
window.minifyJS = minifyJS;
window.minifyCSS = minifyCSS;
