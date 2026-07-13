<x-mail::message>
{{-- 1. TOTAL OVERRIDE LOGO OASIS HEADER VIA PURE EMAIL-SAFE HTML & CSS --}}
<x-slot:header>
<table class="header" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; margin: 0; padding: 0;">
    <tr>
        <td class="header" style="padding: 40px 0 25px 0; text-align: center;">
            <a href="{{ config('app.url') }}" style="display: inline-block; text-decoration: none; text-align: center;">
                {{-- Menggunakan font Georgia bawaan sistem yang aman untuk email dengan ukuran besar --}}
                <span style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 42px; font-style: italic; color: #b45309; font-weight: normal; letter-spacing: 2px; display: block; line-height: 1.1; margin: 0 auto;">
                    Oasis
                </span>
                <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 5px; color: #737373; margin-top: 6px; display: block; line-height: 1;">
                    Sanctuary Hotel & Resort
                </span>
            </a>
        </td>
    </tr>
</table>
</x-slot:header>

{{-- 2. GREETING SECTION --}}
@if (! empty($greeting))
<span style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 20px; font-style: italic; color: #171717; display: block; margin-bottom: 10px;">
{{ $greeting }}
</span>
@else
@if ($level === 'error')
<span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 16px; font-weight: bold; color: #dc2626; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 10px;">
⚠️ @lang('Whoops!')
</span>
@else
<span style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 20px; font-style: italic; color: #171717; display: block; margin-bottom: 10px;">
✨ @lang('Warmest Greetings,')
</span>
@endif
@endif

{{-- 3. INTRO LINES / DESKRIPSI UTAMA --}}
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13px; line-height: 1.8; color: #404040; font-weight: 400; margin-bottom: 20px;">
@foreach ($introLines as $line)
{{ $line }}
@endforeach
</div>

{{-- 4. ACTION BUTTON PANELS --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success' => 'success',
        'error' => 'error',
        default => 'primary',
    };
?>
<div align="center" style="margin: 30px 0;">
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
</div>
@endisset

{{-- 5. OUTRO LINES --}}
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13px; line-height: 1.8; color: #404040; margin-bottom: 25px;">
@foreach ($outroLines as $line)
{{ $line }}
@endforeach
</div>

{{-- 6. PRESTIGIOUS SALUTATION --}}
@if (! empty($salutation))
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13px; color: #525252; border-top: 1px solid #f5f5f5; padding-top: 15px;">
{{ $salutation }}
</div>
@else
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13px; color: #525252; border-top: 1px solid #e5e5e5; padding-top: 15px; line-height: 1.6;">
Best regards,<br>
<strong style="color: #b45309; font-family: 'Georgia', 'Times New Roman', serif; font-style: italic; font-size: 14px;">Oasis Concierge & Hospitality Management</strong>
</div>
@endif

{{-- 7. SUBCOPY / SECURITY FOOTER LINK LINK --}}
@isset($actionText)
<x-slot:subcopy>
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 11px; color: #737373; line-height: 1.6; border-top: 1px dashed #e5e5e5; padding-top: 15px;">
@lang(
    "If you are experiencing structural disruption clicking the \":actionText\" interface, kindly extract and replicate the secure URL below into your preferred local web browser:",
    ['actionText' => $actionText]
)
<br>
<a href="{{ $actionUrl }}" style="color: #b45309; text-decoration: underline; font-family: monospace; display: block; margin-top: 6px; word-break: break-all;">{{ $actionUrl }}</a>
</div>
</x-slot:subcopy>
@endisset

{{-- 8. BRAND PRIVACY FOOTER EXTRA INFO --}}
<x-slot:footer>
<x-mail::footer>
<div align="center" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 10px; color: #a3a3a3; line-height: 1.5; padding: 10px 0;">
© {{ date('Y') }} **Oasis Hotel & Resort**. All rights reserved.<br>
<span style="color: #bcbcbc; font-size: 9px;">Kawasan Eksklusif ITDC Lot 8, Nusa Dua, Bali, Indonesia.</span>
</div>
</x-mail::footer>
</x-slot:footer>
</x-mail::message>