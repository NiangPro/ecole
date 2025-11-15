import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => ({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // Minification activée en production uniquement
        minify: mode === 'production' ? 'terser' : false,
        terserOptions: mode === 'production' ? {
            compress: {
                drop_console: true, // Supprimer console.log en production
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug'],
            },
            format: {
                comments: false, // Supprimer les commentaires
            },
        } : {},
        // Optimisation CSS en production
        cssMinify: mode === 'production',
        // Génération de source maps (désactivé en production pour la performance)
        sourcemap: mode !== 'production',
        // Rollup options pour l'optimisation
        rollupOptions: {
            output: {
                // Optimisation des noms de fichiers pour le cache
                chunkFileNames: mode === 'production' ? 'js/[name]-[hash].js' : 'js/[name].js',
                entryFileNames: mode === 'production' ? 'js/[name]-[hash].js' : 'js/[name].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return mode === 'production' ? 'css/[name]-[hash][extname]' : 'css/[name][extname]';
                    }
                    return mode === 'production' ? 'assets/[name]-[hash][extname]' : 'assets/[name][extname]';
                },
                // Optimisation du code mort (tree shaking)
                manualChunks: undefined,
            },
        },
        // Augmenter la limite de taille pour les warnings
        chunkSizeWarningLimit: 1000,
    },
    // Optimisation pour la production
    ...(mode === 'production' && {
        esbuild: {
            drop: ['console', 'debugger'],
        },
    }),
}));
