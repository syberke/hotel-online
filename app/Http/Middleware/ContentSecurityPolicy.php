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
        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "form-action 'self' https://*.midtrans.com",
            "script-src 'self' 'nonce-{$nonce}' https://snap-assets.sandbox.midtrans.com https://app.sandbox.midtrans.com https://app.midtrans.com https://api.sandbox.midtrans.com https://api.midtrans.com https://www.google.com https://www.gstatic.com https://pay.google.com https://www.googletagmanager.com https://unpkg.com https://cdnjs.cloudflare.com",
            "script-src-attr 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://unpkg.com https://cdnjs.cloudflare.com",
            "font-src 'self' data: https://fonts.bunny.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' https://*.midtrans.com https://*.google.com https://*.googleapis.com https://*.gopayapi.com",
            "frame-src 'self' https://*.midtrans.com https://pay.google.com https://www.google.com",
            "worker-src 'self' blob:",
            'upgrade-insecure-requests',
        ]);
    }
}
