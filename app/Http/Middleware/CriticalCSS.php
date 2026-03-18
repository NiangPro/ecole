<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CriticalCSS
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if (str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $criticalCSS = $this->getCriticalCSS($request->path());
            $content = $response->getContent();
            
            $content = str_replace(
                '</head>',
                "<style>{$criticalCSS}</style>\n</head>",
                $content
            );
            
            $content = str_replace(
                'rel="stylesheet"',
                'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"',
                $content
            );
            
            $response->setContent($content);
        }
        
        return $response;
    }
    
    private function getCriticalCSS($path)
    {
        // CSS critique optimisé pour la page d'accueil
        if ($path === '/' || $path === '') {
            return '
                /* CSS Critique - Above the Fold */
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; }
                .hero-section { 
                    position: relative; 
                    min-height: 65vh; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    background: linear-gradient(135deg, #1e3a8a 0%, #2d3748 50%, #1e293b 100%);
                    z-index: 2;
                }
                .hero-content { 
                    max-width: 1200px; 
                    margin: 0 auto; 
                    text-align: center; 
                    animation: fadeInUp 1s ease-out; 
                }
                @keyframes fadeInUp { 
                    from { opacity: 0; transform: translateY(40px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .main-title { 
                    font-size: clamp(2.5rem, 5vw, 4rem); 
                    font-weight: 900; 
                    color: #ffffff; 
                    margin-bottom: 25px;
                    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
                }
                .subtitle { 
                    font-size: clamp(1.1rem, 2.5vw, 1.4rem); 
                    color: rgba(255, 255, 255, 0.95); 
                    margin-bottom: 40px; 
                    max-width: 1100px; 
                    margin-left: auto; 
                    margin-right: auto;
                }
                .cta-buttons { 
                    display: flex; 
                    gap: 20px; 
                    justify-content: center; 
                    flex-wrap: wrap; 
                    margin-bottom: 60px; 
                }
                .btn-3d { 
                    padding: 16px 36px; 
                    font-size: 1rem; 
                    font-weight: 700; 
                    border: none; 
                    border-radius: 10px; 
                    cursor: pointer; 
                    text-decoration: none; 
                    display: inline-flex; 
                    align-items: center; 
                    gap: 10px;
                    transition: all 0.3s ease;
                }
                .btn-primary { 
                    background: linear-gradient(135deg, #06b6d4, #14b8a6); 
                    color: #000; 
                    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
                }
                .btn-primary:hover { 
                    transform: translateY(-4px); 
                    box-shadow: 0 10px 30px rgba(6, 182, 212, 0.6);
                }
                .btn-secondary { 
                    background: rgba(255, 255, 255, 0.1); 
                    color: #fff; 
                    border: 2px solid rgba(255, 255, 255, 0.3); 
                    backdrop-filter: blur(10px);
                }
                .tech-carousel-wrapper { 
                    margin-top: 40px; 
                    padding: 40px 20px; 
                    background: rgba(255, 255, 255, 0.05); 
                    border-radius: 20px; 
                    backdrop-filter: blur(10px);
                }
                .tech-card-carousel { 
                    background: rgba(255, 255, 255, 0.1); 
                    border-radius: 15px; 
                    padding: 30px; 
                    text-align: center; 
                    border: 1px solid rgba(255, 255, 255, 0.2); 
                    transition: all 0.3s ease;
                }
                .tech-icon-carousel { 
                    font-size: 4rem; 
                    margin-bottom: 20px; 
                    display: flex; 
                    justify-content: center; 
                    align-items: center; 
                    height: 100px;
                }
                .tech-name-carousel { 
                    font-size: 1.5rem; 
                    font-weight: 700; 
                    color: #ffffff; 
                    margin-bottom: 20px;
                }
                .tech-link-carousel { 
                    background: linear-gradient(135deg, #06b6d4, #14b8a6); 
                    color: #000; 
                    padding: 12px 24px; 
                    border-radius: 8px; 
                    text-decoration: none; 
                    font-weight: 600; 
                    display: inline-flex; 
                    align-items: center; 
                    gap: 8px;
                    transition: all 0.3s ease;
                }
                @media (max-width: 768px) {
                    .hero-section { min-height: 55vh; padding: 60px 20px 40px; }
                    .subtitle { display: none; }
                    .main-title { font-size: clamp(1.8rem, 4vw, 2.2rem) !important; }
                    .cta-buttons { flex-direction: column; align-items: center; }
                    .tech-carousel-wrapper { margin-top: 20px; padding: 20px 15px; }
                    .tech-card-carousel { padding: 20px; }
                    .tech-icon-carousel { font-size: 3rem; height: 80px; }
                }
            ';
        }
        
        // CSS critique pour les autres pages
        return '
            /* CSS Critique - Pages Secondaires */
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; }
            header { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 1000; }
            .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
            h1, h2, h3 { margin-bottom: 20px; color: #1a202c; }
            p { margin-bottom: 16px; color: #4a5568; }
            .btn { display: inline-flex; align-items: center; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
            .btn-primary { background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; }
            @media (max-width: 768px) {
                .container { padding: 0 15px; }
                h1 { font-size: 2rem; }
                h2 { font-size: 1.5rem; }
                h3 { font-size: 1.25rem; }
            }
        ';
    }
}
