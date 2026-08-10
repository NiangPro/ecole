<style>
    /* Le module Finances est écrit avec des classes utilitaires Tailwind (chargé
       en CDN, variante dark: basée sur prefers-color-scheme). Le panel admin
       bascule son thème via body.light-mode (cf. admin/layout.blade.php), pas
       via prefers-color-scheme : sans ces overrides, les textes/fonds restent
       calés sur le thème sombre même quand l'utilisateur choisit le mode clair. */
    body.light-mode .finance-module .text-white,
    body.light-mode .finance-module .text-slate-100,
    body.light-mode .finance-module .text-slate-200 {
        color: #1e293b;
    }

    body.light-mode .finance-module .text-slate-300,
    body.light-mode .finance-module .text-slate-400 {
        color: #475569;
    }

    body.light-mode .finance-module .text-slate-500 {
        color: #94a3b8;
    }

    body.light-mode .finance-module .bg-slate-900 {
        background-color: #ffffff;
    }

    body.light-mode .finance-module .bg-slate-800 {
        background-color: #f1f5f9;
    }

    body.light-mode .finance-module .bg-slate-700,
    body.light-mode .finance-module .bg-slate-600,
    body.light-mode .finance-module .hover\:bg-slate-600:hover,
    body.light-mode .finance-module .hover\:bg-slate-700:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    body.light-mode .finance-module .border-slate-600,
    body.light-mode .finance-module .border-slate-700,
    body.light-mode .finance-module .border-slate-800 {
        border-color: #cbd5e1;
    }

    body.light-mode .finance-module option {
        background-color: #ffffff;
        color: #1e293b;
    }
</style>
