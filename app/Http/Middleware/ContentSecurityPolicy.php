<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(18));
        config(['app.csp_nonce' => $nonce]);

        $response = $next($request);

        if ($this->isHtml($response)) {
            $content = $response->getContent();
            if (is_string($content)) {
                $response->setContent(preg_replace(
                    '/<script(?![^>]*\bnonce=)/i',
                    '<script nonce="'.$nonce.'"',
                    $content,
                ));
            }
        }

        $response->headers->set('Content-Security-Policy', $this->policy($nonce));
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');

        return $response;
    }

    private function isHtml(Response $response): bool
    {
        return Str::contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    private function policy(string $nonce): string
    {
        $isLocal = app()->environment('local');
        $viteHttp = $isLocal ? ' http://localhost:5173 http://127.0.0.1:5173' : '';
        $viteSocket = $isLocal ? ' ws://localhost:5173 ws://127.0.0.1:5173' : '';

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "form-action 'self' https://*.midtrans.com",
            "script-src 'self' 'nonce-{$nonce}'{$viteHttp} https://snap-assets.sandbox.midtrans.com https://app.sandbox.midtrans.com https://app.midtrans.com https://api.sandbox.midtrans.com https://api.midtrans.com https://www.google.com https://www.gstatic.com https://pay.google.com https://www.googletagmanager.com https://unpkg.com https://cdnjs.cloudflare.com",
            "script-src-attr 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline'{$viteHttp} https://fonts.bunny.net https://unpkg.com https://cdnjs.cloudflare.com",
            "font-src 'self' data: https://fonts.bunny.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https:",
            "connect-src 'self'{$viteHttp}{$viteSocket} https://*.midtrans.com https://*.google.com https://*.googleapis.com https://*.gopayapi.com",
            "frame-src 'self' https://*.midtrans.com https://pay.google.com https://www.google.com",
            "worker-src 'self' blob:",
        ];

        if (! $isLocal) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }
}
