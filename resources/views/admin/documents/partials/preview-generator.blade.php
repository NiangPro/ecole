{{-- Génère les images d'aperçu public (3 premières pages) depuis le PDF, dans le navigateur via pdf.js --}}
@once
<script integrity="sha512-q+4liFwdPC/bNdhUpZx6aXDx/h77yEQtn4I1slHydcbZK34nLaR3cAeYSJshoxIOq3mjEf7xJE8YWIUHMn+oCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
window.DocPreviewGenerator = (function () {
    const MAX_PAGES = {{ \App\Models\Document::PREVIEW_MAX_PAGES }};
    const TARGET_WIDTH = 900;

    if (window.pdfjsLib) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    async function renderPage(pdf, number) {
        const page = await pdf.getPage(number);
        const base = page.getViewport({ scale: 1 });
        const viewport = page.getViewport({ scale: TARGET_WIDTH / base.width });
        const canvas = document.createElement('canvas');
        canvas.width = Math.round(viewport.width);
        canvas.height = Math.round(viewport.height);
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        await page.render({ canvasContext: ctx, viewport }).promise;
        return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.8));
    }

    async function generate(pdfUrl, uploadUrl) {
        if (!window.pdfjsLib) throw new Error('pdf.js indisponible');
        const pdf = await pdfjsLib.getDocument({ url: pdfUrl, withCredentials: true }).promise;
        const total = Math.min(pdf.numPages, MAX_PAGES);
        const form = new FormData();
        for (let i = 1; i <= total; i++) {
            form.append('pages[]', await renderPage(pdf, i), 'page-' + i + '.jpg');
        }
        const res = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: form,
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error('Envoi refusé (' + res.status + ')');
        return (await res.json()).pages;
    }

    return { generate };
})();
</script>
@endonce
