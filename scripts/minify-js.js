// Script pour minifier les fichiers JavaScript dans public/js/
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { minify } from 'terser';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const jsDir = path.join(__dirname, '..', 'public', 'js');
const filesToMinify = [
    'main.js',
    'article-editor.js',
    'sidebar-navigation.js',
    'sidebar-sticky.js'
];

async function minifyFile(fileName) {
    const filePath = path.join(jsDir, fileName);
    const minifiedPath = path.join(jsDir, fileName.replace('.js', '.min.js'));
    
    try {
        // Lire le fichier
        const code = fs.readFileSync(filePath, 'utf8');
        
        // Minifier le code
        const result = await minify(code, {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug'],
            },
            format: {
                comments: false,
            },
            mangle: {
                reserved: ['$', 'jQuery', 'axios'], // Réserver certains noms
            },
        });
        
        if (result.error) {
            console.error(`Erreur lors de la minification de ${fileName}:`, result.error);
            return;
        }
        
        // Écrire le fichier minifié
        fs.writeFileSync(minifiedPath, result.code);
        
        // Calculer la réduction de taille
        const originalSize = fs.statSync(filePath).size;
        const minifiedSize = fs.statSync(minifiedPath).size;
        const reduction = ((1 - minifiedSize / originalSize) * 100).toFixed(2);
        
        console.log(`✅ ${fileName} minifié: ${originalSize} bytes → ${minifiedSize} bytes (${reduction}% de réduction)`);
    } catch (error) {
        console.error(`❌ Erreur lors de la minification de ${fileName}:`, error.message);
    }
}

async function minifyAll() {
    console.log('🚀 Début de la minification des fichiers JavaScript...\n');
    
    // Vérifier que le répertoire existe
    if (!fs.existsSync(jsDir)) {
        console.error(`❌ Le répertoire ${jsDir} n'existe pas`);
        process.exit(1);
    }
    
    // Minifier chaque fichier
    for (const file of filesToMinify) {
        const filePath = path.join(jsDir, file);
        if (fs.existsSync(filePath)) {
            await minifyFile(file);
        } else {
            console.warn(`⚠️  Le fichier ${file} n'existe pas`);
        }
    }
    
    console.log('\n✨ Minification terminée !');
}

// Exécuter la minification
minifyAll().catch(error => {
    console.error('❌ Erreur fatale:', error);
    process.exit(1);
});

