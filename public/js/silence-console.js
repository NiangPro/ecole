// Script minimaliste - ZERO log dans la console
console.log('🔧 NiangProgrammeur - Mode Silence Activé');

// PAS de logs de débogage
// PAS de vérifications automatiques
// PAS de messages de statut

// Uniquement les erreurs critiques seront affichées
window.addEventListener('error', function(e) {
    console.error('❌ ERREUR CRITIQUE:', e.message);
});

// C'est TOUT. La console doit rester silencieuse.
