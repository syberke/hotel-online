@props(['portal' => 'dashboard'])

<section
    data-oasis-dashboard-connection
    data-portal="{{ strtolower((string) $portal) }}"
    data-state="offline"
    hidden
    role="status"
    aria-live="polite"
    aria-atomic="true"
    class="oasis-dashboard-connection"
>
    <div class="oasis-dashboard-connection__icon" aria-hidden="true">
        <i data-oasis-connection-icon class="fa-solid fa-wifi"></i>
    </div>

    <div class="oasis-dashboard-connection__copy">
        <p data-oasis-connection-eyebrow class="oasis-dashboard-connection__eyebrow">OFFLINE MODE</p>
        <h2 data-oasis-connection-title class="oasis-dashboard-connection__title">Koneksi terputus</h2>
        <p data-oasis-connection-message class="oasis-dashboard-connection__message">
            Data dashboard mungkin belum terbaru. Aksi operasional tidak akan dikirim sampai koneksi kembali.
        </p>
    </div>

    <div class="oasis-dashboard-connection__actions">
        <span data-oasis-connection-badge class="oasis-dashboard-connection__badge">Offline</span>
        <button type="button" data-oasis-connection-retry class="oasis-dashboard-connection__retry">
            Coba lagi
        </button>
    </div>
</section>
