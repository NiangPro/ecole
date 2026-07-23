// axios était importé ici mais n'est appelé nulle part ailleurs dans le code (vérifié :
// aucune occurrence de `axios.` ou `window.axios` en dehors de ce fichier) — ~15 Ko gzip
// chargés pour rien sur chaque page. Les appels HTTP du site utilisent fetch() natif
// (voir public/js/*.js : social-features.js, analytics-tracker.js, etc.).
